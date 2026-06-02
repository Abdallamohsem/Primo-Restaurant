<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'db_connect.php'; // استخدم الملف اللي ظبطناه سوا للأمان

// 1. التأكد إن البيانات مبعوثة من الفورم والسلة مش فاضية
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    
    // 2. تأمين البيانات (SQL Injection Protection)
    // بنستخدم mysqli_real_escape_string عشان نحمي قاعدة البيانات
    $name    = $conn->real_escape_string($_POST['customer_name']);
    $phone   = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $payment = "نقداً عند الاستلام"; 
    $total   = (float)$_POST['total_price']; 

    // 3. جملة الحفظ في جدول orders
    $sql = "INSERT INTO orders (customer_name, phone, address, payment_method, total_price, order_date, status) 
            VALUES ('$name', '$phone', '$address', '$payment', '$total', NOW(), 'pending')";

    if ($conn->query($sql) === TRUE) {
        $order_id = $conn->insert_id; // رقم الطلب اللي لسه متسيف حالا

        // 4. (خطوة احترافية) حفظ تفاصيل الأصناف في جدول order_items
        // ده عشان لما تفتح الـ Admin تعرف الزبون طلب بيتزا إيه بالظبط
        foreach ($_SESSION['cart'] as $item) {
            $p_name  = $item['name'];
            $p_size  = $item['size'];
            $p_qty   = $item['qty'];
            $p_price = $item['price'];
            
            $conn->query("INSERT INTO order_items (order_id, product_name, size, qty, price) 
                          VALUES ('$order_id', '$p_name', '$p_size', '$p_qty', '$p_price')");
        }

        // 5. تصفير السلة والتوجيه لرسالة النجاح أو الواتساب
        unset($_SESSION['cart']);
        
        echo "<script>
                alert('تم استلام طلبك بنجاح برقم: #$order_id');
                window.location.href='index.php';
              </script>";
    } else {
        echo "❌ خطأ في النظام: " . $conn->error;
    }
} else {
    // لو حد حاول يدخل الصفحة دي مباشرة من غير طلب
    header("Location: index.php");
    exit();
}
?>