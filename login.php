<?php
session_start();

// Kết nối đến cơ sở dữ liệu
$db = new PDO('mysql:host=localhost;dbname=ql_nhansu', 'root', '');

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (isset($_SESSION['username'])) {
    // Người dùng đã đăng nhập, chuyển hướng đến trang thêm nhân viên
    header("Location: addnv.php");
    exit;
}

// Xử lý đăng nhập khi người dùng gửi form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kiểm tra trong cơ sở dữ liệu xem username và password có tồn tại hay không
    $query = "SELECT * FROM table_name WHERE username = :username AND password = :password AND role = 'admin'";
    $stmt = $db->prepare($query);
    $stmt->execute(array(':username' => $username, ':password' => $password));
    $count = $stmt->rowCount();

    if ($count == 1) {
        // Đăng nhập thành công, lưu thông tin người dùng vào session và chuyển hướng đến trang thêm nhân viên
        $_SESSION['username'] = $username;
        header("Location: addnv.php");
        exit;
    } else {
        // Đăng nhập thất bại, hiển thị thông báo lỗi
        $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
</head>
<body>
    <h2>Đăng nhập</h2>
    <?php if (isset($error)) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>
    <form method="post" action="">
        <label for="username">Tên đăng nhập:</label><br>
        <input type="text" id="username" name="username"><br>
        <label for="password">Mật khẩu:</label><br>
        <input type="password" id="password" name="password"><br><br>
        <input type="submit" name="submit" value="Đăng nhập"> <!-- Thêm name cho nút submit -->
    </form>
</body>
</html>
