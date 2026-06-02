<?php
// 1. بدء الجلسة للوصول للسلة
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// 2. مسح أصناف السلة فقط (عشان نحافظ على إعدادات اللغة والستايل)
if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart']);
}

// 3. التوجيه لصفحة المنيو مع رسالة تنبيه سريعة
echo "<script>
    alert('تم تنظيف السلة بنجاح.. يمكنك البدء بإضافة طلبات جديدة.');
    window.location.href = 'menu.php';
</script>";
exit();
?>