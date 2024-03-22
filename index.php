<?php
// Kết nối đến cơ sở dữ liệu
$db = new PDO('mysql:host=localhost;dbname=ql_nhansu', 'root', '');

// Xác định trang hiện tại
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Số lượng bản ghi trên mỗi trang
$records_per_page = 5;

// Tính offset (vị trí bắt đầu của bản ghi trên trang hiện tại)
$offset = ($current_page - 1) * $records_per_page;

// Lấy dữ liệu nhân viên cho trang hiện tại
$sql = "SELECT * FROM nhanvien LIMIT $offset, $records_per_page";
$stmt = $db->prepare($sql);
$stmt->execute();
$nhanvien = $stmt->fetchAll();

// Lấy tổng số nhân viên
$total_records_sql = "SELECT COUNT(*) AS total FROM nhanvien";
$stmt = $db->query($total_records_sql);
$total_records = $stmt->fetch()['total'];

// Tính tổng số trang
$total_pages = ceil($total_records / $records_per_page);

?>
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin nhân viên</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
        }
        .gender-icon {
            width: 20px; /* Điều chỉnh kích thước của icon nếu cần */
            height: auto;
        }
    </style>
</head>
<body>

    <h1>Thông tin nhân viên</h1>
    <!-- Nút Đăng nhập -->
    <a href="login.php">Đăng nhập</a>
    <table>
        <tr>
            <th>Mã nhân viên</th>
            <th>Tên nhân viên</th>
            <th>Giới tính</th>
            <th>Nơi sinh</th>
            <th>Tên phòng</th>
            <th>Lương</th>
        </tr>
        <?php foreach ($nhanvien as $nv) { ?>
            <tr>
                <td><?php echo $nv['Ma_NV']; ?></td>
                <td><?php echo $nv['Ten_NV']; ?></td>
                <td>
                    <?php if ($nv['Phai'] == 'NAM' ) { ?>
                        <img src="./image/man.png" alt="Nam" class="gender-icon">
                    <?php } else { ?>
                        <img src="./image/woman.png" alt="Nữ" class="gender-icon">
                    <?php } ?>
                </td>
                <td><?php echo $nv['Noi_Sinh']; ?></td>
                <td><?php echo $nv['Ma_Phong']; ?></td>
                <td><?php echo number_format($nv['Luong'], 0, ',', '.'); ?></td>
                <td> <!-- Thêm cột thao tác -->
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?> 
                        <a href="edit.php?id=<?php echo $nv['Ma_NV']; ?>"><img src="./image/edit.png" alt="Edit"></a> <!-- Button edit -->
                        <a href="delete.php?id=<?php echo $nv['Ma_NV']; ?>"><img src="./image/delete.png" alt="Delete"></a> <!-- Button delete -->
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
    <!-- Hiển thị liên kết phân trang -->
    <div style="margin-top: 20px;">
        Trang:
        <?php for($i = 1; $i <= $total_pages; $i++) { ?>
            <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php } ?>
    </div>
    <!-- Thêm nút "Thêm nhân viên" -->
    <a href="addnv.php" style="display: block; margin-top: 20px;">Thêm nhân viên</a>
</body>
</html>
