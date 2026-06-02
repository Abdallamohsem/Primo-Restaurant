<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'lang_config.php'; 
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>" dir="<?php echo ($_SESSION['lang'] == 'ar') ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم استلام طلبك - Primo Success</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', sans-serif; }
        .success-card { background: white; padding: 40px; border-radius: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); text-align: center; max-width: 500px; width: 90%; }
        .check-icon { font-size: 80px; color: #28a745; margin-bottom: 20px; animation: scaleUp 0.5s ease-in-out; }
        @keyframes scaleUp { 0% { transform: scale(0); } 100% { transform: scale(1); } }
        .btn-home { background-color: #cc0000; color: white; border-radius: 50px; padding: 12px 30px; font-weight: bold; transition: 0.3s; text-decoration: none; display: inline-block; margin-top: 20px; }
        .btn-home:hover { background-color: #a30000; color: white; transform: translateY(-3px); }
    </style>
</head>
<body>

    <div class="success-card">
        <div class="check-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2 class="fw-bold mb-3"><?php echo ($_SESSION['lang'] == 'ar') ? 'تم طلبك بنجاح!' : 'Order Placed Successfully!'; ?></h2>
        <p class="text-muted mb-4">
            <?php 
                if ($_SESSION['lang'] == 'ar') {
                    echo "شكراً لاختيارك بريمو. طلبك قيد التحضير الآن وسيتواصل معك الدليفري قريباً.";
                } else {
                    echo "Thank you for choosing Primo. Your order is being prepared and our delivery star will contact you soon.";
                }
            ?>
        </p>
        
        <div class="p-3 bg-light rounded-3 mb-4">
            <small class="text-muted d-block"><?php echo ($_SESSION['lang'] == 'ar') ? 'وقت الطلب' : 'Order Time'; ?></small>
            <strong class="fs-5"><?php echo date('h:i A'); ?></strong>
        </div>

        <a href="index.php" class="btn-home">
            <i class="fas fa-utensils me-2"></i> <?php echo ($_SESSION['lang'] == 'ar') ? 'العودة للمنيو' : 'Back to Menu'; ?>
        </a>
    </div>

</body>
</html>