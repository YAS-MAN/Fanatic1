<?php
$pageTitle = 'Dashboard';
$currentPage = 'dashboard';
include 'header.php';
?>

    <div class="page-banner" style="margin-bottom: 30px;">
        <h1>Dashboard Admin</h1>
    </div>
    <div class="card-container" style="padding: 0;">
        <div class="card clickable" onclick="window.location.href='cars.php'">
            <div class="card-content">
                <h3>Manage F1 Livery</h3>
                <p>Add, edit, or delete livery specification data.</p>
            </div>
        </div>
        <div class="card clickable" onclick="window.location.href='products.php'">
            <div class="card-content">
                <h3>Manage Store</h3>
                <p>Update merchandise prices, stock, and images.</p>
            </div>
        </div>
        <div class="card clickable" onclick="window.location.href='quizzes.php'">
            <div class="card-content">
                <h3>Manage Quiz</h3>
                <p>Add, edit, or delete questions for Quiz feature.</p>
            </div>
        </div>
    </div>

    <div class="center-boxes" style="margin-top:30px;">
        <a class="admin-box" href="messages.php">
            <h4>Manage Messages</h4>
            <p>Manage and respond to messages from users.</p>
        </a>
        <a class="admin-box" href="orders.php">
            <h4>Manage Orders</h4>
            <p>Manage order statuses and transaction details.</p>
        </a>
    </div>

<?php include 'footer.php'; ?>  
