<?php

require 'vendor/autoload.php';
require_once 'Currency.php';
require_once 'db.php';

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
        if (strpos($text, ':')) {
            $params = explode(":", $text);
            if (count($params) == 3) {
                $from_currency = strtoupper($params[0]);
                $to_currency = strtoupper($params[1]);
                $amount = $params[2];

                $currencyConverter = new Currency();
                $converted = $currencyConverter->exchange((float)$amount, $from_currency, $to_currency);

                if ($converted !== null) {
                    $responseText = "Konvertatsiya natijasi: $amount $from_currency = $converted $to_currency";

                    // Ma'lumotlar bazasiga yozish
                    global $pdo;
                    $stmt = $pdo->prepare("INSERT INTO conversions (userid, amount, from_currency, to_currency, converted_amount, conversion_time) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$chat_id, $amount, $from_currency, $to_currency, $converted, date('Y-m-d H:i:s')]);
                } else {
                    $responseText = "Valyuta kursini olishda xatolik yuz berdi.";
                }
            } else {
                $responseText = "Noto'g'ri format. To'g'ri format: <from_valyuta>:<to_valyuta>:<miqdor>";
            }
        } else {
            $responseText = "Valyutalarni konvertatsiya qilish uchun <from_valyuta>:<to_valyuta>:<miqdor> formatida yozing.";
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
