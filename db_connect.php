<?php
// إعدادات قاعدة البيانات
$host     = "localhost";
$username = "root";
$password = ""; 
$dbname   = "primo_db";

// 1. إنشاء الاتصال باستخدام MySQLi
$conn = new mysqli($host, $username, $password, $dbname);

// 2. التحقق من نجاح الاتصال
if ($conn->connect_error) {
    // إظهار رسالة خطأ واضحة في حالة الفشل
    die("❌ فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// 3. ضبط الترميز ليدعم العربية والروسية (utf8mb4)
$conn->set_charset("utf8mb4");

// 4. ضبط المنطقة الزمنية (Timezone) 
// مهم جداً عشان توقيت الطلبات في صفحة الـ Admin يظهر صح بتوقيت مصر
date_default_timezone_set('Africa/Cairo');

// 5. وظيفة اختيارية (Global Helper) لتنظيف البيانات
// دي هتساعدك جداً في ملفات الـ Admin لمنع هجمات SQL Injection
function clean($data, $conn) {
    return $conn->real_escape_string(strip_tags(trim($data)));
}
?>