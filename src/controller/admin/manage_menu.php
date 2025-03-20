<?php


require_once realpath(__DIR__ . '/../../../config/config.php');
require_once realpath(__DIR__ . '/../../../utils/utils.php');

checkLogin();


$categories = mysqli_query($mysqli, "SELECT * FROM categories");
$subcategories = mysqli_query($mysqli, "SELECT * FROM subcategories");

// cloudinary API configuration, feel free to use your own or keep mine 
$cloud_name = "duns7qt5s";
$upload_preset = "menu_uploads";

// handle adding or updating a menu item
if (isset($_POST['save'])) {
    $MenuItemID = $_POST['MenuItemID'] ?? null;
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $categoryID = $_POST['category'];
    $subcategoryID = $_POST['subcategory'];

    if (empty($name) || empty($description) || empty($price)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit;
    }

    if (!is_numeric($price) || $price <= 0) {
        echo "<script>alert('Invalid price. Must be a positive number.'); window.history.back();</script>";
        exit;
    }

    // handle image upload to cloudinary (only if a new image is uploaded)
    if (!empty($_FILES["image"]["tmp_name"])) {
        $image_file = $_FILES["image"]["tmp_name"];
        $image_name = $_FILES["image"]["name"];
        $image_mime = mime_content_type($image_file);
    
        $ch = curl_init();
        // again you may change to your own API, so you can change the url below to your own, but make sure you update the api configuration above
        curl_setopt($ch, CURLOPT_URL, "https://api.cloudinary.com/v1_1/$cloud_name/image/upload");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            "file" => new CURLFile($image_file, $image_mime, $image_name),
            "upload_preset" => $upload_preset
        ]);
    
        $response = curl_exec($ch);
        curl_close($ch);
    
        $response_data = json_decode($response, true);
        if (!isset($response_data["secure_url"])) {
            echo "<script>alert('Error uploading image to Cloudinary.'); window.history.back();</script>";
            exit;
        }
        $image_url = $response_data["secure_url"];
    }

    // insert or update Menu Item
    if ($MenuItemID) {
        $stmt = $mysqli->prepare("UPDATE menu SET Name=?, Description=?, Price=?, CategoryID=?, SubcategoryID=?, Image=IF(? != '', ?, Image) WHERE MenuItemID=?");
        $stmt->bind_param("ssdiissi", $name, $description, $price, $categoryID, $subcategoryID, $image_url, $image_url, $MenuItemID);
    } else {
        $stmt = $mysqli->prepare("INSERT INTO menu (Name, Description, Price, Image, CategoryID, SubcategoryID) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsii", $name, $description, $price, $image_url, $categoryID, $subcategoryID);
    }

    if ($stmt->execute()) {
        echo '<script>alert("Menu item saved successfully."); window.location.href="index.php?page=admin_manage_menu";</script>';
    } else {
        echo '<script>alert("Error saving menu item."); window.history.back();</script>';
    }
}

// handle deleting a menu item
if (isset($_GET['delete'])) {
    $MenuItemID = $_GET['delete'];
    $stmt = $mysqli->prepare("DELETE FROM menu WHERE MenuItemID = ?");
    $stmt->bind_param("i", $MenuItemID);

    if ($stmt->execute()) {
        redirectWithMessage("index.php?page=admin_manage_menu", "Menu item deleted successfully!");
    } else {
        redirectWithMessage("index.php?page=admin_manage_menu", "Error deleting menu item.", "error");
    }
}
$limit = 5; // limit number of items per page
$p = isset($_GET['p']) && is_numeric($_GET['p']) ? intval($_GET['p']) : 1;
$offset = ($p - 1) * $limit;

// count total menu items for pagination
$total_items_query = "SELECT COUNT(*) as total FROM menu";
$total_items_result = mysqli_query($mysqli, $total_items_query);
$total_items_row = mysqli_fetch_assoc($total_items_result);
$total_items = $total_items_row['total'];
$totalPages = ceil($total_items / $limit);

$searchTerm = isset($_GET['searchTerm']) ? trim($_GET['searchTerm']) : '';
$sql = "
    SELECT menu.MenuItemID, menu.Name, menu.Description, menu.Price, menu.Image,
           menu.CategoryID, menu.SubcategoryID, categories.CategoryName, subcategories.SubcategoryName
    FROM menu
    LEFT JOIN categories ON menu.CategoryID = categories.CategoryID
    LEFT JOIN subcategories ON menu.SubcategoryID = subcategories.SubcategoryID
";

if (!empty($searchTerm)) {
    $sql .= " WHERE menu.Name LIKE '%" . mysqli_real_escape_string($mysqli, $searchTerm) . "%'";
}

$sql .= " LIMIT $limit OFFSET $offset"; // add pagination limit
$result = mysqli_query($mysqli, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="/project/">
    <title>Manage Menu - La Dolce Vita</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>

        body {
            background-color: #f8f9fa;
        }
        
        .container {
            width: 95%;
            margin: auto;
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            margin-bottom: 20px;
        }
        .table img {
            border-radius: 5px;
            width: 70px;
            height: 70px;
            object-fit: cover;
        }
        .btn-actions {
            display: flex;
            gap: 5px;
        }
        .description-cell {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .modal-dialog {
            max-width: 500px;
        }
        .image-preview {
            width: 70px; 
            height: 70px; 
            object-fit: cover; 
            display: none;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc; 
        }
    </style>
</head>
<body>
    <header id="navbar">
        <?php require_once realpath(__DIR__ . '/../../views/header.php'); ?>
    </header>
    
    <div class="container mt-5">
        <h2 class="text-center mb-4">Manage Menu</h2>
        <a href="index.php?page=admin_dashboard" class="btn btn-secondary mb-3">Back to Dashboard</a>
        
        <div class="card p-4">
            <h4 class="mb-3">Add / Update Menu Item</h4>
            <form method="post" action="index.php?page=admin_manage_menu" enctype="multipart/form-data">
                <input type="hidden" name="MenuItemID" id="MenuItemID">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Name:</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Price ($):</label>
                        <input type="number" step="0.01" name="price" id="price" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Category:</label>
                        <select name="category" id="category" class="form-control" required>
                            <?php while ($row = mysqli_fetch_assoc($categories)): ?>
                                <option value="<?= $row['CategoryID'] ?>"><?= htmlspecialchars($row['CategoryName']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Subcategory:</label>
                        <select name="subcategory" id="subcategory" class="form-control" required>
                            <?php while ($row = mysqli_fetch_assoc($subcategories)): ?>
                                <option value="<?= $row['SubcategoryID'] ?>"><?= htmlspecialchars($row['SubcategoryName']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" id="description" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label>Image:</label>
                    <input type="file" name="image" id="image" class="form-control">
                    <img id="imagePreview" class="image-preview">
                </div>
                <button type="submit" name="save" class="btn btn-success mt-2">Save Item</button>
            </form>
        </div>
        <div class="card p-4 table-responsive">
            <h4 class="mb-3">Menu Items</h4>
            <div class="mb-3">
                <form method="GET" action="index.php" class="form-inline">
                <input type="hidden" name="page" value="admin_manage_menu">
                    <input type="text" name="searchTerm" class="form-control mr-2" placeholder="Search Menu Items..." value="<?= htmlspecialchars($searchTerm) ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <?php if (!empty($searchTerm)): ?>
                        <a href="index.php?page=admin_manage_menu" class="btn btn-secondary ml-2">Clear</a>
                    <?php endif; ?>
                </form>
            </div>
            <table class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['MenuItemID'] ?></td>
                            <td><?= htmlspecialchars($row['Name']) ?></td>
                            <td class="description-cell">
                            <?php if (strlen($row['Description']) > 15): ?>
                                <?= substr(htmlspecialchars($row['Description']), 0, 15) ?>...
                                <a href="#" onclick="showDescription('<?= htmlspecialchars($row['Description'], ENT_QUOTES) ?>')" style="display: block; color: blue; text-decoration: underline; cursor: pointer;">See more</a>
                            <?php else: ?>
                                <?= htmlspecialchars($row['Description']) ?>
                            <?php endif; ?>
                            </td>
                            <td>$<?= number_format($row['Price'], 2) ?></td>
                            <td><img src="<?= $row['Image'] ?>"></td>
                            <td><?= htmlspecialchars($row['CategoryName']) ?></td>
                            <td><?= isset($row['SubcategoryName']) ? htmlspecialchars($row['SubcategoryName']) : "N/A" ?></td>
                            <td class="btn-actions">
                                <a href="index.php?page=admin_manage_menu&delete=<?= $row['MenuItemID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                <button class="btn btn-primary btn-sm" onclick="editItem(
                                    '<?= $row['MenuItemID'] ?>',
                                    '<?= htmlspecialchars($row['Name'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($row['Description'], ENT_QUOTES) ?>',
                                    '<?= $row['Price'] ?>',
                                    '<?= $row['CategoryID'] ?>',
                                    '<?= $row['SubcategoryID'] ?>',
                                    '<?= htmlspecialchars($row['Image'], ENT_QUOTES) ?>'
                                )">Edit</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php
            
            $baseUrl = "index.php?page=admin_manage_menu&";
            if ($searchTerm) {
                $baseUrl .= "searchTerm=" . urlencode($searchTerm) . "&";
            }
            echo generatePaginationLinks($p, $totalPages, $baseUrl);
            ?>
        </div>
    </div>
    <div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Full Description</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalDescription"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function showDescription(description) {
            document.getElementById('modalDescription').innerText = description;
            $('#descriptionModal').modal('show');
        }
    </script>
    
    <script>
        function editItem(MenuItemID, name, description, price, categoryID, subcategoryID, image) {
            document.getElementById('MenuItemID').value = MenuItemID;
            document.getElementById('name').value = name;
            document.getElementById('description').value = description;
            document.getElementById('price').value = price;
            document.getElementById('category').value = categoryID;
            document.getElementById('subcategory').value = subcategoryID;

            if (image) {
                const imgPreview = document.getElementById('imagePreview');
                imgPreview.src = image;
                imgPreview.style.display = "block";
                imgPreview.style.width = "200px"; 
                imgPreview.style.height = "200px";
                imgPreview.style.objectFit = "cover";
            }
        }
    </script>

    <footer class="footer">
        <div class="container text-center">
            <p>&copy; 2024 La Dolce Vita. All rights Reserved. </p>
        </div>
    </footer>
    
    <script src="/assets/js/scripts.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
`