<?php
include 'apikey.php';

function requestLLM($prompt) { // Makes API request to together.ai with given .
    global $apikey;
    $apiUrl = 'https://api.together.xyz/v1/completions';
    $data = json_encode([
        'model' => 'meta-llama/Llama-2-70b-hf',
        'prompt' => $prompt,
        'max_tokens' => 20, // Increase if you want to try prompt breakers.
        'temperature' => 0,
        'top_p' => 0,
        'top_k' => 100,
        'repetition_penalty' => 1,
        'stop' => ["\n"] // Same here, you can replace it with "</s>".
    ]);

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer {$apikey}",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        return "Nothing"; // Nothing on error
    } else {
        $responseData = json_decode($response, true);
        if (isset($responseData['choices'][0]['text'])) {
            return trim($responseData['choices'][0]['text']);
        } else {
            return "Nothing"; // Nothing on nothing
        }
    }

    curl_close($ch);
}
?>
