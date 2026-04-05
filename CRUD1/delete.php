<?php
$conn = mysqli_connect("localhost", "root", "geographypsu", "gisweb69");
mysqli_set_charset($conn,"utf8");
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // เธเนเธญเธเธเธฑเธ SQเธงเธขเธเธฒเธฃเนเธเธฅเธเน€เธเนเธเน€เธฅเธเธเธณเธเธงเธเน€เธ•เนเธก

    // 1. เธ”เธถเธเธเธทเนเธเธฅเธเนเธเธฅเนเนเธเนเธเธฅเน€เธ”เธญเธฃเน
    $query = "SELECT photo FROM satuntour WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    if ($data) {
        if (!empty($data['photo'])) {
            $photoPath = "uploads/" . $data['photo'];
            if (file_exists($photoPath)) {
                unlink($photoPath); // เธเธณเธชเธฑเนเธเธฅเธเนเธเธฅเนเธเธฃเธดเธ
            }
        }

        // 2. ยืนยันการลบข้อมูล
        $sql_delete = "DELETE FROM satuntour WHERE id = $id";
        if (mysqli_query($conn, $sql_delete)) {
            echo "<script>
                    alert('ลบข้อมูลสำเร็จ');
                    window.location='upload.php';
                  </script>";
        } else {
            echo "เน€เธเธดเธ”เธเนเเธเนเธญเธกเธนเธฅ: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('เนเธกเนเธเธฒเธฃเธฅเธ'); window.location='upload.php';</script>";
    }
} else {
    header("Location: upload.php");
}

mysqli_close($conn);
?>