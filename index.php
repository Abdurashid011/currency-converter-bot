<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;

$token = "7237515629:AAH48kseCAE8AX2Ia4HU8jiE2kTV6iw3-gg";
$tgApi = "https://api.telegram.org/bot$token/";

$client = new Client(['base_uri' => $tgApi]);

$update = json_decode(file_get_contents('php://input'));

if (isset($update) && isset($update->message)) {
    $message = $update->message;
    $chat_id = $message->chat->id;
    $text = $message->text;

    if (!empty($text)) {
        // Valyuta konvertatsiyasi uchun
        if (strpos($text, "/convert") === 0) {
            $params = explode(" ", $text);
            if (count($params) == 4) {
                $amount = $params[1];
                $from_currency = strtoupper($params[2]);
                $to_currency = strtoupper($params[3]);

                require_once "Currency.php";

                $currencyConverter = new Currency();
                $converted = $currencyConverter->exchange((float)$amount, $from_currency, $to_currency);

                if ($converted !== null) {
                    $responseText = "Konvertatsiya natijasi: $amount $from_currency = $converted $to_currency";
                } else {
                    $responseText = "Valyuta kursini olishda xatolik yuz berdi.";
                }
            } else {
                $responseText = "Noto'g'ri format. To'g'ri format: /convert <miqdor> <from_valyuta> <to_valyuta>";
            }
        } else {
            $responseText = "Salom! Men valyuta konvertatsiyasi qilish uchun mo'ljallanganman. /convert buyrug'ini ishlatib valyutalarni konvertatsiya qiling.";
        }

        // Javobni yuborish
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => $responseText
            ]
        ]);
    } else {
        error_log("Xabar matni bo'sh.");
    }
} else {
    error_log("Update yoki xabar mavjud emas.");
}
