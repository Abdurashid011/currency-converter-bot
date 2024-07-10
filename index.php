<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use GuzzleHttp\Client;

$token = "7237515629:AAH48kseCAE8AX2Ia4HU8jiE2kTV6iw3-gg";
$tgApi = "https://api.telegram.org/bot$token/";

$client = new Client(['base_uri' => $tgApi]);

$update = json_decode(file_get_contents('php://input'), true);

if (isset($update)) {
    if (isset($update['message'])) {
        $message = $update['message'];
        $chat_id = $message['chat']['id'];
        $type = $message['chat']['type'];
        $miid = $message['message_id'];
        $name = $message['from']['first_name'];
        $user = $message['from']['username'] ?? '';
        $fromid = $message['from']['id'];
        $text = $message['text'];
        $title = $message['chat']['title'];
        $chatuser = $message['chat']['username'] ?? "Shaxsiy Guruh!";
        $caption = $message['caption'] ?? '';
        $entities = $message['entities'][0] ?? '';
        $left_chat_member = $message['left_chat_member'] ?? '';
        $new_chat_member = $message['new_chat_member'] ?? '';
        $photo = $message['photo'] ?? '';
        $video = $message['video'] ?? '';
        $audio = $message['audio'] ?? '';
        $voice = $message['voice'] ?? '';
        $reply = $message['reply_markup'] ?? '';
        $fchat_id = $message['forward_from_chat']['id'] ?? '';
        $fid = $message['forward_from_message_id'] ?? '';
    }
}

$client->post('sendMessage', [
    'form_params' => [
        'chat_id' => $chat_id ?? '',
        'text' => $text ?? 'Please send only text'
    ]
]);
