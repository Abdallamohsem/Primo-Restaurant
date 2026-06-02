<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'db_connect.php'; 
include 'lang_config.php'; 

// تحديد القسم المختار (الافتراضي: الكل)
$selected_cat = isset($_GET['cat']) ? $_GET['cat'] : 'all';
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>" dir="<?php echo ($_SESSION['lang'] == 'ar') ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منيو بريمو - Primo Menu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .nav-pills .nav-link { color: #555; border-radius: 50px; padding: 10px 25px; font-weight: bold; transition: 0.3s; border: 1px solid #ddd; margin: 0 5px; }
        .nav-pills .nav-link.active { background-color: #cc0000 !important; color: #fff !important; border-color: #cc0000; box-shadow: 0 4px 10px rgba(204, 0, 0, 0.2); }
        .product-card { border: none; border-radius: 20px; transition: 0.3s; overflow: hidden; height: 100%; }
        .product-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        .price-badge { background: #fff5f5; color: #cc0000; border: 1px solid #ffcccc; border-radius: 10px; padding: 5px 15px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="container mt-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold text-danger">قائمة الطعام</h1>
            <p class="text-muted">اختر القسم المفضل لديك</p>
        </div>

        <div class="d-flex justify-content-center mb-5">
            <ul class="nav nav-pills bg-white shadow-sm p-3 rounded-pill flex-wrap justify-content-center">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($selected_cat == 'all' ? 'active' : ''); ?>" href="menu.php">
                        <i class="fa fa-th-large me-1"></i> الكل
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($selected_cat == 'pizza_meat' ? 'active' : ''); ?>" href="?cat=pizza_meat">🥩 لحوم</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($selected_cat == 'pizza_chicken' ? 'active' : ''); ?>" href="?cat=pizza_chicken">🍗 دجاج</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($selected_cat == 'pizza_roll' ? 'active' : ''); ?>" href="?cat=pizza_roll">🌀 رول</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($selected_cat == 'crepe' ? 'active' : ''); ?>" href="?cat=crepe">🥞 كريب</a>
                </li>
            </ul>
        </div>

        <div class="row g-4">
            <?php
            // استعلام ديناميكي مؤمن
            $selected_cat = $conn->real_escape_string($selected_cat);
            $sql = ($selected_cat == 'all') ? "SELECT * FROM products" : "SELECT * FROM products WHERE category = '$selected_cat'";
            
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0):
                while($item = $result->fetch_assoc()):
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card product-card shadow-sm">
                    <img src="images/<?php echo $item['image']; ?>" class="card-img-top" style="height: 200px; object-fit: cover;" onerror="this.src='images/default.jpg'">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold mb-0"><?php echo $item['name']; ?></h5>
                            <span class="price-badge"><?php echo $item['price_large']; ?> ج</span>
                        </div>
                        <p class="text-muted small mb-3"><?php echo $item['category']; // أو الوصف لو عندك ?></p>
                        
                        <button class="btn btn-outline-danger w-100 rounded-pill fw-bold" onclick="location.href='index.php#menu'">
                             اطلب الآن <i class="fa fa-arrow-left ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endwhile; else: ?>
            <div class="col-12 text-center py-5">
                <div class="mb-3 text-muted" style="font-size: 4rem;"><i class="fa fa-utensils"></i></div>
                <h4 class="text-muted">مفيش منتجات في القسم ده لسه يا بطل!</h4>
                <a href="menu.php" class="btn btn-danger mt-3 rounded-pill">عرض كل المنيو</a>
            </div>
            <?php endif; ?>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>