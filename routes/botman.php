<?php

use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Support\Facades\Log;

$botman = resolve('botman');

$botman->fallback(function (\BotMan\BotMan\BotMan $bot){

    $queryObject = $bot->getMessage()->getText();
    $id = $bot->getUser()->getId();

    Log::info(print_r($bot->getUser()->getInfo(),true));

    if (!$queryObject)
        return;

    if (strlen(trim($queryObject))==0)
        return;
    

    $postdata = http_build_query(
        array(
            'user' => \GuzzleHttp\json_encode($bot->getUser()) ,
            'bot_url'=>env("MY_BOT_NAME"),
            'query' => $queryObject
        )
    );

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
