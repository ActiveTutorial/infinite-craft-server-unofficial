<?php
include '../../private/api.php';
include '../../private/databaseinteract.php';
include '../../private/prompts.php';

$allowedOrigins = ['http://localhost:3000', 'https://neal.fun']; // Change or update if you need to.
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

if (isset($_GET['ref']) && $_GET['ref'] === 'app') {
    header("Access-Control-Allow-Origin: *");
} elseif (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
} else {
    header('Content-Type: text/plain');
    echo "Not allowed";
    http_response_code(403);
    exit;
}

header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

function nealCase($input) {
    $input = strtolower($input);
    $words = explode(' ', $input);
    $words = array_map('ucfirst', $words);
    return implode(' ', $words);
}

function newItem($first, $second) { // request and add new item
    if ($first == $second) {
        $result = requestLLM(getPrompt("self", $first, $second));
    } else {
        $result = requestLLM(getPrompt("normal", $first, $second));
    }
    $alive = aliveCheck($result);
    $explodedResult = explode('=', $result);
    $result = trim(array_pop($explodedResult));    
    return ['value' => $result, 'isNew' => !$alive];
    addRecipe($first, $second, $result);
}

function newEmoji($item) { // request and add new item (also does for zombie elements, idk how else to logically implement this)
    $newEmoji = requestLLM(getPrompt("emoji", $item));
    addItem($item, $newEmoji);
    return $newEmoji;
}

function getCraftResponse(...$args) { // haha thats an infinite craft reference... get it? 
    switch ($args[0]) {
        case "result":
            $apiresult = findResults("result", $args[1], $args[2]); // check existence of result
            if (!(aliveCheck($args[1]) && aliveCheck($args[2]) && strlen($args[1]) <= 30 && strlen($args[2]) <= 30)) {
                // dead check
                return ['value' => "Nothing", 'isNew' => false]; // Nothing if dead
            }
            if ($apiresult == false) { // if it doesn't exist make it and award fd               
                    $apiresult = newItem($args[1], $args[2]);
                    $isNew = $apiresult['isNew'];
                    $apiresult = $apiresult['value'];
            } else {
                $isNew = false;
            }
            if (empty($apiresult)) { // Nothing on unexpected thing
                return ['value' => "Nothing", 'isNew' => false];
            }
            return ['value' => $apiresult, 'isNew' => $isNew];
        case "emoji":
            if ($args[1] == "Nothing") {
                return ['value' => ""];
            }
            $emoji = findResults("emoji", $args[1]);
            if ($emoji == false) {
                $emoji = newEmoji($args[1]);
            }
            return ['value' => $emoji];
        default:
            return null;
    }
}

if (isset($_GET['first']) && isset($_GET['second'])) {
    $first = nealCase(trim($_GET['first'])); // Neal case it
    $second = nealCase(trim($_GET['second']));
    list($first, $second) = ($first < $second) ? [$first, $second] : [$second, $first]; // sort alsphabetically
    $apiresult = getCraftResponse("result", $first, $second);
    $emoji = getCraftResponse("emoji", $apiresult['value']);
    echo json_encode([
        'result' => $apiresult['value'] ?: "Nothing",
        'emoji' => $emoji['value'] ?: "",
        'isNew' => $apiresult['isNew']
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['result' => 'Nothing', 'emoji' => '', 'isNew' => false]);
}
?>
