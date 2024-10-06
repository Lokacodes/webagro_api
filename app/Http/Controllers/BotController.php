<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BotController extends Controller
{
    public static $botToken = "7395349985:AAFrkiH7q7ECqA-95j9klbIeMf8C65TXneI";

    public function callback(Request $request)
    {
        $message = $request->input('message');
        $from = @$message['from'];
        $command = @$message['text'];

        if ($command != '/myinfo') return;

        $message = "Halo {$from['first_name']}, berikut data profilmu :\n\n";
        $message .= json_encode($from, JSON_PRETTY_PRINT);

        $res = $this->send($from['id'], $message);
    }

    public static function send($userId, $message)
    {
        $token = self::$botToken;
        $data = [
            "chat_id" => $userId,
            "text" => $message,
            "parse_mode" => "html"
        ];

        // CURL SEND
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot{$token}/sendMessage");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
