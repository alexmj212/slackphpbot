<?php

/**
 * Class DKPBot
 */
class DKPBot {

	private $botName = 'DKP Bot';

	private $botIcon = ':dragon_face:';

	private $payload;

	private $user;

	private $points;

	public function __construct($data){

		$this->payload = new Payload($data);
		$userPoints = explode(' ',$this->payload->getText());
		if(count($userPoints) === 2){
			$this->user = $userPoints[0];
			$this->points = (int) $userPoints[1];
		}

		if($this->payload->isUserName($this->user) &&  is_numeric($this->points) && $this->points >= -10 && $this->points !== 0 && $this->points <= 10){
			if($this->user === $this->payload->getUserName()){
				$this->points = 0 - $this->points;
				$this->logDKP();
				$text = '*'.$this->payload->getUserName().'* has attempted to grant themselves DKP but instead receives -'.abs($this->points).'DKP';
				$text = $text.'\n'.$this->user.' now has '.$this->retrieveDKP($this->user).'DKP';
				$this->payload->setResponseText($text);
			} else {
				$this->logDKP();
				$text = '*'.$this->payload->getUserName().'* has given *'.$this->user.'* '.$this->points.'DKP';
				$text = $text.'\n'.$this->user.' now has '.$this->retrieveDKP($this->user).'DKP';
				$this->payload->setResponseText($text);
			}
			$responder = new Responder($this->botName, $this->botIcon, $this->payload->getResponseText(), $this->payload->getChannelName(), 1);
		} else if ($this->payload->getText() === 'score'){
			$responder = new Responder($this->botName, $this->botIcon, 'You have '.$this->retrieveDKP($this->payload->getUserName()).'DKP', $this->payload->getChannelName(), 0);
		} else if ($this->payload->getText() === 'rank'){
			$responder = new Responder($this->botName, $this->botIcon, $this->ranking(), $this->payload->getChannelName(), 0);
		} else {
			$responder = new Responder($this->botName, $this->botIcon, 'Invalid command', $this->payload->getChannelName(), 0);
		}
	}

    /**
     *
     */
    private function logDKP(){

        date_default_timezone_set('UTC');
        $database = new DataSource();
        $collection = $database->getCollection('dkpbot');

		//Does this team exist?
		if($document = $collection->findOne(array('team_id'=>$this->payload->getTeamId()))){
			//Yes this team exists
			$users = $document['users'];
			//Does this user exist?
			if(array_key_exists($this->user,$users)){
				//Yes this user exists
				$users[$this->user]['dkp'] += $this->points;
	                        $users[$this->user]['last_received_date'] = date('Y-m-d H:i:s');
			} else {
				//No, add this user, start them at 500
        	                $users[$this->user]['dkp'] = 500 + $this->points;
	                        $users[$this->user]['created'] = date('Y-m-d H:i:s');
                        	$users[$this->user]['last_received_date'] = date('Y-m-d H:i:s');
                	}
			//Save the new information
			$document['users'] = $users;
			$collection->update(array('team_id'=>$this->payload->getTeamId()),$document);
		} else {
			//No, this team doesn't exist
			$team = array(
				'team_id'=>$this->payload->getTeamId(),
				'users'=>array(
					$this->user => array(
				                'dkp' => 500 + $this->points,
                		                'created' => date('Y-m-d H:i:s'),
		                                'last_received_date' => date('Y-m-d H:i:s')
					)
				)
			);
			$collection->insert($team);
		}
    }

    /**
     * @return string
     */
    private function ranking(){
		$database = new DataSource();
		$collection = $database->getCollection('dkpbot');
		$data = $collection->findOne(array('team_id'=>$this->payload->getTeamId()));
		//Preserve array keys for sorting
		foreach($data['users'] as $username=>$user){
			$data['users'][$username]['username'] = $username;
		}
		//Sort in desc by DKP
		usort($data['users'], function($a, $b) {
    			return $b['dkp'] - $a['dkp'];
		});
		$leaderBoard = '*DKP Leaderboard*\n';
		foreach($data['users'] as $user){
			$leaderBoard .= $user['dkp'].' DKP\t\t';
			if($user['username'] === $this->payload->getUserName()){
				$leaderBoard .= '*'.$user['username'].'*';
			} else {
				$leaderBoard .= $user['username'];
			}
			$leaderBoard .= '\n';
		}
		$leaderBoard .= 'If you\'re not listed, you\'ve not received DKP';
		return $leaderBoard;
	}

    /**
     * @param $userName
     * @return int
     */
    private function retrieveDKP($userName){
		$database = new DataSource();
		$collection = $database->getCollection('dkpbot');

        if($document = $collection->findOne(array('team_id'=>$this->payload->getTeamId()))){
			if(array_key_exists($this->user,$document['users'])){
	                        return $document['users'][$userName]['dkp'];
			} else { return 500; }
        } else { return 500; }
    }

}