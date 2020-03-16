<?php

$botman = resolve('botman');

$botman->fallback(function ($bot){
    $queryObject = json_decode($bot->getDriver()->getEvent());

    $id = $bot->getUser()->getId();

    $query = $queryObject->query;

    $postdata = http_build_query(
        array(
            'chatId' => $id ,
            'bot_name'=>env("MY_BOT_NAME"),
            'query' => $query
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
