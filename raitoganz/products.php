<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission for adding products
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $customer_id = $_POST['customer_id']; // Get the selected customer ID

    // Insert the new product with the foreign key
    $query = "INSERT INTO products (product_name, price, stock, customer_id) VALUES ('$product_name', '$price', '$stock', '$customer_id')";
    if (mysqli_query($conn, $query)) {
        $success = "Product added successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Fetch existing products
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);

// Fetch customers for the dropdown
$customer_query = "SELECT customer_id, customer_name FROM customers";
$customer_result = mysqli_query($conn, $customer_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        header { background: black; color: white; padding: 15px; text-align: center; }
        nav a { color: white; text-decoration: none; margin: 0 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background-color: black; color: white; }
        form { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        input, select { width: calc(100% - 22px); padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { background: #28a745; color: white; border: none; padding: 10px; cursor: pointer; border-radius: 5px; }
        button:hover { background: #218838; }
    </style>
</head>
<body>
    <header>
        <h1>Products Management</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="products.php">Products</a>
            <a href="customers.php">Customers</a>
            <a class="logout" href="logout.php">Logout</a>
        </nav>
    </header>
    
    <main>
        <h2>Add New Product</h2>
        <form method="POST" action="">
            <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <input type="text" name="product_name" placeholder="Product Name" >
            <input type="number" name="price" placeholder="Price" step="0.01" >
            <input type="number" name="stock" placeholder="Quantity" >
            <select name="customer_id" required>
                <option value="">Select Customer</option>
                <?php while ($customer_row = mysqli_fetch_assoc($customer_result)): ?>
                    <option value="<?php echo htmlspecialchars($customer_row['customer_id']); ?>">
                        <?php echo htmlspecialchars($customer_row['customer_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Add Product</button>
        </form>

        <h2>Existing Products</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Customer ID</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td><?php echo htmlspecialchars($row['price']); ?></td>
                <td><?php echo htmlspecialchars($row['stock']); ?></td>
                <td><?php echo htmlspecialchars($row['customer_id']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </main>
</body>
</html>