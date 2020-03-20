<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;

class BotController extends Controller
{
    //

    public function getWebhookUpdates(Request $request)
    {
        if (!$request->session()->has("robo_user")){

            $postdata = http_build_query(
                array(
                    'email' => "admin@gmail.com",
                    'password' => "adminsecret",
                    'remember_me' => 1
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

            $token = json_decode(file_get_contents('http://skidka-service.ru/api/v1/auth/login', false, $context))->access_token;

            $request->session()->push("robo_user",json_encode([
                "access_token"=>$token
            ]));

            Log::info($token);
        }
        $telegram = new Api(env("TELEGRAM_BOT_TOKEN"));
        $response = $telegram->getMe();

        $update = json_decode($telegram->getWebhookUpdate());

        if (isset($update->channel_post))
            return;

        Log::info($update->message->message_id);
        Log::info($response->getId());
        Log::info($update->message->text);

        $postdata = http_build_query(
            array(
                'message_id' => $update->message->message_id,
                'user' => json_encode([
                    "id" => $update->message->id,
                    "first_name" => $response->getFirstName(),
                    "username" => $response->getUsername()
                ]),
                'bot_name' => env("MY_BOT_NAME"),
                'query' => $update->message->text
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
    }

}
