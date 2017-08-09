<?php
require_once('database.php');
session_start();
$pdo = $databaseConnection->getPdo();
if(isset($_POST['email_user'])):
  $sql1= "SELECT nome,cognome FROM utente WHERE email=:email_profile";
$stmt = $pdo -> prepare($sql1);
  $stmt -> bindParam(':email_profile',$_POST['email_user'],PDO::PARAM_STR);
  $stmt -> execute();
  $resultUser = $stmt->fetchAll();
$sql="SELECT * FROM messaggio,utente WHERE email_mittente=:email AND email=email_destinatario AND email_destinatario=:email_profile";
$sql2="SELECT * FROM messaggio,utente WHERE email_destinatario=:email AND email=email_mittente AND email_mittente=:email_profile";
$stmt = $pdo -> prepare($sql);
$stmt -> bindParam(':email',$_SESSION['email'],PDO::PARAM_STR);
$stmt -> bindParam(':email_profile',$_POST['email_user'],PDO::PARAM_STR);
$stmt -> execute();
$resultSent = $stmt->fetchAll();
$stmt = $pdo -> prepare($sql2);
$stmt -> bindParam(':email',$_SESSION['email'],PDO::PARAM_STR);
$stmt -> bindParam(':email_profile',$_POST['email_user'],PDO::PARAM_STR);
$stmt -> execute();
$resultReceived = $stmt->fetchAll();
?>
<h2 class="flow-text message-title" name="<?php echo $resultUser[0][0]." ".$resultUser[0][1];?>" style="text-align:center;">Messaggi scambiati con <?php echo $resultUser[0][0]." ".$resultUser[0][1];?></h2>
<div style="text-align:center;">
	<button onclick="sent()" class="btn">Inviati <span class="badge" style="color:white;"><?php echo count($resultSent); ?></span></button>
	<button onclick="received()" class="btn">Ricevuti <span class="badge" style="color:white;"><?php  echo count($resultReceived); ?></span></button>
</div>
<?php
	if(count($resultReceived)):
?>
<div style="max-height:550px;overflow-y:auto;">
<ul class="collection" id="received">
<?php
	for($i=0;$i<count($resultReceived);$i++){
?>
<li class="collection-item" style="height:100%;">
  <div class="secondary-content">
  <p ><?php echo substr($resultReceived[$i][4],0,16);?></p>
</div>
  <p style="font-weight:bold;font-size:20px;">
  	Oggetto:
  	<?php echo $resultReceived[$i][2];?> 					        
  </p>
  <p>
  	Testo:
  	 <?php echo $resultReceived[$i][3];?>
  </p>
</li>

<?php
}
?>
</ul>
</div>
<?php
	endif;
?>
<?php
	if(count($resultSent)):
?>
<div style="max-height:550px;overflow-y:auto;">
<ul class="collection"  id="sent" hidden>
<?php
	for($i=0;$i<count($resultSent);$i++){
?>
<li class="collection-item avatar" style="height:100%;">
  
  <div class="secondary-content">
  <p ><?php echo substr($resultSent[$i][4],0,16);?></p>
</div>
  <p style="font-weight:bold;font-size:20px;">
  	Oggetto:
  	<?php echo $resultSent[$i][2];?> 					        
  </p>
  <p>Testo: <br>
  	 <?php echo $resultSent[$i][3];?>
  </p>
</li>

<?php
}
?>
</ul>
</div>
<?php
	endif;
?>
<script>
var sent = function(){
  $('.message-title').html("Messaggi inviati a "+$('.message-title').attr('name'));
	$('#received').hide();
	$('#sent').show();
};
var received = function(){
   $('.message-title').html("Messaggi ricevuti da "+$('.message-title').attr('name'));
	$('#sent').hide();
	$('#received').show();
};
</script>
<?php
else:
?>
<h2 class="flow-text red" style="text-align:center;">Errore nella ricezione dati</h2>
<?php
endif;
?>

