<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
	  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	  <title>Liste</title>
	  <style type="text/css">
<?php include 'liste.css'; ?>		 
	  </style>
   </head>
   <body id="main_body" >   
<?php
	include ("config.php");
	$ersetzung=array('/',' ');
	if (empty($_GET["status"])==false) {
	$status=$_GET["status"];   		
	$status_ersetzt=str_replace($ersetzung,"",$status);
	}
	try {
		$conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		echo "<center>";
		$sql_buttons = "select Status from stellen where published=1 and declined=0 group by Status";
			foreach ($conn->query($sql_buttons) as $row) {
				$status_db=$row['Status'];
				$status_db_ersetzt=str_replace($ersetzung,"",$status_db);
				echo '<a href="liste.php?status='.$status_db_ersetzt.'"><button class="btn">'.$status_db.'</button></a>';
			}
		echo "</center>";		
		if (empty($status)==false){
			$sql = "select id,Titel, Problem, Status,date_format(date(timestamp),'%d.%m.%Y') as datum, Bild from stellen where published=1 and declined=0 and replace(replace(Status,'/',''),' ','')='".$status_ersetzt."' order by timestamp desc";
			//echo '<br>'.$sql.'<br>';
			echo '<div class="container">
					<div class="centered-element">
					<br><br>
					<table><tr class="c0"><td>Datum</td><td>Titel</td><td>Bild</td><td>Problem</td></tr>';		
					$n=1;
			foreach ($conn->query($sql) as $row) {
						$id=$row['id'];
						$titel=$row['Titel'];
						$problem=$row['Problem'];	
						$datum=$row['datum'];			
						$bild=$row['Bild'];
						if (!empty($bild)) {
							$bild='<a href="../upload/'.$bild.'" target=_blank><img src="../upload/'.$bild.'" width=100%></a>';
						} else {
							$bild='';
						}
						echo '<tr class="c'.$n.'"><td style="white-space: nowrap;">'.$datum.'</td><td><a href="share.php?id='.$id.'" target=_blank>'.$titel.'</a></td><td>'.$bild.'</td><td>'.$problem.'</td></tr>';
						
						if ($n==1){						
							$n=2;							
							} else {						
							$n=1;
						}
			}
			echo '</table></div></div></body>';
		}   																					
	}  										
	catch(PDOException $e){
		echo "Connection failed: " . $e->getMessage();
	}
?>
</body>
</html>