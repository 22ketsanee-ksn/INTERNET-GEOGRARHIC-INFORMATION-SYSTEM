<?php
$conn = mysqli_connect("localhost","root","geographypsu","gisweb69");
mysqli_set_charset($conn,"utf8");

$result = mysqli_query($conn,"SELECT * FROM satuntour ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>Web GIS แผนที่ + ตาราง</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg,#667eea,#764ba2);
}

/* MAP */
#map {
    height: 450px;
    border-radius: 15px;
    margin-bottom: 20px;
}

/* TABLE */
.table-hover tbody tr:hover {
    background: #f1f5ff;
    cursor: pointer;
}

/* CARD */
.card {
    border-radius: 15px;
}
</style>
</head>

<body>

<div class="container mt-4 mb-5">

<h3 class="text-center text-white mb-4">🌍 Web GIS สถานที่ท่องเที่ยว</h3>
<div class="d-flex justify-content-between align-items-center mb-4 title-bar">
    <a href="upload.php" class="btn btn-light fw-bold"> 🧭ย้อนกลับ</a>
</div>
<!-- MAP -->
<div class="card shadow mb-4">
<div class="card-body">
<div id="map"></div>
</div>
</div>

<!-- TABLE -->
<div class="card shadow">
<div class="card-header bg-dark text-white">📊 ตารางข้อมูล</div>
<div class="card-body table-responsive">

<table class="table table-hover text-center align-middle">
<thead class="table-primary">
<tr>
<th>ชื่อ</th>
<th>รายละเอียด</th>
<th>Latitude</th>
<th>Longitude</th>
<th>ประเภท</th>
</tr>
</thead>

<tbody>
<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr onclick="goToMap(<?php echo $row['latitude']; ?>,<?php echo $row['longitude']; ?>)">
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['descript']; ?></td>
<td><?php echo $row['latitude']; ?></td>
<td><?php echo $row['longitude']; ?></td>
<td><?php echo $row['typeplace']; ?></td>
</tr>
<?php } ?>
</tbody>

</table>

</div>
</div>

</div>

<script>
let map;

// 📌 สร้าง Map
function initMap(){

    const start = {lat:6.72,lng:100.07};

    map = new google.maps.Map(document.getElementById("map"),{
        zoom:10,
        center:start
    });

    // 📍 ดึงข้อมูลจาก PHP ไป JS
    let locations = [
    <?php
    $res = mysqli_query($conn,"SELECT * FROM satuntour");
    while($r=mysqli_fetch_assoc($res)){
        echo "['".$r['name']."',".$r['latitude'].",".$r['longitude']."],";
    }
    ?>
    ];

    // 📍 แสดง marker
    locations.forEach(function(loc){
        new google.maps.Marker({
            position:{lat:parseFloat(loc[1]),lng:parseFloat(loc[2])},
            map:map,
            title:loc[0]
        });
    });

}

// 🎯 คลิกตาราง → ไปตำแหน่ง
function goToMap(lat,lng){
    const position = {lat:parseFloat(lat),lng:parseFloat(lng)};

    map.setCenter(position);
    map.setZoom(15);

    new google.maps.Marker({
        position:position,
        map:map,
        animation:google.maps.Animation.BOUNCE
    });
}
</script>

<!-- 🔥 ใส่ API KEY -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5l0aPyGFqiqY5DpqOYf-8YhN_eYHHobU&callback=initMap" async defer></script>

</body>
</html>