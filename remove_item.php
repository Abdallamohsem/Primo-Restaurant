<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// التأكد إن المفتاح مبعوث (زي 5_Large مثلاً)
if (isset($_GET['key'])) {
    $key = $_GET['key'];
    
    // التأكد إن الصنف موجود فعلاً في السلة قبل المسح
    if (isset($_SESSION['cart'][$key])) {
        unset($_SESSION['cart'][$key]); 
    }
}

// حركة ذكية: لو العميل مسح من صفحة cart.php يرجع ليها، لو من مكان تاني يرجع للـ index
$redirect_to = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';

header("Location: " . $redirect_to); 
exit();
?>