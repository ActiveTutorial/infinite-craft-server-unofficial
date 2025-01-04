<?php
function getPrompt(...$args) {
    switch ($args[0]) {
        case "normal": // Most confirmed prompt, see IC discord, propably the real one
            return <<<EOT
            Fire + Water = Steam
            Human + Robe = Judge
            Earth + Water = Plant
            Cow + Fire = Steak
            King + Ocean = Poseidon
            {$args[1]} + {$args[2]} =
            EOT;
        case "self": // Only Boulder is confiremed but the first 2 are from Adam and I think it's real.
            return <<<EOT
            Wind + Wind = Tornado
            Water + Water = Lake
            Earth + Earth = Mountain
            Rock + Rock = Boulder
            {$args[1]} + {$args[2]} =
            EOT;
        case "emoji": // This one is entirely made up neal doesnt use together.ai for emojis, see IC discord for proof.
            return <<<EOT
            Hat = 🎩
            Key = 🔑
            Bicycle = 🚲
            Spicy = 🌶️
            Clean = 🧼
            Glasses = 🕶️
            Eat = 🍽️
            {$args[1]} =
            EOT;
        default:
            return null;
    }
}
?>
