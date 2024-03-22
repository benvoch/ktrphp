<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['username'])) {
    // Người dùng chưa đăng nhập, chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit;
}

// Kiểm tra xem người dùng có quyền admin hay không
// Trong trường hợp này, chúng ta đã kiểm tra quyền admin trong quá trình đăng nhập, nên không cần kiểm tra lại ở đây.

// Kết nối đến cơ sở dữ liệu
$db = new PDO('mysql:host=localhost;dbname=ql_nhansu', 'root', '');

// Xử lý khi người dùng gửi form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nhận dữ liệu từ form
    $maNV = $_POST['maNV'];
    $tenNV = $_POST['tenNV'];
    $phai = $_POST['phai'];
    $noiSinh = $_POST['noiSinh'];
    $maPhong = $_POST['maPhong'];
    $luong = $_POST['luong'];

    // Thêm dữ liệu vào cơ sở dữ liệu
    $sql = "INSERT INTO nhanvien (Ma_NV, Ten_NV, Phai, Noi_Sinh, Ma_Phong, Luong) VALUES (:maNV, :tenNV, :phai, :noiSinh, :maPhong, :luong)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':maNV', $maNV);
    $stmt->bindParam(':tenNV', $tenNV);
    $stmt->bindParam(':phai', $phai);
    $stmt->bindParam(':noiSinh', $noiSinh);
    $stmt->bindParam(':maPhong', $maPhong);
    $stmt->bindParam(':luong', $luong);
    
    if ($stmt->execute()) {
        // Chuyển hướng về trang danh sách nhân viên sau khi thêm thành công
        header("Location: ./index.php");
        exit;
    } else {
        // Xử lý lỗi khi thêm vào cơ sở dữ liệu
        $error = "Có lỗi xảy ra. Vui lòng thử lại sau.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm nhân viên</title>
</head>
<body>
    <h2>Thêm nhân viên</h2>
    <?php if (isset($error)) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>
    <form method="post" action="">
        <label for="maNV">Mã nhân viên:</label><br>
        <input type="text" id="maNV" name="maNV"><br>
        <label for="tenNV">Tên nhân viên:</label><br>
        <input type="text" id="tenNV" name="tenNV"><br>
        <label for="phai">Giới tính:</label><br>
        <select id="phai" name="phai">
            <option value="NAM">Nam</option>
            <option value="NU">Nữ</option>
        </select><br>
        <label for="noiSinh">Nơi sinh:</label><br>
        <input type="text" id="noiSinh" name="noiSinh"><br>
        <label for="maPhong">Mã phòng:</label><br>
        <input type="text" id="maPhong" name="maPhong"><br>
        <label for="luong">Lương:</label><br>
        <input type="text" id="luong" name="luong"><br><br>
        <input type="submit" value="Thêm">
    </form>
</body>
</html>
