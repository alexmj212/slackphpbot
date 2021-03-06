<?php

/**
 * Responder.php
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

/**
 * Class Responder
 *
 * @category Responder
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

class Responder
{

    /**
     * The post that will be used to respond
     *
     * @var Post
     */
    private $_post;


    /**
     * Set the post content
     *
     * @param Post $post The post that is sent to Slack
     */
    public function __construct(&$post)
    {
        $this->_post = &$post;

    }//end __construct()


    /**
     * Echo the contents of the post to Slack
     *
     * @return void
     */
    public function respond()
    {

        // Set response header to json type.
        header('Content-Type: application/json');
        echo $this->_post->toString();
        exit();

    }//end respond()


}//end class
