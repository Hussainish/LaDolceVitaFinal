<?php include 'session_status.php';?>
<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../utils/utils.php';

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    header("Location: index.php?page=auth&login_error=" . urlencode("You need to log in to access the menu"));
    exit;
}

// load categories
$categories_sql = "SELECT * FROM categories";
$categories_result = mysqli_query($mysqli, $categories_sql);

// determine selected category/subcategory and search query - in case a category or subcategory is selected
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$subcategory_id = isset($_GET['subcategory_id']) ? intval($_GET['subcategory_id']) : 0;
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

// load subcategories for the selected category
$stmt = $mysqli->prepare("SELECT * FROM subcategories WHERE CategoryID = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$subcategories_result = $stmt->get_result();

// prepare the menu items query and include the search term
$stmt = $mysqli->prepare("SELECT * FROM menu WHERE (CategoryID = ? OR ? = 0) AND (SubcategoryID = ? OR ? = 0) AND Name LIKE ?");
$search_term = "%$search_query%";
$stmt->bind_param("iiiss", $category_id, $category_id, $subcategory_id, $subcategory_id, $search_term);
$stmt->execute();
$menu_items_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="/project/">
    <title>Menu - La Dolce Vita</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
        /* vertical navbar for categories */
        .vertical-navbar {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .vertical-navbar .nav-link {
            color: #333;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 5px;
            transition: background 0.3s, color 0.3s;
        }
        .vertical-navbar .nav-link:hover {
            background-color: #e9ecef;
            color: #007bff;
        }
        .vertical-navbar .nav-link.active {
            background-color: #007bff;
            color: #fff;
        }
        .vertical-navbar .subcategory-link {
            padding-left: 30px;
            font-size: 0.9rem;
        }
        .vertical-navbar .subcategory-link:hover {
            background-color: #d0e0f0;
        }
        .vertical-navbar .subcategory-link.active {
            background-color: #0056b3;
            color: #fff;
        }
        /* menu cards */
        .menu-card {
            height: 450px;
            overflow: hidden;
            position: relative;
            margin-bottom: 30px;
            border: none;
            border-radius: 8px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        /* make the card have float- like effect when hovering over it basically making it move up a bit*/
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .menu-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .menu-card .card-body {
            padding: 15px;
            position: relative;
        }
        .menu-card .card-title {
            font-size: 1.4rem;
            margin-bottom: 10px;
        }
        .menu-card .card-text {
            max-height: 100px;
            overflow-y: auto;
            margin-bottom: 10px;
        }
        .menu-card .price {
            position: absolute;
            bottom: 10px;
            left: 15px;
            font-size: 1.2rem;
            font-weight: bold;
        }
        .menu-card .add-to-cart {
            position: absolute;
            bottom: 10px;
            right: 15px;
        }
        /* search form */
        .input-group .form-control {
            border-radius: 0.25rem 0 0 0.25rem;
        }
        .input-group .btn {
            border-radius: 0 0.25rem 0.25rem 0;
        }
    </style>
</head>
<body>
    <!-- navigation bar -->
    <div>
        <?php require_once realpath(__DIR__ . '/../views/header.php');?>
    </div>
    
    <div class="container mt-5">
        <div class="row">
            <!-- vertical navbar for categories -->
            <div class="col-md-3">
                <div class="vertical-navbar">
                    <!-- search form -->
                    <form method="GET" action="index.php">
                    <input type="hidden" name="page" value="menu">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Search menu..." name="search" value="<?= htmlspecialchars($search_query) ?>">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                    <!-- categories navigation -->
                    <nav class="nav flex-column">
                        <a class="nav-link <?= ($category_id == 0) ? 'active' : '' ?>" href="index.php?page=menu&category_id=0">Show All</a>
                        <?php while ($category = mysqli_fetch_assoc($categories_result)): ?>
                            <a class="nav-link <?= ($category['CategoryID'] == $category_id) ? 'active' : '' ?>" href="index.php?page=menu&category_id=<?= $category['CategoryID'] ?>">
                                <?= htmlspecialchars($category['CategoryName']) ?>
                            </a>
                            <?php if ($category['CategoryID'] == $category_id): ?>
                                <ul class="list-unstyled">
                                    <?php while ($subcategory = mysqli_fetch_assoc($subcategories_result)): ?>
                                        <li>
                                            <a class="nav-link subcategory-link <?= ($subcategory['SubcategoryID'] == $subcategory_id) ? 'active' : '' ?>" href="index.php?page=menu&category_id=<?= $category_id ?>&subcategory_id=<?= $subcategory['SubcategoryID'] ?>">
                                                <?= htmlspecialchars($subcategory['SubcategoryName']) ?>
                                            </a>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    </nav>
                </div>
            </div>
            <!-- menu items list -->
            <div class="col-md-9">
                <div class="row">
                    <?php if (mysqli_num_rows($menu_items_result) > 0): ?>
                        <?php while ($menu_item = mysqli_fetch_assoc($menu_items_result)): ?>
                            <div class="col-md-4">
                                <div class="card menu-card shadow-sm">
                                    <img src="<?= htmlspecialchars($menu_item['Image']) ?>" alt="<?= htmlspecialchars($menu_item['Name']) ?>" class="card-img-top">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title"><?= htmlspecialchars($menu_item['Name']) ?></h5>
                                        <p class="card-text"><?= htmlspecialchars($menu_item['Description']) ?></p>
                                        <div class="price">$<?= htmlspecialchars($menu_item['Price']) ?></div>
                                        <a href="index.php?page=cart&action=add&id=<?= $menu_item['MenuItemID'] ?>" class="btn btn-primary add-to-cart">Add to Cart</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <p>No menu items found for the search query.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="footer">
        <div class="container text-center">
            <p>&copy; 2024 La Dolce Vita. All rights Reserved. </p>
        </div>
    </footer>
    
    <script src="/assets/js/scripts.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
