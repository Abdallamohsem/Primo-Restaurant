<?php 
// 1. استدعاء ملف الاتصال واللغات
include 'db_connect.php'; 
include 'lang_config.php'; 

// 2. نظام حماية
$admin_password = "abdalla_admin_2026"; 

if (isset($_POST['login_pass']) && $_POST['login_pass'] === $admin_password) {
    session_regenerate_id(); 
    $_SESSION['admin_auth'] = true;
}

$is_authenticated = isset($_SESSION['admin_auth']) && $_SESSION['admin_auth'] === true;

// مصفوفة ترجمة سريعة لداخل الصفحة (لو حبيت تضيفها لملف اللغات الأساسي يكون أفضل)
$page_txt = [
    'ar' => ['title' => 'إضافة صنف جديد', 'btn' => 'إضافة الصنف للمطعم', 'name' => 'اسم الصنف', 'cat' => 'القسم'],
    'en' => ['title' => 'Add New Item', 'btn' => 'Add to Menu', 'name' => 'Item Name', 'cat' => 'Category'],
    'ru' => ['title' => 'Добавить блюдо', 'btn' => 'Добавить в меню', 'name' => 'Название', 'cat' => 'Категория']
][$_SESSION['lang']];
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>" dir="<?php echo ($_SESSION['lang'] == 'ar') ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_txt['title']; ?> - Primo</title>
    
    <?php if($_SESSION['lang'] == 'ar'): ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <?php else: ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <?php endif; ?>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .admin-card { border-radius: 15px; border: none; }
        .btn-save { background: #111; color: #fff; border-radius: 30px; padding: 12px 25px; transition: 0.3s; font-weight: bold; }
        .btn-save:hover { background: #cc0000; transform: translateY(-2px); color: #fff; }
        .price-box { background: #fff; border: 1px solid #dee2e6; }
        .form-control:focus, .form-select:focus { border-color: #cc0000; box-shadow: 0 0 0 0.25rem rgba(204, 0, 0, 0.1); }
    </style>
</head>
<body>

<?php if (!$is_authenticated): ?>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow-lg p-4 text-center" style="max-width: 400px; width: 100%; border-radius: 20px;">
            <div class="mb-3"><i class="fa fa-user-shield fa-4x text-dark"></i></div>
            <h4 class="fw-bold mb-4">دخول المشرفين</h4>
            <form method="POST">
                <input type="password" name="login_pass" class="form-control rounded-pill text-center mb-3" placeholder="كلمة المرور">
                <button type="submit" class="btn btn-dark w-100 rounded-pill py-2">دخول النظام</button>
            </form>
        </div>
    </div>
<?php else: ?>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card admin-card shadow-sm p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold m-0 text-danger"><i class="fa fa-utensils me-2"></i> <?php echo $page_txt['title']; ?></h3>
                        <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                            <a href="?lang=ar" class="btn btn-sm btn-light <?php echo ($_SESSION['lang'] == 'ar' ? 'active fw-bold bg-danger text-white' : ''); ?>">AR</a>
                            <a href="?lang=en" class="btn btn-sm btn-light <?php echo ($_SESSION['lang'] == 'en' ? 'active fw-bold bg-danger text-white' : ''); ?>">EN</a>
                            <a href="?lang=ru" class="btn btn-sm btn-light <?php echo ($_SESSION['lang'] == 'ru' ? 'active fw-bold bg-danger text-white' : ''); ?>">RU</a>
                        </div>
                    </div>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold"><?php echo $page_txt['name']; ?>:</label>
                                <input type="text" name="name" class="form-control rounded-3" required placeholder="مثلاً: تشيكن رانش">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold"><?php echo $page_txt['cat']; ?>:</label>
                                <select name="category" class="form-select rounded-3" required>
                                    <option value="" selected disabled>-- اختر القسم --</option>
                                    <optgroup label="🍕 أقسام البيتزا">
                                        <option value="pizza_veg">بيتزا (نباتي)</option>
                                        <option value="pizza_meat">بيتزا (لحوم)</option>
                                        <option value="pizza_chicken">بيتزا (دجاج)</option>
                                        <option value="pizza_seafood">بيتزا (سي فود)</option>
                                        <option value="pizza_roll">بيتزا ساندوتش رول</option>
                                    </optgroup>
                                    <optgroup label="🍟 الوجبات والأصناف الأخرى">
                                        <option value="meals">وجبات كاملة</option>
                                        <option value="pasta">باستا</option>
                                        <option value="crepe">كريب</option>
                                        <option value="side_dishes">أصناف جانبية</option>
                                        <option value="drinks">مشروبات</option>
                                    </optgroup>
                                    <optgroup label="🎁 العروض">
                                        <option value="offers">عروض خاصة</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">الوصف:</label>
                                <textarea name="description" class="form-control rounded-3" rows="2" placeholder="اكتب مكونات الصنف هنا..."></textarea>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <h6 class="text-muted border-bottom pb-2">تسعير الأحجام (EGP)</h6>
                                <div class="row g-2 p-3 price-box rounded-3 shadow-sm">
                                    <div class="col-4 text-center">
                                        <label class="small text-muted fw-bold">صغير (S)</label>
                                        <input type="number" step="0.01" name="p_small" class="form-control text-center" placeholder="0.00">
                                    </div>
                                    <div class="col-4 text-center">
                                        <label class="small text-muted fw-bold">وسط (M)</label>
                                        <input type="number" step="0.01" name="p_medium" class="form-control text-center" placeholder="0.00">
                                    </div>
                                    <div class="col-4 text-center">
                                        <label class="small text-muted fw-bold">كبير (L)</label>
                                        <input type="number" step="0.01" name="p_large" class="form-control text-center" placeholder="0.00">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-3 mt-4">
                                <label class="form-label fw-semibold">صورة المنتج:</label>
                                <input type="file" name="image" class="form-control" required>
                            </div>

                            <div class="col-12 text-center mt-3">
                                <button type="submit" name="submit" class="btn btn-save w-100 shadow">
                                    <i class="fa fa-plus me-2"></i> <?php echo $page_txt['btn']; ?>
                                </button>
                            </div>
                        </div>
                    </form>

                    <?php
                    if(isset($_POST['submit'])){
                        $name = $conn->real_escape_string($_POST['name']);
                        $desc = $conn->real_escape_string($_POST['description']);
                        $cat  = $conn->real_escape_string($_POST['category']);
                        $s = (float)$_POST['p_small'];
                        $m = (float)$_POST['p_medium'];
                        $l = (float)$_POST['p_large'];

                        $img_name = $_FILES['image']['name'];
                        $ext = pathinfo($img_name, PATHINFO_EXTENSION);
                        $target = "images/" . time() . "_" . uniqid() . "." . $ext;

                        if(move_uploaded_file($_FILES['image']['tmp_name'], $target)){
                            $img_db = basename($target);
                            $sql = "INSERT INTO products (name, description, category, image, price_small, price_medium, price_large) 
                                    VALUES ('$name', '$desc', '$cat', '$img_db', '$s', '$m', '$l')";
                            
                            if($conn->query($sql)){
                                echo "<script>Swal.fire({title: 'نجاح', text: 'تم إضافة الصنف للمنيو', icon: 'success', confirmButtonColor: '#cc0000'});</script>";
                            }
                        }
                    }
                    ?>
                </div>
                <div class="text-center mt-4">
                    <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-pill px-4"><i class="fa fa-home me-1"></i> العودة للموقع</a>
                    <a href="logout.php" class="btn btn-link text-muted text-decoration-none ms-3"><i class="fa fa-door-open me-1"></i> خروج</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

</body>
</html>