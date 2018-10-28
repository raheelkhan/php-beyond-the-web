<?php

/**
 *
 * This file run as a HTTP server based on libevent (Now knows as PHP/Event) extension
 * Libevent pecl extension doesnot work with libevent version 2 hence the pecl team rewrote
 * the libevent extension under a new name pecl-event.
 *
 * @todo Find out why it is not working in non blocking I/O
 * @package php-beyond-the-web
 * @author Raheel <raheelwp@gmail.com>
 */

function techInfo($request)
{
    $request->addHeader('Content-Type', 'text/plain; charset=ISO-8859-1', EventHttpRequest::OUTPUT_HEADER);
    $replyText = '';
    $replyText .= 'Command : ' . $request->getCommand() . "\n";
    $replyText .= 'Host : ' . $request->getHost() . "\n";
    $replyText .= 'Input Headers : ' . var_export($request->getInputHeaders(), true) . "\n";
    $replyText .= 'Output Headers : ' . var_export($request->getOutputHeaders(), true) . "\n";
    $replyText .= 'URI : ' . $request->getUri() . "\n";

    $reply = new EventBuffer();
    $reply->add($replyText);

    $request->sendReply(200, 'OK', $reply);
}

function closeServer($request)
{
    $reply = new EventBuffer();
    $reply->add('Ok, I am shutting down...');
    $request->sendReply(200, 'OK', $reply);

    global $eventBase;
    $eventBase->exit();
}

function notFound($request)
{
    $request->sendError(404, 'Resource not found...');
}

function cat($request)
{
    $request->addHeader('Content-Type', 'image/jpeg', EventHttpRequest::OUTPUT_HEADER);
    
    $reply = new EventBuffer();
    $reply->add(file_get_contents('cat.jpg'));

    $request->sendReply(200, 'OK', $reply);
}

function genericHandler($request)
{
    $replyText = '';
    $replyText = '<html><head><title>'.$request->getUri().'</title></head>';
    $replyText .= '<body><h1>Picture of cat</h1><br>';
    $replyText .= '<img src="/images/cat.jpg">';
    $replyText .= '</body></html>';

    $reply = new EventBuffer;
    $reply->add($replyText);
    
    $request->sendReply(200, "OK", $reply);
}

$eventBase = new EventBase();
$http = new EventHttp($eventBase);
$http->setAllowedMethods(EventHttpRequest::CMD_GET | EventHttpRequest::CMD_POST);

$http->setCallback('/info', 'techInfo');
$http->setCallback('/close', 'closeServer');
$http->setCallback('/notfound', 'notFound');
$http->setCallback('/images/cat.jpg', 'cat');
$http->setDefaultCallback('genericHandler');

$http->bind('0.0.0.0', 8080);

$eventBase->loop();
