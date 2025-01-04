<?php
include 'databasecreds.php';

function connectDatabase() {
    global $creds;
    $conn = new mysqli(...$creds);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function findResults($type, ...$args) {
    $conn = connectDatabase();
    
    if ($type == "emoji" && count($args) == 1) {
        $stmt = $conn->prepare("SELECT emoji FROM emojis WHERE item = ?");
        $stmt->bind_param("s", $args[0]);
    } elseif ($type == "result" && count($args) == 2) {
        $stmt = $conn->prepare("SELECT result FROM results WHERE first = ? AND second = ?");
        $stmt->bind_param("ss", $args[0], $args[1]);
    } else {
        $conn->close();
        return false;
    }

    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $result = $type == "emoji" ? "emoji" : "result";
        $stmt->bind_result($output);
        $stmt->fetch();
        $stmt->close();
        $conn->close();
        return $output;
    }

    $stmt->close();
    $conn->close();
    return false;
}

function addRecipe($first, $second, $result) {
    $conn = connectDatabase();
    $stmt = $conn->prepare("INSERT INTO results (first, second, result) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $first, $second, $result);
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return true;
    } else {
        $stmt->close();
        $conn->close();
        return false;
    }
}

function addItem($item, $emoji) {
    $conn = connectDatabase();
    $stmt = $conn->prepare("INSERT INTO emojis (item, emoji) VALUES (?, ?)");
    $stmt->bind_param("ss", $item, $emoji);
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return true;
    } else {
        $stmt->close();
        $conn->close();
        return false;
    }
}

function aliveCheck($item) { // Looks up in emoji database not recipes to enable Zombie elements
    $conn = connectDatabase();
    $stmt = $conn->prepare("SELECT 1 FROM emojis WHERE item = ?");
    $stmt->bind_param("s", $item);
    $stmt->execute();
    $stmt->store_result();

    $exists = $stmt->num_rows > 0;
    $stmt->close();
    $conn->close();
    return $exists;
}

function findRecipe($first, $second, $result) {
    $conn = connectDatabase();
    $stmt = $conn->prepare("SELECT 1 FROM results WHERE first = ? AND second = ? AND result = ?");
    $stmt->bind_param("sss", $first, $second, $result);
    $stmt->execute();
    $stmt->store_result();

    $exists = $stmt->num_rows > 0;
    $stmt->close();
    $conn->close();
    return $exists;
}
?>
