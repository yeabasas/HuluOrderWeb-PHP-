<?php
$telegramApiUrl = "https://api.telegram.org/bot6531167409:AAHtgqjmkqwyUbKIlMGGDbGK5PzSXgSc_to/";

// Chat ID where you want to check if the bot is a member
$chatIdToCheck = "377915937";

// Bot's username (not the token)
$botUsername = "https://t.me/yeabsupportbot";

// Form the URL to check the bot's status in the chat
$url = $telegramApiUrl . "getChatMember?chat_id=" . $chatIdToCheck . "&user_id=@" . $botUsername;

// Make the API request
$response = file_get_contents($url);

// Decode the JSON response
$result = json_decode($response, true);

// Check if the bot is a member (status "member" or "administrator")
if ($result && isset($result['result']['status']) && ($result['result']['status'] == 'member' || $result['result']['status'] == 'administrator')) {
    echo 'Bot is a member of the chat.';
} else {
    echo 'Bot is not a member of the chat.';
}
