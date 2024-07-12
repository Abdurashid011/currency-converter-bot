<?php

require 'vendor/autoload.php';
require_once 'Currency.php';
require_once 'db.php';

use GuzzleHttp\Client;

$token = "7237515629:AAH48kseCAE8AX2Ia4HU8jiE2kTV6iw3-gg";
$tgApi = "https://api.telegram.org/bot$token/";

$client = new Client(['base_uri' => $tgApi]);

$update = json_decode(file_get_contents('php://input'));

if (isset($update->message)) {
    $message = $update->message;
    $chat_id = $message->chat->id;
    $text = $message->text;

    if (!empty($text)) {
        if (strpos($text, ':')) {
            $params = explode(":", $text);
            if (count($params) == 3) {
                $from_currency = strtoupper($params[0]);
                $to_currency = strtoupper($params[1]);
                $amount = (float)$params[2];

                $currencyConverter = new Currency();
                $converted = $currencyConverter->exchange($amount, $from_currency, $to_currency);

                if ($converted !== null) {
                    $responseText = "Conversion result: $amount $from_currency = $converted $to_currency";

                    $pdo = db::connect();
                    $stmt = $pdo->prepare("INSERT INTO conversions(
                                        user_id, amount, from_currency, to_currency, converted_amount, conversion_time)
                                        VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$chat_id, $amount, $from_currency, $to_currency, $converted, date('Y-m-d H:i:s')]);
                } else {
                    $responseText = "Error occurred while fetching exchange rate.";
                }
            } else {
                $responseText = "Incorrect format. Correct format: <from_currency>:<to_currency>:<amount>";
            }
        } else {
            $responseText = "To convert currencies, use the format <from_currency>:<to_currency>:<amount>.";
        }

        // Send the response
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => $responseText
            ]
        ]);
    } else {
        error_log("Message text is empty.");
    }
} else {
    error_log("Update or message is not present.");
}
