<?php
require_once('funzioni/database.php');
session_start();
$pdo = $databaseConnection->getPdo();
$sql="SELECT * FROM messaggio,utente WHERE email_mittente=:email AND email=email_destinatario";
$sql2="SELECT * FROM messaggio,utente WHERE email_destinatario=:email AND email=email_mittente";
$stmt = $pdo -> prepare($sql);
$stmt -> bindParam(':email',$_SESSION['email'],PDO::PARAM_STR);
$stmt -> execute();
$resultSent = $stmt->fetchAll();
$stmt = $pdo -> prepare($sql2);
$stmt -> bindParam(':email',$_SESSION['email'],PDO::PARAM_STR);
$stmt -> execute();
$resultReceived = $stmt->fetchAll();
?>
<h2 class="flow-text" style="text-align:center;">Casella di posta</h2>
<div style="text-align:center;">
	<button onclick="sent()" class="btn">Inviati <span class="badge" style="color:white;"><?php echo count($resultSent); ?></span></button>
	<button onclick="received()" class="btn">Ricevuti <span class="badge" style="color:white;"><?php  echo count($resultReceived); ?></span></button>
</div>
<?php
	if(count($resultReceived)):
?>
<div style="max-height:550px;overflow-y:auto;">
<ul class="collection" id="received" hidden>
<?php
	for($i=0;$i<count($resultReceived);$i++){
?>
<li class="collection-item" style="height:100%;">
  <img src="/images/<?php echo $resultReceived[$i][10];?>" alt="Immagine del profilo" class="circle">
  <span class="title">Da:<?php echo $resultReceived[$i][8].' '.$resultReceived[$i][9].' (';?><a name="<?php echo $resultReceived[$i][1]; ?>" onclick="profilo(this)" style="text-decoration:underline;color:blue;cursor:pointer;"><?php echo $resultReceived[$i][1]; ?></a>)</span>
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
  <img src="/images/<?php echo $resultSent[$i][10];?>" alt="Immagine del profilo" class="circle">
   <span class="title">A:<?php echo $resultSent[$i][8].' '.$resultSent[$i][9].' (';?><a name="<?php echo $resultSent[$i][5]; ?>" onclick="profilo(this)" style="text-decoration:underline;color:blue;cursor:pointer;"><?php echo $resultSent[$i][5]; ?></a>)</span>
  <div class="secondary-content">
  <p><?php echo substr($resultSent[$i][4],0,16);?></p>
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
var profilo = function(elmnt){
  var emailSearch = $(elmnt).attr('name');
  $.ajax({
    url:"../profiloSearch.php",
    type:"POST",
    data:{email:emailSearch},
    success: function(data){
      $('#content-target').empty();
      $('#content-target').append(data);
    },
    error:function(data){
      alertify.alert("Impossibile caricare i dati del profilo. Riprovare.");
    }
  }); 
};
var sent = function(){
	$('#received').hide();
	$('#sent').show();
};
var received = function(){
	$('#sent').hide();
	$('#received').show();
};
</script>