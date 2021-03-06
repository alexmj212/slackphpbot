<?php

/**
 * Payload.php
 *
 * PHP version 5
 *
 * @category Includes
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

namespace TrollBots\Lib;
use ErrorException;

/**
 * Class Payload
 *
 * @category Payload
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */
class Payload
{

    /**
     * Token to identify post
     *
     * @var string
     */
    private $_token;

    /**
     * The ID of the Team that send the request
     *
     * @var string
     */
    private $_teamId;

    /**
     * The ID of the channel where the request originated
     *
     * @var string
     */
    private $_channelId;

    /**
     * The name of the channel where the request originated
     *
     * @var string
     */
    private $_channelName;

    /**
     * The ID of the user who submitted the request
     *
     * @var string
     */
    private $_userId;

    /**
     * The name of the user who submitted the request
     *
     * @var string
     */
    private $_userName;

    /**
     * The command that was sent
     *
     * @var string
     */
    private $_command;

    /**
     * The text of the command that was sent
     *
     * @var string
     */
    private $_text;


    /**
     * Payload constructor.
     *
     * @param array $data the post data received
     *
     * @throws ErrorException
     */
    public function __construct($data)
    {
        try {
            if (array_key_exists('token', $data) === true) {
                $this->_token = $data['token'];
            } else {
                throw new ErrorException('token missing from payload');
            }

            if (array_key_exists('team_id', $data) === true) {
                $this->_teamId = $data['team_id'];
            } else {
                throw new ErrorException('team_id missing from payload');
            }

            if (array_key_exists('channel_id', $data) === true) {
                $this->_channelId = $data['channel_id'];
            } else {
                throw new ErrorException('channel_id missing from payload');
            }

            if (array_key_exists('channel_name', $data) === true) {
                $this->_channelName = '#'.strtolower($data['channel_name']);
            } else {
                throw new ErrorException('channel missing from payload');
            }

            if (array_key_exists('user_id', $data) === true) {
                $this->_userId = $data['user_id'];
            } else {
                throw new ErrorException('user_id missing from payload');
            }

            if (array_key_exists('user_name', $data) === true) {
                $this->_userName = '@'.strtolower($data['user_name']);
            } else {
                throw new ErrorException('user_name missing from payload');
            }

            if (array_key_exists('command', $data) === true) {
                $this->_command = strtolower($data['command']);
            } else {
                throw new ErrorException('command missing from payload');
            }

            if (array_key_exists('text', $data) === true) {
                $this->_text = strtolower($data['text']);
            } else {
                throw new ErrorException('text missing from payload');
            }
        } catch (ErrorException $e){
            echo 'Payload Processing Error: ', $e->getMessage(), '\n';
            exit();
        }//end try

    }//end __construct()


    /**
     * Get the Team ID
     *
     * @return mixed
     */
    public function getTeamId()
    {
        return $this->_teamId;

    }//end getTeamId()


    /**
     * Get Channel Name
     *
     * @return string
     */
    public function getChannelName()
    {
        return $this->_channelName;

    }//end getChannelName()


    /**
     * Get Username
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->_userName;

    }//end getUserName()


    /**
     * Get the text of the payload
     *
     * @return string
     */
    public function getText()
    {
        return $this->_text;

    }//end getText()


    /**
     * Get the token of the payload
     *
     * @return string
     */
    public function getToken()
    {
        return $this->_token;

    }//end getToken()


    /**
     * Check if the text contains a valid username
     *
     * @param string $user the given user
     *
     * @return bool
     */
    public static function isUserName($user)
    {

        return !(strpos($user, '@') > 0 ||
            strpos($user, '@') === false ||
            !ctype_alnum(substr($user, 1)));

    }//end isUserName()


    /**
     * Check if the text contains a valid channel name
     *
     * @param string $channel the given channel
     *
     * @return bool
     */
    public static function isChannel($channel)
    {

        return !(strpos($channel, '#') > 0 ||
            strpos($channel, '#') === false ||
            !ctype_alnum(substr($channel, 1)));

    }//end isChannel()


}//end class
