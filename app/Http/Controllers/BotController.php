<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;

class BotController extends Controller
{
    //

    public function getWebhookUpdates()
    {
        $telegram = new Api(env("TELEGRAM_BOT_TOKEN"));
        $response = $telegram->getMe();

        $botId = $response->getId();
        $firstName = $response->getFirstName();
        $username = $response->getUsername();

        $update = $telegram->getWebhookUpdate();

        if (!isset($update->channel_post))
            Log::info(print_r($update, true));

    }
    /*    public function fallback($bot)
        {
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

        }*/
}
