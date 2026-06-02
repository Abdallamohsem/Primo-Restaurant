<?php
// lang_config.php المطور والشامل لكل صفحات السيستم
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// تحديد اللغة الافتراضية
if (!isset($_SESSION['lang'])) { $_SESSION['lang'] = 'ar'; }

// تغيير اللغة عند الطلب
if (isset($_GET['lang'])) {
    $allowed = ['ar', 'en', 'ru'];
    if (in_array($_GET['lang'], $allowed)) { 
        $_SESSION['lang'] = $_GET['lang']; 
        // إعادة توجيه لنفس الصفحة بدون الـ GET عشان الرابط يفضل نظيف
        header("Location: " . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    }
}

$languages = [
    'ar' => [
        // الهيدر والمنيو
        'home' => 'الرئيسية',
        'menu' => 'المنيو',
        'contact' => 'اتصل بنا',
        'cart' => 'سلة الطلبات',
        // السلة والطلب
        'qty' => 'الكمية',
        'price' => 'السعر',
        'total' => 'الإجمالي',
        'checkout' => 'إتمام الطلب',
        'empty_cart' => 'السلة فارغة!',
        'add_to_cart' => 'إضافة للسلة',
        // بيانات العميل
        'name_label' => 'الاسم بالكامل:',
        'phone_label' => 'رقم الهاتف:',
        'address_label' => 'العنوان بالتفصيل:',
        'send_order' => 'تأكيد الطلب عبر واتساب',
        // لوحة التحكم (الجديد اللي إنت ضفته)
        'add_new' => 'إضافة صنف جديد',
        'item_name' => 'اسم الصنف:',
        'category' => 'القسم:',
        'description' => 'الوصف (اختياري):',
        'pricing' => 'تسعير الأحجام (جنيه مصري)',
        'small' => 'صغير (S)',
        'medium' => 'وسط (M)',
        'large' => 'كبير (L)',
        'upload_img' => 'صورة المنتج:',
        'save' => 'حفظ الصنف في قاعدة البيانات'
    ],
    'en' => [
        'home' => 'Home',
        'menu' => 'Menu',
        'contact' => 'Contact Us',
        'cart' => 'Cart',
        'qty' => 'Qty',
        'price' => 'Price',
        'total' => 'Total',
        'checkout' => 'Checkout',
        'empty_cart' => 'Cart is empty!',
        'add_to_cart' => 'Add to Cart',
        'name_label' => 'Full Name:',
        'phone_label' => 'Phone Number:',
        'address_label' => 'Detailed Address:',
        'send_order' => 'Confirm via WhatsApp',
        'add_new' => 'Add New Item',
        'item_name' => 'Item Name:',
        'category' => 'Category:',
        'description' => 'Description (Optional):',
        'pricing' => 'Size Pricing (EGP)',
        'small' => 'Small (S)',
        'medium' => 'Medium (M)',
        'large' => 'Large (L)',
        'upload_img' => 'Product Image:',
        'save' => 'Save Item'
    ],
    'ru' => [
        'home' => 'Главная',
        'menu' => 'Меню',
        'contact' => 'Контакты',
        'cart' => 'Корзина',
        'qty' => 'Кол-во',
        'price' => 'Цена',
        'total' => 'Итого',
        'checkout' => 'Оформить заказ',
        'empty_cart' => 'Корзина пуста!',
        'add_to_cart' => 'В корзину',
        'name_label' => 'Полное имя:',
        'phone_label' => 'Номер телефона:',
        'address_label' => 'Подробный адрес:',
        'send_order' => 'Подтвердить в WhatsApp',
        'add_new' => 'Добавить товар',
        'item_name' => 'Название:',
        'category' => 'Категория:',
        'description' => 'Описание:',
        'pricing' => 'Цены (EGP)',
        'small' => 'Маленький (S)',
        'medium' => 'Средний (M)',
        'large' => 'Большой (L)',
        'upload_img' => 'Изображение:',
        'save' => 'Сохранить'
    ]
];

$txt = $languages[$_SESSION['lang']];
?>