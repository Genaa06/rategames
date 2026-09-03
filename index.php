<?php
include 'config.php';
include 'includes/helpers.php';

// نجيب كل لعبة مع متوسط تقييمها وعدد المراجعات
$sql = "SELECT games.*,
               AVG(reviews.rating) AS avg_rating,
               COUNT(reviews.id) AS review_count
        FROM games
        LEFT JOIN reviews ON games.id = reviews.game_id
        GROUP BY games.id
        ORDER BY games.id DESC";
$result = $conn->query($sql);

// إحصائيات سريعة للواجهة
$stats = $conn->query("SELECT
        (SELECT COUNT(*) FROM games) AS total_games,
        (SELECT COUNT(*) FROM reviews) AS total_reviews,
        (SELECT ROUND(AVG(rating),1) FROM reviews) AS overall_avg
    ")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RateGames - تقييمات الألعاب</title>
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

    <section class="hero">
        <div class="hero-content">
            <h1>قيّم لعبتك المفضلة، واكتشف رأي اللاعبين الحقيقي</h1>
            <p>مجتمع صغير يشارك تجاربه الصادقة مع الألعاب قبل ما تقرر تجربتها.</p>
            <a href="add_game.php" class="btn">➕ أضف لعبة جديدة</a>
        </div>
        <div class="stat-strip">
            <div><strong><?php echo (int) $stats['total_games']; ?></strong><span>لعبة مضافة</span></div>
            <div><strong><?php echo (int) $stats['total_reviews']; ?></strong><span>مراجعة لاعبين</span></div>
            <div><strong><?php echo $stats['overall_avg'] ? $stats['overall_avg'] : '—'; ?></strong><span>متوسط التقييم العام</span></div>
        </div>
    </section>

    <section class="games-section">
        <div class="section-heading">
            <h2>كل الألعاب</h2>
            <p>مرتبة من الأحدث إضافة</p>
        </div>
        <div class="games-grid">
            <?php
            if ($result->num_rows > 0) {
                while ($game = $result->fetch_assoc()) {
                    $avg = $game['avg_rating'] ? round($game['avg_rating'], 1) : 0;
                    $count = $game['review_count'];
                    $grad = coverGradient($game['category']);
                    $icon = htmlspecialchars($game['icon']);

                    echo "
                    <a href='game_details.php?id={$game['id']}' class='game-card'>
                        <div class='game-cover' style='background:{$grad}'>
                            <span>{$icon}</span>
                        </div>
                        <div class='game-body'>
                            <h3>" . htmlspecialchars($game['name']) . "</h3>
                            <p class='category-tag'>" . htmlspecialchars($game['category']) . "</p>
                            " . starsHtml((float)$avg) . "
                            <p class='rating-info'>{$avg} من 5 ({$count} مراجعة)</p>
                        </div>
                    </a>";
                }
            } else {
                echo "<p class='empty-state'>لا توجد ألعاب حالياً. أضف أول لعبة من صفحة الإضافة!</p>";
            }
            ?>
        </div>
    </section>

    <footer>
        <p>جميع الحقوق محفوظة © 2026</p>
    </footer>

</body>
</html>
<?php
$conn->close();
?>
