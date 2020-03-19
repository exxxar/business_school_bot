<?php

use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Support\Facades\Log;

$botman = resolve('botman');

$botman->fallback(function (\BotMan\BotMan\BotMan $bot){

    $queryObject = $bot->getMessage()->getText();

    if (!$queryObject)
        return;

    if (strlen(trim($queryObject))==0)
        return;

    $data =   array(
        'user' =>json_encode($bot->getUser()->getInfo()),
        'bot_url'=>env("MY_BOT_NAME"),
        'query' => $queryObject
    );

    if ( isset($bot->getMessage()->getPayload()["message_id"]))
        array_push($data,[ 'message_id'=>$bot->getMessage()->getPayload()["message_id"]]);

    $postdata = http_build_query($data );

    $opts = array('http' =>
        array(
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );

    $context = stream_context_create($opts);

    $result = file_get_contents('http://skidka-service.ru/api/v1/methods', false, $context);
});
