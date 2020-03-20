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
        /*if (!$request->session()->has("robo_user")){

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
        }*/
        $telegram = new Api(env("TELEGRAM_BOT_TOKEN"));
        $response = $telegram->getMe();

        $update = json_decode($telegram->getWebhookUpdate());

        if (isset($update->channel_post))
            return;

        Log::info(print_r($update, true));

        $postdata = http_build_query(
            array(
                'message_id' => $update->message->message_id??$update->callback_query->message->message_id,
                'user' => json_encode([
                    "id" => $update->message->from->id ?? $update->callback_query->from->id,
                    "first_name" => $update->message->from->first_name ?? $update->callback_query->from->first_name,
                    "last_name" => $update->message->from->last_name ?? $update->callback_query->from->last_name,
                    "username" => $update->message->from->username ?? $update->callback_query->from->username,
                ]),
                'bot_name' => env("MY_BOT_NAME"),
                'query' => $update->message->text??$update->callback_query->data
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

        try {
            $result = file_get_contents('http://skidka-service.ru/api/v1/methods', false, $context);
        }
        catch (\Exception $e){

        }
    }

}
