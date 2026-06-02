<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'lang_config.php'; 

// قيمة التوصيل
$shipping_cost = (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) ? 30 : 0; 

// مصفوفة الترجمة
$txt_cart = [
    'ar' => [
        'title' => 'مراجعة طلبك', 'item' => 'الصنف', 'size' => 'المقاس', 'qty' => 'الكمية', 
        'total' => 'الإجمالي', 'del' => 'حذف', 'empty' => 'سلتك فاضية حالياً', 
        'delivery' => 'خدمة التوصيل', 'final' => 'الإجمالي النهائي', 'confirm' => 'تأكيد الطلب وحفظ البيانات',
        'fixed_price' => 'سعر ثابت', 'summary' => 'ملخص الحساب', 'back' => 'استكمال التسوق'
    ],
    'en' => [
        'title' => 'Review Your Order', 'item' => 'Item', 'size' => 'Size', 'qty' => 'Quantity', 
        'total' => 'Total', 'del' => 'Remove', 'empty' => 'Your cart is empty', 
        'delivery' => 'Delivery Fee', 'final' => 'Grand Total', 'confirm' => 'Confirm & Save Order',
        'fixed_price' => 'Fixed Price', 'summary' => 'Summary', 'back' => 'Back to Menu'
    ],
    'ru' => [
        'title' => 'Ваш заказ', 'item' => 'Товар', 'size' => 'Размер', 'qty' => 'Кол-во', 
        'total' => 'Итого', 'del' => 'Удалить', 'empty' => 'Ваша корзина пуста', 
        'delivery' => 'Доставка', 'final' => 'Общая сумма', 'confirm' => 'Подтвердить заказ',
        'fixed_price' => 'Цена', 'summary' => 'Итого', 'back' => 'В меню'
    ]
];
$curr_txt = $txt_cart[$_SESSION['lang']];

function translateSize($size, $lang) {
    if ($size == 'Standard' || $size == 'Default') return '';
    $sizes = [
        'S' => ['ar' => 'صغير', 'en' => 'Small', 'ru' => 'Маленький'],
        'M' => ['ar' => 'وسط', 'en' => 'Medium', 'ru' => 'Средний'],
        'L' => ['ar' => 'كبير', 'en' => 'Large', 'ru' => 'Большой']
    ];
    return isset($sizes[$size][$lang]) ? $sizes[$size][$lang] : $size;
}
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>" dir="<?php echo ($_SESSION['lang'] == 'ar') ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $curr_txt['title']; ?> - Primo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap<?php echo ($_SESSION['lang'] == 'ar') ? '.rtl' : ''; ?>.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #fcfcfc; font-family: 'Segoe UI', sans-serif; }
        .table-card { border-radius: 15px; border: none; background: #fff; overflow: hidden; }
        .qty-box { background: #f3f3f3; border-radius: 50px; padding: 4px 12px; display: inline-flex; align-items: center; }
        .qty-btn { color: #cc0000; font-size: 1.2rem; text-decoration: none; }
        .btn-order { background: #cc0000; color: white; border-radius: 50px; font-weight: bold; padding: 15px; border: none; width: 100%; transition: 0.3s; }
        .btn-order:hover { background: #aa0000; transform: translateY(-2px); color: #fff; }
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fa fa-shopping-basket text-danger me-2"></i> <?php echo $curr_txt['title']; ?></h2>
        <a href="index.php" class="btn btn-outline-dark btn-sm rounded-pill px-3"><i class="fa fa-arrow-left me-1"></i> <?php echo $curr_txt['back']; ?></a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card table-card shadow-sm">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-center">
                                <th class="text-start ps-4"><?php echo $curr_txt['item']; ?></th>
                                <th><?php echo $curr_txt['size']; ?></th>
                                <th><?php echo $curr_txt['qty']; ?></th>
                                <th><?php echo $curr_txt['total']; ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $subtotal_all = 0;
                            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])):
                                foreach ($_SESSION['cart'] as $key => $item):
                                    $line_total = $item['price'] * $item['qty'];
                                    $subtotal_all += $line_total;
                            ?>
                            <tr class="text-center">
                                <td class="text-start ps-4">
                                    <span class="fw-bold d-block"><?php echo $item['name']; ?></span>
                                    <small class="text-muted"><?php echo $item['price']; ?> EGP</small>
                                </td>
                                <td>
                                    <?php if($item['size'] != 'Standard'): ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">
                                            <?php echo translateSize($item['size'], $_SESSION['lang']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small"><?php echo $curr_txt['fixed_price']; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="qty-box">
                                        <a href="update_qty.php?key=<?php echo $key; ?>&action=decrease" class="qty-btn"><i class="fa fa-minus-circle"></i></a>
                                        <span class="fw-bold mx-3"><?php echo $item['qty']; ?></span>
                                        <a href="update_qty.php?key=<?php echo $key; ?>&action=increase" class="qty-btn"><i class="fa fa-plus-circle"></i></a>
                                    </div>
                                </td>
                                <td class="fw-bold"><?php echo $line_total; ?> EGP</td>
                                <td>
                                    <a href="remove_item.php?key=<?php echo $key; ?>" class="text-danger opacity-50"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fa fa-shopping-cart fa-3x text-light mb-3"></i>
                                    <p class="text-muted"><?php echo $curr_txt['empty']; ?></p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <form id="orderForm" action="process_order.php" method="POST">
                <div class="card shadow-sm p-4 border-0" style="border-radius: 15px;">
                    <h5 class="fw-bold mb-4 border-bottom pb-2"><?php echo $curr_txt['summary']; ?></h5>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">اسم العميل</label>
                        <input type="text" name="customer_name" class="form-control rounded-3 border-0 bg-light" placeholder="اكتب اسمك هنا" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">رقم الهاتف</label>
                        <input type="tel" name="phone" class="form-control rounded-3 border-0 bg-light" placeholder="01xxxxxxxxx" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">العنوان بالتفصيل</label>
                        <textarea name="address" class="form-control rounded-3 border-0 bg-light" rows="2" placeholder="الشارع، الدور، العلامة المميزة" required></textarea>
                    </div>

                    <input type="hidden" name="total_price" value="<?php echo ($subtotal_all > 0) ? ($subtotal_all + $shipping_cost) : 0; ?>">

                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>المجموع:</span>
                        <span><?php echo $subtotal_all; ?> EGP</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <span><?php echo $curr_txt['delivery']; ?>:</span>
                        <span class="text-success fw-bold">+ <?php echo $shipping_cost; ?> EGP</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-5"><?php echo $curr_txt['final']; ?>:</span>
                        <span class="text-danger fw-bold fs-4"><?php echo ($subtotal_all > 0) ? ($subtotal_all + $shipping_cost) : 0; ?> EGP</span>
                    </div>

                    <button type="button" class="btn btn-order shadow" onclick="validateAndSubmit()">
                        <?php echo $curr_txt['confirm']; ?> <i class="fa fa-check-circle ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function validateAndSubmit() {
    const subtotal = <?php echo $subtotal_all; ?>;
    if (subtotal === 0) {
        Swal.fire({ icon: 'error', title: 'عفواً!', text: 'سلتك فاضية، ضيف أكلك المفضل الأول' });
        return;
    }
    
    // التحقق من الحقول
    const name = document.getElementsByName('customer_name')[0].value.trim();
    const phone = document.getElementsByName('phone')[0].value.trim();
    const addr = document.getElementsByName('address')[0].value.trim();
    
    if(!name || !phone || !addr) {
        Swal.fire({ icon: 'warning', title: 'بيانات ناقصة', text: 'من فضلك كمل بيانات التوصيل عشان الأكل ميتأخرش عليك' });
        return;
    }

    // إظهار لودر شيك قبل الإرسال
    Swal.fire({
        title: 'جاري تسجيل طلبك...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    document.getElementById('orderForm').submit();
}
</script>
</body>
</html>