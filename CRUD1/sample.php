<?php
$conn = mysqli_connect("localhost", "root", "geographypsu", "gisweb69");
mysqli_set_charset($conn,"utf8");
if (!$conn) { die("การเชื่อมต้อล้มเหลว" . mysqli_connect_error()); }

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $description = $_POST['description'];
    $typeplace = $_POST['typeplace'];
    $ownplace = $_POST['ownplace'];

    if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }

    function uploadFileWithOriginalName($fileInput) {
        if (!isset($_FILES[$fileInput]) || $_FILES[$fileInput]['error'] !== 0) return null;

        $originalName = $_FILES[$fileInput]['name'];
        $fileTmp = $_FILES[$fileInput]['tmp_name'];
        
        $pathInfo = pathinfo($originalName);
        $filenameOnly = $pathInfo['filename'];
        $extension = $pathInfo['extension'];

        $cleanName = preg_replace("/[^a-zA-Z0-9เธ-เน_-]/u", "_", $filenameOnly);

        $newName = $cleanName . "_" . time() . "_" . rand(10, 99) . "." . $extension;
        $dest = "uploads/" . $newName;

        if (move_uploaded_file($fileTmp, $dest)) {
            return $newName;
        }
        return null;
    }

    $photoName = uploadFileWithOriginalName('photo');

    // บันทึกรายการลงฐานข้อมูล
    $sql = "INSERT INTO satuntour (name, latitude, longitude, photo, descript, ownplace, typeplace ) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssss", $name, $latitude, $longitude, $photoName, $description, $ownplace, $typeplace, );

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('บันทึกข้อมูลสำเร็จเเล้ว'); window.location='upload.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล!: " . mysqli_error($conn);
    }
}
?>