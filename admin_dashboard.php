<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todo_list";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}


if (isset($_GET['delete'])) {
    $username = $_GET['delete'];
    $deleteSql = "DELETE FROM users WHERE username = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("s", $username);
    $deleteStmt->execute();
    $deleteStmt->close();
}


if (isset($_POST['edit_user'])) {
    $oldUsername = $_POST['old_username']; 
    $newUsername = $_POST['username']; 
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $updateSql = "UPDATE users SET username = ?, email = ?, password = ? WHERE username = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ssss", $newUsername, $email, $hashedPassword, $oldUsername);
        $updateStmt->execute();
        $updateStmt->close();
    } else {
        $updateSql = "UPDATE users SET username = ?, email = ? WHERE username = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("sss", $newUsername, $email, $oldUsername);
        $updateStmt->execute();
        $updateStmt->close();
    }

    header("Location: admin_dashboard.php");
    exit();
}


$sql = "SELECT * FROM users";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Dashboard Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="admin_dashboard.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
    <div class="wrapper">
        <div class="content">
        <div class="header">Dashboard <span style="color: red;">Admin</span></div>
        <h2 style="color: white;">Manage <span style="color: red;">User  </span> Accounts</h2>

        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <a style="color: white;" href="?username=<?php echo urlencode($user['username']); ?>"><i data-feather="edit"></i></a>
                            <span style="border-top: 1px solid white; width: 100%; height: 0; margin: 10px 0;"></span> 
                            <a style="color: red;" href="#" onclick="openModal('<?php echo urlencode($user['username']); ?>')"><i data-feather="user-x"></i></a </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Confirm Deleting</h2>
                <p>Are you sure you want to delete this user?</p>
                <button class="modal-button" id="confirmDelete">Yes, Delete</button>
                <button class="modal-button cancel" onclick="closeModal()">Cancel</button>
            </div>
        </div>

        <?php if (isset($_GET['username'])): ?>
            <?php
            $username = $_GET['username'];
            $editSql = "SELECT * FROM users WHERE username = ?";
            $editStmt = $conn->prepare($editSql);
            $editStmt->bind_param("s", $username);
            $editStmt->execute();
            $editResult = $editStmt->get_result();
            $editUser    = $editResult->fetch_assoc();
            ?>
           
<div class="floating-alert">
    <h2 class="edit-user-title">Edit <span style="color: red;">User    </span> Account</h2>
    <form method="POST" class="edit-user-form">
        <input type="hidden" name="old_username" value="<?php echo htmlspecialchars($editUser   ['username']); ?>">
        <div class="form-group">
            <label for="username" class="form-label">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($editUser   ['username']); ?>" required class="form-input">
        </div>
        <div class="form-group">
            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($editUser   ['email']); ?>" required class="form-input">
        </div>
        <button type="submit" name="edit_user" class="update-button">Update User</button>
        <span class="close-alert" onclick="closeAlert()">×</span> 
    </form>
</div>

<script>
    </script>
        <?php endif; ?>
        
        <div class="logout-container">
            <a href="index.php" class="logout-button">Logout</a>
        </div>
    </div>
    
    <script>
        function closeAlert() {
            document.querySelector('.floating-alert').style.display = 'none';
        }
        function openModal(username) {
            document.getElementById('deleteModal').style.display = 'block';
            document.getElementById('confirmDelete').onclick = function() {
                window.location.href = '?delete=' + username;
            };
        }

        function closeModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('deleteModal')) {
                closeModal();
            }
        }
        feather.replace();
    </script>
</body>
</html>
<?php
$conn->close();
?>