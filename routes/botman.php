<?php

use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Support\Facades\Log;

$botman = resolve('botman');

$botman->fallback(function ($bot){

    Log::info("Test 1");
    $this->bot->loadDriver(TelegramDriver::DRIVER_NAME);

    $queryObject = json_decode($bot->getDriver()->getEvent());

    $id = $bot->getUser()->getId();

    $query = $queryObject->query;

    $postdata = http_build_query(
        array(
            'chatId' => $id ,
            'bot_url'=>env("MY_BOT_NAME"),
            'query' => $query
        )
    );

    Log::info("Test 2");

    $opts = array('http' =>
        array(
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );

    $context = stream_context_create($opts);

    Log::info("Test 3");

    $result = file_get_contents('http://skidka-service.ru/api/v1/methods', false, $context);
});
