<?php
   include ("config.php");   
   $id=$_GET["id"];   
   if (is_numeric($id)) {
						try {
							$conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
							// set the PDO error mode to exception
							$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							//echo "<b>Connected successfully</b><br/>";
							
							$sql = "select Titel, Problem, Bild, DATE_FORMAT(timestamp, '%d.%m.%Y') AS datum, lat, lng from stellen where declined=0 and id=".$id;
							
								foreach ($conn->query($sql) as $row) {
											$titel=$row['Titel'];
											$problem=$row['Problem'];	
											$bild=$row['Bild'];	
											$datum=$row['datum'];
											$lat=$row['lat'];
											$lng=$row['lng'];
								}
   																					
   							}
								catch(PDOException $e){
   											echo "Connection failed: " . $e->getMessage();
   								}								
   								
								
	if ($bild=="") {
		$bildanzeige='';		
		$ogbild='<meta property="og:image" content="'.$sitepath.'img/shareimage.jpg"/>';
	}
	else{
		$bildanzeige='<img id="foto" src="../upload/'.$bild.'"/>';
		$ogbild='<meta property="og:image" content="'.$fulluploadpath.''.$bild.'"/>';
	};
	
	
	echo ('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
			'.$ogbild.'	
			<meta property="og:type" content="article" />
			<meta property="og:title" content="'.$sitetitle.' - '.$titel.'" />
			<meta property="og:description" content="'.$problem.'" />
			<meta name="description" content="'.$problem.'" />
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<title>ADFC Radwegmelder - '.$titel.'</title>

			  <!-- leaflet.css und leaflet.js von externer Quelle einbinden -->
			  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" />
			  <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"></script>
			  <style type="text/css">  
			

				

				#inhalt {
					display: table;
					margin: 0 auto;
					border: 1px solid orange;
					height: 50%;
					width: 75%;
					font-family: sans-serif;
					padding: 25px 25px 25px 25px;
					}
				
				#titel {					
					padding: 25px 25px 25px 25px;
					} 
					
				#meineKarte {
				 height: 500px; 
					}
					
					#foto {
					    width:100%;
						max-width:600px;
						object-fit: cover;
						overflow: hidden;
				}
					
				</style>

		</head>
		<body id="main_body" > 	
			<div id="titel">
			<center><a href="'.$sitepath.'"><img src="../img/logo.png" width="50%" /></a></center>
			</div>
			<div id="inhalt"> 

				<div class="row">
				  <div class="column">				
					<h2>'.$datum.' '.$titel.'</h2>
					<p>'.$problem.'<br/><br/></p>
					<p>
					<a href="'.$sitepath.'">Eigene Meldung im '.$sitetitle.' verfassen</a>	
					</p>
				  </div>
				  <div class="row">
					'.$bildanzeige.'				
				  </div>
				</div>
				<br/>
				<div id="meineKarte" style="background-color:#bbb;">				
				<script type="text/javascript">
				var Karte = L.map("meineKarte", {
				preferCanvas: true
				}).setView(['.$lat.', '.$lng.'], 16);
				L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
				"attribution": "Kartendaten &copy; <a href=https://www.openstreetmap.org/copyright>OpenStreetMap</a> Mitwirkende",
				"useCache": true
				}).addTo(Karte);
				var circleMarker = L.circleMarker(['.$lat.','.$lng.'], {color: "#FF0000",radius:10}).addTo(Karte);
				
				</script>			
				</div>
				<br/>
				<a href="'.$impressumlink.'">Impressum</a> | <a href="'.$datenschutzlink.'">Datenschutz</a> | <a href="'.$sitepath.'">Eigene Meldung im '.$sitetitle.' verfassen</a>				
			</div>
			
		 
		</body>
	</html>   
	');   		
	
	}   
   ?>