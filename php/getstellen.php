<?php
include("config.php");

$mysql = new mysqli($host, $user, $password, $database);
$mysql->set_charset("utf8");
$result=array();
$markers=array();

$query= $mysql->query("SELECT stellen.id, stellen.lat, stellen.lng, stellen.position_text, stellen.Titel, stellen.Problem, stellen.Loesung,stellen.Bild, stellen.Status, stellen.published, stellen.declined, DATE_FORMAT(stellen.timestamp, '%d.%m.%Y') AS timestamp, b.supported FROM stellen LEFT JOIN (SELECT fk_stelle AS id, COUNT(*) AS supported FROM (select fk_stelle,ip_hash from votes group by ip_hash,fk_stelle)as reduziert GROUP BY fk_stelle ORDER BY supported DESC) AS b ON b.id=stellen.id WHERE stellen.published=1 and stellen.declined=0");
//Order hat hier keine direkte Wirkung. Dies kommt daher, da irgendwo im JavaScript die ID als String interpretiert wird und dann z.B. 8, 80, 81 sortiert wird


$i=0;
while($t=$query->fetch_assoc()) {
  $markers[$i]=$t;
  ++$i;
}
$result['markers']=$markers;

echo json_encode($result);
?>
