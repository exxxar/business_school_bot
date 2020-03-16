<?php

use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Support\Facades\Log;

$botman = resolve('botman');

$botman->fallback(function ($bot){

    $queryObject = $bot->getMessage()->getText();
    $id = $bot->getUser()->getId();

    if (is_null($queryObject))
        return;

    Log::info($queryObject);

    $postdata = http_build_query(
        array(
            'chatId' => $id ,
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
