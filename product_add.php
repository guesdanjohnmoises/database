<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Correct SQL (table and column names must match)
    $sql = "INSERT INTO products (name, price, stock) VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL ERROR: " . $conn->error); // This shows the real error
    }

    $stmt->bind_param("sdi", $name, $price, $stock);

    if ($stmt->execute()) {
        echo "<script>alert('Product added successfully!'); window.location='products.php';</script>";
    } else {
        echo "<script>alert('Failed to add product: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
</head>
<body>
    <h2>Add Product</h2>
    <form method="POST">
        <label>Product Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Price:</label><br>
        <input type="number" name="price" step="0.01" required><br><br>

        <label>Stock:</label><br>
        <input type="number" name="stock" required><br><br>

        <button type="submit">Save</button>
    </form>
</body>
</html>
