<?php
// ==========================================
// ملف الاتصال بقاعدة البيانات
// ==========================================

$host = "localhost";
$username = "root";      // اسم المستخدم الافتراضي في XAMPP
$password = "";          // كلمة المرور الافتراضية فارغة في XAMPP
$database = "reviews_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
