<?php
include("config.php");

/* IP ermitteln*/
function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

$ipadresse=md5(getUserIpAddr());


$mysql = new mysqli($host, $user, $password, $database);
$mysql->set_charset("utf8");

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

$mysql->query("INSERT INTO votes (fk_stelle,ip_hash) VALUES ($id,'".$ipadresse."')");
//$query_text = "SELECT COUNT(*) AS supported FROM votes WHERE fk_stelle=$id GROUP BY fk_stelle;";
$query_text = "SELECT COUNT(*) AS supported FROM (select fk_stelle from votes where fk_stelle=$id group by ip_hash) as a WHERE fk_stelle=$id GROUP BY fk_stelle;";
//echo $query_text;
$query = $mysql->query($query_text);
$result=array();
$result['supported']=$query->fetch_assoc()['supported'];
echo json_encode($result);
?>
