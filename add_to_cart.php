<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'db_connect.php';
// تأكد أن lang_config.php لا يطبع أي نصوص (No Output) لكي لا يفسد رد الـ AJAX
include 'lang_config.php'; 

if (isset($_POST['id'], $_POST['qty'])) {
    
    $id    = (int)$_POST['id'];
    $qty   = (int)$_POST['qty'];
    $size  = isset($_POST['size']) ? trim($_POST['size']) : 'Standard';
    
    // جلب بيانات المنتج بالكامل
    $stmt = $conn->prepare("SELECT name, price_small, price_medium, price_large, category FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if (!$product) { echo "0"; exit; } // المنتج غير موجود
    if ($qty <= 0) { echo "0"; exit; } // كمية غير صالحة

    $price = 0;
    $display_size = 'Standard';

    // 1. تحديد الأقسام التي تدعم المقاسات (S, M, L)
    // ضفت كل الأقسام المحتملة للبيتزا والرول بناءً على صور المنيو بتاعتك
    $has_sizes = in_array($product['category'], ['pizza', 'pizza_veg', 'pizza_meat', 'pizza_chicken', 'pizza_seafood', 'pizza_roll', 'sandwiches']);
    
    if ($has_sizes) {
        $small_variants  = ['صغير', 'S', 'Small', 'Маленький'];
        $medium_variants = ['وسط', 'M', 'Medium', 'Средний'];
        $large_variants  = ['كبير', 'L', 'Large', 'Большой'];

        if (in_array($size, $small_variants)) {
            $price = $product['price_small'];
            $display_size = 'S'; 
        } elseif (in_array($size, $medium_variants)) {
            $price = $product['price_medium'];
            $display_size = 'M';
        } elseif (in_array($size, $large_variants)) {
            $price = $product['price_large'];
            $display_size = 'L';
        } else {
            // لو المقاس مبعوث بشكل غير متوقع، نأخذ السعر المتاح
            $price = ($product['price_small'] > 0) ? $product['price_small'] : $product['price_large'];
            $display_size = 'Standard';
        }
    } else {
        // 2. للأصناف ذات السعر الموحد (كريب، وجبات، باستا، مشروبات)
        // نحاول سحب السعر من أي خانة بها قيمة (الأولوية لـ price_large أو price_small)
        if ($product['price_large'] > 0) $price = $product['price_large'];
        elseif ($product['price_small'] > 0) $price = $product['price_small'];
        elseif ($product['price_medium'] > 0) $price = $product['price_medium'];
        
        $display_size = 'Standard';
    }

    // تأكيد أخير أن السعر ليس صفراً
    if ($price <= 0) { echo "0"; exit; }

    // --- إدارة السلة في الـ Session ---
    if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }

    // مفتاح فريد يجمع بين ID المنتج ومقاسه
    $cart_item_key = $id . "_" . $display_size;

    if (isset($_SESSION['cart'][$cart_item_key])) {
        $_SESSION['cart'][$cart_item_key]['qty'] += $qty;
    } else {
        $_SESSION['cart'][$cart_item_key] = [
            'id'       => $id,
            'name'     => $product['name'], 
            'size'     => $display_size,
            'qty'      => $qty,
            'price'    => (float)$price,
            'category' => $product['category']
        ];
    }

    // حساب إجمالي عدد القطع في السلة لإرجاعه للهيدر (AJAX Response)
    $total_items = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total_items += $item['qty'];
    }
    
    echo $total_items;

} else {
    echo "0";
}
?>