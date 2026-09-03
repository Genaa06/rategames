<?php
include 'config.php';

$successMessage = "";
$errorMessage = "";
$nameVal = $categoryVal = $descVal = "";
$iconVal = "🎮";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $icon = trim($_POST['icon']);

    $nameVal = $name;
    $categoryVal = $category;
    $descVal = $description;
    if ($icon !== "") { $iconVal = $icon; }

    if ($name == "" || $category == "" || $description == "") {
        $errorMessage = "الرجاء تعبئة جميع الحقول المطلوبة.";
    } else {
        if ($icon == "") {
            $icon = "🎮";
        }

        $stmt = $conn->prepare("INSERT INTO games (name, icon, category, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $icon, $category, $description);

        if ($stmt->execute()) {
            $successMessage = "تمت إضافة اللعبة بنجاح! ✅";
            $nameVal = $categoryVal = $descVal = "";
            $iconVal = "🎮";
        } else {
            $errorMessage = "حدث خطأ أثناء الإضافة: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة لعبة - RateGames</title>
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

    <section class="form-section">
        <h2>➕ إضافة لعبة جديدة</h2>

        <?php if ($successMessage != "") { ?>
            <p class="msg success"><?php echo $successMessage; ?></p>
        <?php } ?>

        <?php if ($errorMessage != "") { ?>
            <p class="msg error"><?php echo $errorMessage; ?></p>
        <?php } ?>

        <form method="POST" class="game-form" id="gameForm">
            <label for="name">اسم اللعبة</label>
            <input type="text" id="name" name="name" placeholder="مثال: Minecraft" value="<?php echo htmlspecialchars($nameVal); ?>" required>

            <label for="category">التصنيف</label>
            <input list="categoryList" id="category" name="category" placeholder="مثال: أكشن" value="<?php echo htmlspecialchars($categoryVal); ?>" required>
            <datalist id="categoryList" >
                <option value="أكشن">
                <option value="رياضة">
                <option value="استراتيجية">
                <option value="رعب">
                <option value="سباق">
                <option value="ألغاز">
                <option value="مغامرات">
                <option value="محاكاة">
                <option value="قتال">
</datalist>

            <label>إيموجي مميز (اختياري)</label>
            <input type="hidden" id="icon" name="icon" value="<?php echo htmlspecialchars($iconVal); ?>">
            <div class="icon-picker" id="iconPicker">
                <?php foreach (['🎮','⚔️','⚽','♟️','🧟','🏎️','🧩','🗺️','🏡','🥊','⛏️','💀','🌾'] as $emoji) { ?>
                    <button type="button" data-icon="<?php echo $emoji; ?>"><?php echo $emoji; ?></button>
                <?php } ?>
            </div>

            <label for="description">الوصف</label>
            <textarea id="description" name="description" rows="4" placeholder="وصف مختصر عن اللعبة" required><?php echo htmlspecialchars($descVal); ?></textarea>

            <button type="submit" class="btn">إضافة اللعبة</button>
        </form>

        <a class="back-link" href="index.php">⟵ رجوع للصفحة الرئيسية</a>
    </section>

    <footer>
        <p> جميع الحقوق محفوظة © 2026</p>
    </footer>

    <script>
        const iconInput = document.getElementById('icon');
        const buttons = document.querySelectorAll('#iconPicker button');
        buttons.forEach(btn => {
            if (btn.dataset.icon === iconInput.value) btn.classList.add('active');
            btn.addEventListener('click', () => {
                buttons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                iconInput.value = btn.dataset.icon;
            });
        });
    </script>

</body>
</html>
<?php
$conn->close();
?>
