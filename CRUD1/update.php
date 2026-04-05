<?php
$conn = mysqli_connect("localhost", "root", "geographypsu", "gisweb69");
mysqli_set_charset($conn,"utf8");
$id = $_GET['id'];
$res = mysqli_query($conn, "SELECT * FROM satuntour WHERE id = $id");
$data = mysqli_fetch_assoc($res);

// เธชเนเธง
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $description = $_POST['description'];
    $typeplace = $_POST['typeplace'];
    $ownplace = $_POST['ownplace'];
    
    // เธเธฑเ
    function handleUpdateFile($fileInput, $oldFile) {
        if ($_FILES[$fileInput]['error'] === 0) {
            $pathInfo = pathinfo($_FILES[$fileInput]['name']);
            $newName = preg_replace("/[^a-zA-Z0-9เธ-เน_-]/u", "_", $pathInfo['filename']) . "_" . time() . "." . $pathInfo['extension'];
            move_uploaded_file($_FILES[$fileInput]['tmp_name'], "uploads/" . $newName);
            return $newName;
        }
        return $oldFile;
    }

    $photoName = handleUpdateFile('photo', $data['photo']);

    $sql = "UPDATE satuntour SET name=?, latitude=?, longitude=?, photo=?, descript=?, typeplace=?, ownplace=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssi", $name, $latitude, $longitude, $photoName, $description, $typeplace, $ownplace, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('เเก้ไขข้อมูลสำเร็จ'); window.location='upload.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>การปรับปรุงแก้ไขข้อมูล  </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>#map { height: 350px; border-radius: 10px; margin-bottom: 15px; }</style>
</head>
<body class="bg-light">
    <div class="container mt-4 mb-5">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark"><h4>เเก้ไขข้อมูล <?php echo $data['name']; ?></h4></div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div id="map"></div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ละติจูด</label>
                            <input type="text" name="latitude" id="lat" class="form-control" value="<?php echo $data['latitude']; ?>" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ลองจิจูด</label>
                            <input type="text" name="longitude" id="lng" class="form-control" value="<?php echo $data['longitude']; ?>" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ชื่อสถานที่ท่องเที่ยว</label>
                        <input type="text" name="name" class="form-control" value="<?php echo $data['name']; ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ประเภทสถานที่ท่องเที่ยว </label>
                            <input type="text" name="typeplace" class="form-control" value="<?php echo $data['typeplace']; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">หน่วยงานที่ดูเเลสถานที่ท่องเที่ยว </label>
                            <input type="text" name="ownplace" class="form-control" value="<?php echo $data['ownplace']; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">เเทรกรูปภาพ <?php echo $data['photo']; ?></label>
                            <input type="file" name="photo" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">สาระสังเขป/คำอธิบายสถานที่</label>
                        <textarea name="description" class="form-control" rows="3"><?php echo $data['descript']; ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" name="update" class="btn btn-success flex-grow-1">ยืนยันการเเก้ไขข้อมูล</button>
                        <a href="upload.php" class="btn btn-secondary">กลับไปตารางสถานที่ท่องเที่ยว</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5l0aPyGFqiqY5DpqOYf-8YhN_eYHHobU&callback=initMap" async defer></script>
    <script>
        function initMap() {
            const oldPos = { lat: <?php echo $data['latitude']; ?>, lng: <?php echo $data['longitude']; ?> };
            const map = new google.maps.Map(document.getElementById("map"), { zoom: 15, center: oldPos });
            let marker = new google.maps.Marker({ position: oldPos, map: map, draggable: true });

            // เน€เธกเธทเนเธญเธเธฅเธดเธเนเธ
            map.addListener("click", (e) => {
                const pos = e.latlng ? e.latlng : e.latLng;
                marker.setPosition(pos);
                document.getElementById("lat").value = pos.lat().toFixed(7);
                document.getElementById("lng").value = pos.lng().toFixed(7);
            });

            // เน€เธกเธทเนเธญเธฅเธฒเธเธซเธกเธธเธ”เนเธเธงเธฒเธ
            marker.addListener("dragend", (e) => {
                document.getElementById("lat").value = e.latLng.lat().toFixed(7);
                document.getElementById("lng").value = e.latLng.lng().toFixed(7);
            });
        }
    </script>
</body>
</html>
 