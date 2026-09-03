<?php
include 'config.php';
include 'includes/helpers.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM games WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$game = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$game) {
    header("Location: index.php");
    exit();
}

$statStmt = $conn->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) AS review_count FROM reviews WHERE game_id = ?");
$statStmt->bind_param("i", $id);
$statStmt->execute();
$stats = $statStmt->get_result()->fetch_assoc();
$statStmt->close();
$avg = $stats['avg_rating'] ? round($stats['avg_rating'], 1) : 0;
$count = $stats['review_count'];

$reviewsStmt = $conn->prepare("SELECT * FROM reviews WHERE game_id = ? ORDER BY created_at DESC");
$reviewsStmt->bind_param("i", $id);
$reviewsStmt->execute();
$reviews = $reviewsStmt->get_result();

$grad = coverGradient($game['category']);
$reviewError = isset($_GET['review_error']) ? $_GET['review_error'] : "";
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($game['name']); ?> - RateGames</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="navbar">
        <div class="logo">⭐ Rate<span>Games</span></div>
        <nav>
            <ul>
                <li><a href="index.php">الرئيسية</a></li>
                <li><a href="add_game.php">إضافة لعبة</a></li>
            </ul>
        </nav>
    </header>

    <section class="game-header">
        <div class="game-header-bg" style="background:<?php echo $grad; ?>"></div>
        <div class="game-header-content">
            <div class="game-icon-big" style="background:<?php echo $grad; ?>"><?php echo htmlspecialchars($game['icon']); ?></div>
            <h1><?php echo htmlspecialchars($game['name']); ?></h1>
            <p class="category-tag"><?php echo htmlspecialchars($game['category']); ?></p>
            <?php echo starsHtml((float)$avg, 'big'); ?>
            <p class="rating-info"><?php echo $avg; ?> من 5 (<?php echo $count; ?> مراجعة)</p>
            <p class="game-desc"><?php echo htmlspecialchars($game['description']); ?></p>
            <div class="header-actions">
                <a href="#addReview" class="btn">✍️ أضف مراجعتك</a>
                <a href="delete_game.php?id=<?php echo $game['id']; ?>"
                   class="btn danger"
                   onclick="return confirm('متأكد إنك تبي تحذف هذي اللعبة وكل مراجعاتها؟');">🗑️ حذف اللعبة</a>
            </div>
        </div>
    </section>

    <section class="reviews-section" style="margin-top: 40px;">
        <h2>المراجعات (<?php echo $count; ?>)</h2>
        <div class="reviews-list">
            <?php if ($reviews->num_rows > 0) { ?>
                <?php while ($r = $reviews->fetch_assoc()) { ?>
                    <div class="review-card">
                        <div class="review-top">
                            <span class="reviewer-name"><?php echo htmlspecialchars($r['reviewer_name']); ?></span>
                            <?php echo starsHtml((float)$r['rating']); ?>
                        </div>
                        <p class="review-comment"><?php echo htmlspecialchars($r['comment']); ?></p>
                        <p class="review-date"><?php echo date('Y/m/d', strtotime($r['created_at'])); ?></p>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p class="empty-state">لا توجد مراجعات بعد. كن أول من يشارك رأيه!</p>
            <?php } ?>
        </div>
    </section>

    <section class="review-form-box" id="addReview">
        <h2 style="text-align:center; font-size:20px; margin-bottom:16px;">✍️ أضف مراجعتك</h2>

        <?php if ($reviewError != "") { ?>
            <p class="msg error"><?php echo htmlspecialchars($reviewError); ?></p>
        <?php } ?>

        <form method="POST" action="add_review.php" class="game-form">
            <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">

            <label for="reviewer_name">اسمك</label>
            <input type="text" id="reviewer_name" name="reviewer_name" placeholder="مثال: سلطان" required>

            <label for="rating">تقييمك</label>
            <select id="rating" name="rating" required>
                <option value="5">⭐⭐⭐⭐⭐ ممتازة</option>
                <option value="4">⭐⭐⭐⭐ جيدة جداً</option>
                <option value="3">⭐⭐⭐ جيدة</option>
                <option value="2">⭐⭐ متوسطة</option>
                <option value="1">⭐ ضعيفة</option>
            </select>

            <label for="comment">تعليقك</label>
            <textarea id="comment" name="comment" rows="4" placeholder="شاركنا رأيك عن اللعبة" required></textarea>

            <button type="submit" class="btn">نشر المراجعة</button>
        </form>

        <a class="back-link" href="index.php">⟵ رجوع للصفحة الرئيسية</a>
    </section>

    <footer>
        <p>جميع الحقوق محفوظة © 2026</p>
    </footer>

</body>
</html>
<?php
$conn->close();
?>
