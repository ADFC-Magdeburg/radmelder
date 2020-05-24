<?php
   include ("config.php");
   
   $id=$_GET["id"];
   
   	if (is_numeric($id)) {
   
   						try {
   											$conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
   											// set the PDO error mode to exception
   											$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   											//echo "<b>Connected successfully</b><br>";
   											
   											$sql = "select Titel, replace(replace(replace (Problem,'<','['),'>',']'),char(34),'´') as Problem from stellen where id=".$id;
   											
   												foreach ($conn->query($sql) as $row) {
   															$titel=$row['Titel'];
   															$problem=$row['Problem'];	
   												}
   																					
   							}
   										/**********************************************************************/
   										
   										catch(PDOException $e){
   											echo "Connection failed: " . $e->getMessage();
   								}
   									
   									
   
   						echo ('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
   						<html xmlns="http://www.w3.org/1999/xhtml">
   						   <head>
   							  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
   							  <title>Ergänzungsmeldung</title>
   							  <style type="text/css">
   							  
   								 #form {
   								 display: table;
   								 margin: 0 auto;
   								 border: 1px solid black;
   								 height: 90%;
   								 width: 75%;
   								 font-family: sans-serif;
   								 }
   								 #formrahmen {
   								 width: 100%
   								 }
   								 
   								 #ergaenzung {
   								 margin: 20px;
   								 }
   								 
   								 #originaltitel{
   								 width: 100%;		
   								 }
   								 
   								 #original, #ergaenzungstext{
   								 width: 100%;
   								 height: 100px;
   								 }
   								 
   								 label.description {
   									 font-weight:bold;
   									 
   								 }
   								 
   								 #email{
   								 width: 300px;		
   								 }
   									 
   								 
   							  </style>
   							  
   							  <script type="text/javascript">
   
   								
   								function freischalten() {
   								  var checkBox = document.getElementById("myCheck");
   								  var text = document.getElementById("sendenknopf");
   								  if (checkBox.checked == true){
   									text.style.display = "block";
   								  } else {
   									text.style.display = "none";
   								  }
   								}
   								
   								
   								
   								</script>
   						   </head>
   						   <body id="main_body" >    
   							  <div id="formrahmen">
   								 <div id="form">
   									<form id="ergaenzung"  method="post" action="ergaenzung_senden.php">
   									   <h2>Ergänzungsmeldung</h2>
   									   <p>Hier kannst du eine Ergänzung zu einem bereits gemeldeten Eintrag machen. Dieser wird ebenfalls redaktionell vor Veröffentlichung durch uns geprüft.</p>
   									   <label class="description" for="stellenid">ID</label><br/>
   									   
									   <input id="stellenid" name="stellenid" class="element text medium" type="text" maxlength="255" value="'.$id.'" disabled="disabled"/><br/><br/> 
   									   <input type="hidden" id="id" name="id" value="'.$id.'">
										
										
   									   <label class="description" for="originaltitel">Originaltitel</label><br/>          
   									   <input id="originaltitel" name="originaltitel" class="element text medium" type="text" maxlength="255" value="'.$titel.'" disabled="disabled"></input>	
									   <input type="hidden" id="otitel" name="otitel" value="'.$titel.'">
   									   <br/><br/> 
   									   
   									   <label class="description" for="original">Originalmeldung</label><br/>
   									   <textarea id="original" name="original" class="element textarea medium" disabled="disabled" rows="4" cols="200">'.$problem.'</textarea>
									   <input type="hidden" id="oproblem" name="oproblem" value="'.$problem.'">
   									   <br/><br/> 
   									   
   									   <label class="description" for="ergaenzungstext">Ergänzung </label><br/>
   									   <textarea id="ergaenzungstext" name="ergaenzungstext" class="element textarea medium" rows="4" cols="200"></textarea>
   									   <br/><br/> 
   									   
   									   <label class="description" for="email">E-Mail-Adresse </label><br/>
   									   <input id="email" name="email" class="element text medium" type="text" maxlength="255" value=""></input>			   
   									   <p class="guidelines" id="guide_2"><small>Für evtl. Rückfragen. Wird nicht veröffentlicht!</small></p>
   									   
   									   
   									   <label class="description" for="myCheck">Datenschutz</label><br/>
   										<span>              
   									   <input type="checkbox" id="myCheck" onclick="freischalten()"/>
   									   <label class="choice">Ich stimme der Speicherung meiner E-Mail-Adresse und der anonymisierten Veröffentlichung meiner Einreichung zu. Hinweis: Du kannst deine Einwilligung jederzeit für die Zukunft per E-Mail, Brief oder Telefon widerrufen. Nähere Informationen zum Thema Datenschutz findest du unter <a href="http://www.adfc-sachsenanhalt.de/impressum/" target="_blank">http://www.adfc-sachsenanhalt.de/impressum/</a></label><br/>
   									   </span>	   
   									   
   										<p id="sendenknopf" style="display:none"><input id="saveForm" class="button_text" type="submit" name="submit" value="Absenden" /></p>
   									   
   									</form>
   								 </div>
   							  </div>
   						   </body>
   						</html>
   
   						');
   						
   	}
   
   ?>