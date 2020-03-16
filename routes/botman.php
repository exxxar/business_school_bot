<?php

use App\Http\Controllers\BotController;

$botman = resolve('botman');

$botman->fallback(BotController::class . "@fallback");
