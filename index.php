<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'db_connect.php'; // استدعاء ملف الاتصال المركزي اللي ظبطناه
require_once 'lang_config.php'; // لدعم اللغات مستقبلاً

// دالة عرض الأقسام بطريقة احترافية ومحدثة
function displayCategory($conn, $categoryName, $categoryTitle) {
    // جلب المنتجات حسب القسم
    $sql = "SELECT * FROM products WHERE category = '$categoryName'";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        echo '<h2 class="text-center fw-bold my-5 py-3 bg-danger text-white rounded-pill shadow-sm" id="cat-'.$categoryName.'">' . $categoryTitle . '</h2>';
        echo '<div class="row">';
        while($row = $result->fetch_assoc()) {
            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
                    <img src="images/<?php echo $row['image']; ?>" class="card-img-top" style="height: 220px; object-fit: cover;" onerror="this.src='images/default.jpg'">
                    
                    <div class="card-body text-center">
                        <h5 class="fw-bold mb-3"><?php echo $row['name']; ?></h5>
                        
                        <?php 
                        // فحص: هل المنتج له مقاسات أم سعر واحد فقط؟
                        if (empty($row['price_small']) && empty($row['price_medium'])): ?>
                            <div class="alert alert-light border-danger rounded-pill py-2 mb-3">
                                <span class="fw-bold text-danger">السعر: <?php echo $row['price_large']; ?> ج</span>
                            </div>
                            <input type="hidden" id="size-<?php echo $row['id']; ?>" value="Standard">
                        <?php else: ?>
                            <select class="form-select mb-3 border-danger rounded-pill shadow-sm text-center" id="size-<?php echo $row['id']; ?>">
                                <?php if(!empty($row['price_small'])): ?><option value="صغير">صغير (<?php echo $row['price_small']; ?> ج)</option><?php endif; ?>
                                <?php if(!empty($row['price_medium'])): ?><option value="وسط">وسط (<?php echo $row['price_medium']; ?> ج)</option><?php endif; ?>
                                <?php if(!empty($row['price_large'])): ?><option value="كبير">كبير (<?php echo $row['price_large']; ?> ج)</option><?php endif; ?>
                            </select>
                        <?php endif; ?>

                        <div class="d-flex justify-content-center align-items-center mb-3 bg-light py-2 rounded-pill border">
                            <button class="btn btn-sm text-danger border-0 fw-bold" onclick="updateQty(<?php echo $row['id']; ?>, -1)">
                                <i class="fa fa-minus-circle fs-5"></i>
                            </button>
                            <span class="mx-4 fw-bold fs-5 text-dark" id="qty-<?php echo $row['id']; ?>">0</span>
                            <button class="btn btn-sm text-success border-0 fw-bold" onclick="updateQty(<?php echo $row['id']; ?>, 1)">
                                <i class="fa fa-plus-circle fs-5"></i>
                            </button>
                        </div>
                        
                        <button class="btn btn-danger w-100 fw-bold rounded-pill py-2 shadow-sm" onclick="addToCart(<?php echo $row['id']; ?>, '<?php echo $row['name']; ?>')">
                            <i class="fa fa-cart-plus me-2"></i> إضافة للسلة
                        </button>
                    </div>
                </div>
            </div>
            <?php
        }
        echo '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بريمو - المنيو الرسمي</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #fcfcfc; font-family: 'Segoe UI', Tahoma, sans-serif; }
        .navbar { background: linear-gradient(90deg, #cc0000, #ff0000) !important; padding: 15px 0; }
        .nav-link { color: white !important; font-weight: 600; font-size: 1.1rem; }
        .card { transition: 0.3s; border: none !important; }
        .card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
        .badge-cart { background: #ffc107; color: #000; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top shadow">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="images/logo.jpg" width="60" class="rounded-circle border border-2 border-white">
        </a>
        <button class="navbar-toggler border-white" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <i class="fa fa-bars text-white"></i>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link px-3" href="index.php">الرئيسية</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="#menu">المنيو</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="contact.php">اتصل بنا</a></li>
            </ul>
            <div class="d-flex align-items-center">
                <a href="cart.php" class="text-white position-relative mx-3 text-decoration-none bg-dark p-2 rounded-circle">
                    <i class="fa fa-shopping-basket"></i>
                    <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge-cart">
                        <?php 
                        $total_qty = 0;
                        if(isset($_SESSION['cart'])){
                            foreach($_SESSION['cart'] as $item) { $total_qty += $item['qty']; }
                        }
                        echo $total_qty; 
                        ?>
                    </span>
                </a>
            </div>
        </div>
    </div>
</nav>

<div id="primoSlider" class="carousel slide shadow mb-5" data-bs-ride="carousel">
    <div class="carousel-inner" style="max-height: 450px;">
        <div class="carousel-item active">
            <img src="images/slide1.jpg" class="d-block w-100" style="object-fit: cover;">
        </div>
        </div>
</div>

<div class="container" id="menu">
    <?php 
    // عرض الأقسام بناءً على قاعدة البيانات والصور المرفقة
    displayCategory($conn, 'pizza', '🍕 قسم البيتزا الإيطالي'); 
    displayCategory($conn, 'pasta', '🍝 قسم الباستا اللذيذة');
    displayCategory($conn, 'crepe', '🌯 قسم الكريبات العالمي');
    displayCategory($conn, 'sandwiches', '🥖 بيتزا سندوتشات الرول');
    displayCategory($conn, 'meals', '🍱 قسم الوجبات');
    displayCategory($conn, 'drinks', '🥤 المشروبات المنعشة');
    displayCategory($conn, 'offers', '🎁 العروض الخاصة');
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function updateQty(id, change) {
        let el = document.getElementById('qty-' + id);
        let val = parseInt(el.innerText) + change;
        if (val >= 0) { el.innerText = val; }
    }

    function addToCart(id, name) {
        let qtyLabel = document.getElementById('qty-' + id);
        let qty = parseInt(qtyLabel.innerText);
        let size = document.getElementById('size-' + id).value;

        if (qty <= 0) {
            Swal.fire({ title: 'تنبيه', text: 'يرجى تحديد الكمية أولاً.', icon: 'warning' });
            return;
        }

        let formData = new FormData();
        formData.append('id', id);
        formData.append('name', name);
        formData.append('size', size);
        formData.append('qty', qty);

        fetch('add_to_cart.php', { method: 'POST', body: formData })
        .then(response => response.text())
        .then(data => {
            document.getElementById('cart-count').innerText = data;
            qtyLabel.innerText = "0";
            Swal.fire({ title: 'تمت الإضافة!', text: 'تمت إضافة ' + name + ' للسلة', icon: 'success', timer: 1000, showConfirmButton: false });
        });
    }
</script>
</body>
</html>