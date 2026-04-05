<?php
$conn = mysqli_connect("localhost", "root", "geographypsu", "gisweb69");
mysqli_set_charset($conn,"utf8");
$sql = "SELECT * FROM satuntour ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ตารางสถานที่ท่องเที่ยว</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg,#667eea,#764ba2);
    min-height: 100vh;
}

/* Header */
.title-bar {
    background: linear-gradient(45deg,#ff6a00,#ee0979);
    padding: 15px;
    border-radius: 12px;
    color: white;
}

/* Card */
.table-card {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

/* Table Header */
.table thead {
    background: linear-gradient(45deg,#00c6ff,#0072ff);
    color: white;
}

.btn-gradient {
   background-color: #f4f4f6;
    color: black;
    border: none;
    border-radius: 30px;
    padding: 10px 20px;
    transition: 0.3s;
    font-size: 15px;
}

.btn-gradient:hover {
    background: linear-gradient(45deg,#ff6a00,#ee0979);
    color: #181616;
    transform: scale(1.05);
}

/* Table Hover */
.table-hover tbody tr:hover {
    background-color: #f1f5ff;
    transform: scale(1.01);
    transition: 0.2s;
}

/* Image */
.img-thumb {
    width: 200px;
    border-radius: 10px;
    border: 2px solid #ddd;
}

/* Buttons */
.btn-warning {
    background: linear-gradient(45deg,#f7971e,#ffd200);
    border: none;
}

.btn-danger {
    background: linear-gradient(45deg,#ff416c,#ff4b2b);
    border: none;
}

.btn-primary {
    background: linear-gradient(45deg,#00c6ff,#0072ff);
    border: none;
}

/* Badge */
.badge {
    font-size: 13px;
    padding: 6px 10px;
    border-radius: 10px;
}

/* Card padding */
.card-body {
    background: #fff;
}
</style>
</head>

<body>

<div class="container mt-5">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4 title-bar">
    <h4 class="mb-0">🌍 ตารางสถานที่ท่องเที่ยว</h4>
    <a href="map_table.php" class="btn btn-gradient fw-bold shadow-sm" style="margin-left: 480px;">🗺️ ฐานข้อมูลร่วมกับแผนที่</a>
    <a href="index.html" class="btn btn-light fw-bold">➕ เพิ่มข้อมูล</a>
</div>

<!-- TABLE -->
<div class="table-card">
<div class="card-body table-responsive">

<table class="table table-hover align-middle text-center">

<thead>
<tr>
<th>รูปภาพ</th>
<th>ชื่อ</th>
<th>รายละเอียด</th>
<th>พิกัด</th>
<th>ประเภท</th>
<th>จัดการ</th>
</tr>
</thead>

<tbody>
<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>

<td>
<?php if($row['photo']) { ?>
<img src="uploads/<?php echo $row['photo']; ?>" class="img-thumb">
<?php } ?>
</td>

<td class="fw-bold text-primary"><?php echo $row['name']; ?></td>

<td style="max-width:400px;">
<?php echo $row['descript']; ?>
</td>

<td>
<span class="text-success"><?php echo $row['latitude']; ?></span><br>
<span class="text-danger"><?php echo $row['longitude']; ?></span>
</td>

<td>
<span class="badge bg-info text-dark"><?php echo $row['typeplace']; ?></span><br>
<small class="text-muted"><?php echo $row['ownplace']; ?></small>
</td>


<td>
<a href="update.php?id=<?php echo $row['id']; ?>" 
class="btn btn-warning btn-sm" onclick="return confirm('แก้ไขข้อมูล');">✏️</a>

<a href="delete.php?id=<?php echo $row['id']; ?>" 
class="btn btn-danger btn-sm"
onclick="return confirm('ยืนยันการลบข้อมูล');">🗑</a>
</td>

</tr>
<?php } ?>
</tbody>

</table>

</div>
</div>

</div>

</body>
</html>