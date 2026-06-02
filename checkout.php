<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'lang_config.php';

// 1. منع الدخول للصفحة لو السلة فاضية
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: menu.php");
    exit();
}

// 2. إعداد النصوص حسب اللغة
$txt_checkout = [
    'ar' => ['title' => 'إتمام الطلب', 'confirm_msg' => 'راجع تفاصيل طلبك الأخيرة قبل التأكيد', 'final_total' => 'الإجمالي النهائي المطلوب', 'back' => 'تعديل السلة', 'finish' => 'تأكيد وإرسال عبر واتساب'],
    'en' => ['title' => 'Checkout', 'confirm_msg' => 'Review your final details before confirming', 'final_total' => 'Final Amount Due', 'back' => 'Edit Cart', 'finish' => 'Confirm & Send to WhatsApp'],
    'ru' => ['title' => 'Оформление', 'confirm_msg' => 'Проверьте детали заказа перед подтверждением', 'final_total' => 'Итого к оплате', 'back' => 'В корзину', 'finish' => 'Подтвердить заказ']
];
$curr = $txt_checkout[$_SESSION['lang']];

// حساب الإجمالي
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += ($item['price'] * $item['qty']);
}
$shipping = 30; // خدمة التوصيل الثابتة
$grand_total = $subtotal + $shipping;
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>" dir="<?php echo ($_SESSION['lang'] == 'ar') ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $curr['title']; ?> - Primo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.<?php echo ($_SESSION['lang'] == 'ar' ? 'rtl.' : ''); ?>min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #fdfdfd; font-family: 'Segoe UI', sans-serif; }
        .checkout-box { max-width: 600px; margin: 50px auto; background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); padding: 30px; }
        .item-row { border-bottom: 1px dashed #eee; padding: 10px 0; }
        .total-banner { background: #fff5f5; border-radius: 12px; padding: 15px; border-left: 5px solid #cc0000; }
        .btn-confirm { background: #25d366; color: white; border-radius: 50px; padding: 15px; font-weight: bold; border: none; width: 100%; font-size: 1.1rem; transition: 0.3s; }
        .btn-confirm:hover { background: #128c7e; transform: translateY(-2px); color: #fff; }
    </style>
</head>
<body>

<div class="container">
    <div class="checkout-box">
        <div class="text-center mb-4">
            <div class="display-6 text-danger mb-2"><i class="fa fa-clipboard-check"></i></div>
            <h3 class="fw-bold"><?php echo $curr['title']; ?></h3>
            <p class="text-muted small"><?php echo $curr['confirm_msg']; ?></p>
        </div>

        <div class="mb-4">
            <?php foreach ($_SESSION['cart'] as $item): ?>
            <div class="d-flex justify-content-between item-row">
                <span>
                    <span class="fw-bold"><?php echo $item['qty']; ?>x</span> 
                    <?php echo $item['name']; ?> 
                    <?php if($item['size'] != 'Standard'): ?>
                        <small class="badge bg-light text-dark"><?php echo $item['size']; ?></small>
                    <?php endif; ?>
                </span>
                <span class="text-dark"><?php echo ($item['price'] * $item['qty']); ?> EGP</span>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="total-banner mb-4">
            <div class="d-flex justify-content-between mb-1">
                <span>المجموع الفرعي:</span>
                <span><?php echo $subtotal; ?> EGP</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>خدمة التوصيل:</span>
                <span class="text-success">+ <?php echo $shipping; ?> EGP</span>
            </div>
            <div class="d-flex justify-content-between border-top pt-2">
                <span class="fw-bold fs-5"><?php echo $curr['final_total']; ?>:</span>
                <span class="text-danger fw-bold fs-4"><?php echo $grand_total; ?> EGP</span>
            </div>
        </div>

        <div class="row g-2">
            <div class="col-md-8">
                <form action="process_order.php" method="POST">
                    <button type="submit" class="btn btn-confirm shadow-sm">
                        <i class="fab fa-whatsapp me-2"></i> <?php echo $curr['finish']; ?>
                    </button>
                </form>
            </div>
            <div class="col-md-4">
                <a href="cart.php" class="btn btn-outline-secondary w-100 rounded-pill py-3">
                    <?php echo $curr['back']; ?>
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>