<?php
require_once("product.model.php");

$page = $_GET["page"] ?? "home";

// ---- Save Manufacturer ----
if (isset($_POST["save_mfg"])) {
    Product::saveMfg($_POST["name"], $_POST["contact"]);
    header("Location: index.php"); exit;
}

// ---- Save Product ----
if (isset($_POST["save_product"])) {
    Product::saveProduct($_POST["name"], $_POST["price"], $_POST["mfg_id"]);
    header("Location: index.php"); exit;
}


if (isset($_POST["update_product"])) {
    Product::updateProduct(
        $_POST["id"], $_POST["name"], $_POST["price"], $_POST["mfg_id"]
    );
    header("Location: index.php"); exit;
}

if ($page == "delete_product") {
    Product::deleteProduct($_GET["id"]);
    header("Location: index.php"); exit;
}

// ---- Delete Manufacturer ----
if (isset($_POST["delete_mfg"])) {
    Product::deleteMfg($_POST["mfg_id"]);
    header("Location: index.php"); exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>My Project</title></head>
<body>

<!-- ============ NAV LINKS ============ -->
<p>
    <a href="index.php?page=add_product">Add Product</a> |
    <a href="index.php?page=add_mfg">Add Manufacturer</a> |
    <a href="index.php?page=delete_mfg">Delete Manufacturer</a>
</p>

<hr>

<?php
// ============ Add Product ============
if ($page == "add_product") { ?>
    <h3>Add Product</h3>
    <form method="post">
        Manufacturer:
        <select name="mfg_id" required>
            <option value="">-- select --</option>
            <?php Product::dropdownMfg(); ?>
        </select><br><br>
        Name: <input type="text" name="name" required><br><br>
        Price: <input type="text" name="price" required><br><br>
        <button type="submit" name="save_product">Save</button>
    </form>

<?php }

// ============ Edit Product ============
elseif ($page == "edit_product") {
    $p = Product::getProduct($_GET["id"]);
    if ($p) { ?>
        <h3>Edit Product</h3>
        <form method="post">
            <input type="hidden" name="id" value="<?= $p->id ?>">
            Manufacturer:
            <select name="mfg_id" required>
                <?php Product::dropdownMfg($p->manufacturer_id); ?>
            </select><br><br>
            Name: <input type="text" name="name" value="<?= htmlspecialchars($p->name) ?>" required><br><br>
            Price: <input type="text" name="price" value="<?= $p->price ?>" required><br><br>
            <button type="submit" name="update_product">Update</button>
        </form>
    <?php } else { echo "<p>Product not found</p>"; }
}

// ============ Add Manufacturer ============
elseif ($page == "add_mfg") { ?>
    <h3>Add Manufacturer</h3>
    <form method="post">
        Name: <input type="text" name="name" required><br><br>
        Contact: <input type="text" name="contact" required><br><br>
        <button type="submit" name="save_mfg">Save</button>
    </form>

<?php }

// ============ Delete Manufacturer ============
elseif ($page == "delete_mfg") { ?>
    <h3>Delete Manufacturer</h3>
    <form method="post" onsubmit="return confirm('Delete this manufacturer and all its products?')">
        <select name="mfg_id" required>
            <option value="">-- select --</option>
            <?php Product::dropdownMfg(); ?>
        </select>
        <button type="submit" name="delete_mfg">Delete</button>
    </form>

<?php }


else { ?>

    <h3>All Products</h3>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Manufacturer</th>
            <th>Action</th>
        </tr>
        <?php Product::index(); ?>
    </table>

<?php } ?>

</body>
</html>