<?php

/**
 * Tip Bot for Slack
 * Alex Johnson
 * Main
 */

/**
 * Description:
 * * Driver for the application
 * * Recieve data, rocess and send responses
 */

require 'vendor/autoload.php';
include 'includes/processPayload.php';

//Initialize Slim Framework
$app = new \Slim\Slim();

//Loads the web view for displaying stastics
$app->get( '/', 'main');

//For displaying the readme on the about page
$app->get( '/README.md', 'readme');

//Define 'tip' endpoint and associated controller
$app->post( '/tip/', 'tip');

//Define 'triggered' endpoint
$app->post( '/triggered/', 'triggered');

//Execute Slim framework processing
$app->run();

    /**
     * tip function
     * Controller for the tip endpoint
     */

    function tip (){

        global $app;

        $payload = new ProcessPayload($app->request->post());
        $payload->payloadType = $payload->parseCommand();

    }

    function triggered (){

        global $app;

        $payload = new ProcessPayload($app->request->post());

        $payload->responseType = "triggered";

        if($payload->isUserName()){
            $payload->response($payload->userName." has been triggered by ".$payload->text."!","triggered");
        } else {
            $payload->response($payload->userName." has been triggered!","triggered");
        }

    }

    function main (){
        header('Location: /dist/index.html');
        die();
    }

    function readme(){
        return file_get_contents('README.md');
    }





?>
