<?php
$dsn="mysql:host=localhost;dbname=webuiplus";
$username="root";
$passwd="westWIN2020";

try {
	$pdo=new PDO($dsn,$username,$passwd);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "成功连接到数据库";
} catch (PDOException $e) {
    echo "连接数据库失败: " . $e->getMessage();
}

	
$query = "show status;";
$stmt = $pdo->prepare($query);
$stmt->execute();
 
while ($row = $stmt->fetch()) {
    echo $row[0] . ": " .$row[1] . "<br>";
}



?>
