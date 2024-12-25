<?php
session_start();

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "todo_list"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil jadwal notifikasi dari database
$current_time = date("Y-m-d H:i:s");
$next_hour = date("Y-m-d H:i:s", strtotime('+1 hour'));
$sql = "SELECT * FROM tasks WHERE completed='1'";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png" />
    <link rel="manifest" href="site.webmanifest" />
    <link rel="mask-icon" href="safari-pinned-tab.svg" color="#5bbad5" />
    <meta name="msapplication-TileColor" content="#2d89ef" />
    <meta name="theme-color" content="#ffffff" />
    <link rel="stylesheet" href="style.css">
    <title>Notifikasi</title>
</head>
<body>
    <div class="wrapper">
        <div class="screen-backdrop"></div>
        <div class="home-screen screen">
            <div class="head-wrapper">
                <div class="back-btn">
                    <a href="home.php">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                        </svg>
                    </a>
                </div>
                <h1>Notifikasi</h1>
            </div>
            <div class="notifications-wrapper">
                <div class="notifications">
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="notification-item">
                                <div class="content">
                                    <h1><?php echo htmlspecialchars($row['task']); ?></h1>
                                    <p><?php echo htmlspecialchars($row['category']); ?></p>
                                    <p><?php echo htmlspecialchars($row['completed']); ?></p>
                                    <p><?php echo htmlspecialchars($row['schedule_time']); ?></p>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="no-tasks">Tidak ada notifikasi saat ini.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
