<?php

use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Support\Facades\Log;

$botman = resolve('botman');

$botman->fallback(function ($bot){

    Log::info("Test 1");

    $bot->reply("test");

    $bot->loadDriver(TelegramDriver::DRIVER_NAME);

    $queryObject = $bot->getDriver()->getMessages();

    Log::info(print_r($queryObject["message"],true));
    $id = $bot->getUser()->getId();

    $query = $queryObject["message"];

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
