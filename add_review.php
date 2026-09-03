<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $game_id = intval($_POST['game_id']);
    $reviewer_name = trim($_POST['reviewer_name']);
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    if ($reviewer_name == "" || $comment == "" || $rating < 1 || $rating > 5) {
        $conn->close();
        header("Location: game_details.php?id={$game_id}&review_error=" . urlencode("الرجاء تعبئة جميع الحقول بشكل صحيح."));
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO reviews (game_id, reviewer_name, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isis", $game_id, $reviewer_name, $rating, $comment);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: game_details.php?id={$game_id}#addReview");
    exit();
}

$conn->close();
header("Location: index.php");
exit();
?>
