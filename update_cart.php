<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// التأكد من وجود المفتاح والأكشن في الرابط (GET)
if (isset($_GET['key'], $_GET['action']) && isset($_SESSION['cart'])) {
    
    $key    = $_GET['key'];
    $action = $_GET['action'];

    // التأكد إن الصنف موجود فعلاً في السلة
    if (isset($_SESSION['cart'][$key])) {
        
        if ($action == 'increase') {
            // تزويد الكمية بواحد
            $_SESSION['cart'][$key]['qty'] += 1;
        } 
        elseif ($action == 'decrease') {
            // تنقيص الكمية
            if ($_SESSION['cart'][$key]['qty'] > 1) {
                $_SESSION['cart'][$key]['qty'] -= 1;
            } else {
                // لو بقت 1 ونقصها تاني، يتم حذف الصنف تماماً
                unset($_SESSION['cart'][$key]);
            }
        }
    }
}

// التوجيه التلقائي للصفحة اللي جه منها العميل (غالباً cart.php)
$goto = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
header("Location: " . $goto);
exit();
?>