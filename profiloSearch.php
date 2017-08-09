<?php
require_once('funzioni/database.php');
session_start();
if(isset($_POST['email']))://post è un array devo verificare che contenga la var email
$email = $_POST['email'];
if($email == $_SESSION['email']):
?>
<script type="text/javascript">
$('#content-target').load('/profilo.php');
</script>
<?php
else :
$pdo = $databaseConnection -> getPdo();
$sql = "SELECT nome,cognome,email,foto,corso_laurea FROM utente,studente WHERE email=:email 
AND email_studente=email
UNION ALL
SELECT nome,cognome,email,foto,ruolo FROM utente,docente WHERE email=:email AND email_professore=email";
$stmt = $pdo-> prepare($sql);
$stmt -> bindParam(':email',$email,PDO::PARAM_STR);
$stmt -> execute();
$resultSearch = $stmt->fetchAll();
if(count($resultSearch)):
  ?>
<h2 class="flow-text" style= "text-align:center;">
Profilo
</h2>
<div class="card large blue-grey darken-1" style="height:353px;">
  <div class="card-image">
    <img src="/images/<?php echo $resultSearch[0][3];?>" style="
    border-radius: 50%;
    height: 200px;
    width: 200px;
    left: 35px;
    top: 13px;"> 
  </div>
  <div class="card-content white-text" style="position:absolute;top:0px;left:320px;height:100%;">
    <span class="card-title" style="color:black;font-weight:bold;"><?php echo $resultSearch[0][0]." ".$resultSearch[0][1]; ?></span>
       <p>Tipo utente:<?php if(strpos($resultSearch[0][4], "informatica")!==FALSE){
     //query per aggiungere dati in base al tipo di utente (studente/professore)
      $sql = "SELECT email_studente,data_nascita,luogo_nascita,anno_immatricolazione,corso_laurea FROM studente WHERE email_studente=:email";
      $stmt = $pdo-> prepare($sql);
      $stmt -> bindParam(':email',$email,PDO::PARAM_STR); //variabile temp 
      $stmt -> execute();
      $resultSearchStudent = $stmt->fetchAll();
     echo " Studente di ".$resultSearch[0][4]; 
   } else { 
      $sql = "SELECT email_professore,ruolo FROM docente WHERE email_professore=:email";
      $stmt = $pdo-> prepare($sql);
      $stmt -> bindParam(':email',$email,PDO::PARAM_STR); //variabile temp 
      $stmt -> execute();
      $resultSearchProf = $stmt->fetchAll();
      echo " Docente"; 
    }
       ?>
     </p>
     <?php 
      if(isset($resultSearchStudent) && count($resultSearchStudent)):
      ?>
     <p>Data Nascita: <?php echo $resultSearchStudent[0][1]; ?></p>
      <p>Luogo Nascita: <?php echo $resultSearchStudent[0][2];?></p>
      <p>Anno Immatricolazione: <?php echo $resultSearchStudent[0][3];?></p>
    <?php endif;
      if(isset($resultSearchProf) && count($resultSearchProf)):
      ?>
      <p>Ruolo: <?php echo $resultSearchProf[0][1]; ?></p>
    <?php endif; ?>
    <p>Email: <?php echo $resultSearch[0][2]; ?></p>
  </div>
  <div class="card-action" style="position:absolute;">
    <a class="btn green" name="<?php echo $resultSearch[0][2]; ?>" style="font-size:12px;color:white;" onclick="listMessages(this)">Messaggi scambiati</a> 
    <?php
        $sql = "SELECT * FROM follower WHERE email_followed=:email_followed AND email_follower=:email_follower";
        $stmt = $pdo -> prepare($sql);
        $stmt -> bindParam(":email_follower",$_SESSION['email'],PDO::PARAM_STR);
        $stmt -> bindParam(":email_followed",$resultSearch[0][2],PDO::PARAM_STR);
        $stmt -> execute();
        $resultFollowing = $stmt->fetchAll();
        if(count($resultFollowing)){
          $isFollowing=true;
        }else{
          $isFollowing=false;
        }
      ?>
      <a class="btn <?php if($isFollowing){echo 'red';} else { echo 'green';} ?>" name="<?php echo $resultSearch[0][2]; ?>" onclick="follow(this)" style="font-size:12px;color:white;">
        <?php 
        if($isFollowing){
          echo "Non seguire più";
        }else{
          echo "Inizia a seguire";
        }
        ?>
      </a>
      <?php
      if($isFollowing):
      ?>
      <a class="btn green" id="sendmessage" name="<?php echo $resultSearch[0][2]; ?>" onclick="loadMessageForm(this)" style="font-size:12px;color:white;">
        Invia messaggio
      </a>
      <?php
      endif;
      ?>
      </div>
  </div>
</div>
<script type="text/javascript">
var loadMessageForm = function(elmnt){
  var email = $(elmnt).attr('name');
  var nome = $('.card-title').html();
  if(email!=null && nome!=null && email.length>0 && nome.length>0){
    $.ajax({
      url: "../messageForm.php",
      type: 'POST',
      data: {email_followed:email,nome:nome.trim()},
      success: function(data){
        $('#content-target').empty();
        $('#content-target').append(data);
      },
      error: function(){
        alertify.alert("Errore nella trasmissione dei dati! Riprovare");
      }
    });
  }else{
    alertify.alert("Errore nella trasmissione dei dati! Riprovare");
  }
};
var follow = function(elmnt){
  var emailFollowed = $(elmnt).attr('name');
  if(emailFollowed!=null && emailFollowed.length>0){
    var op='';
    if($(elmnt).hasClass('red')){//OP INDICA L'OPERAZIONE DA ESEGUIRE. Se 0 smetto di seguire quella persona, se 1 inizio a seguirla
      var op="0"; //smetto di seguire quella persona
    }else if($(elmnt).hasClass('green')){
      var op="1"; //inizio a seguirla
    }
    $.ajax({
      url: "/funzioni/manageFollower.php",
      type:"POST",
      data: {email_followed:emailFollowed,op:op},
      success: function(data){
        var result = JSON.parse(data);
        if(result.success==1){
          if(op==='0'){
            $(elmnt).html("Inizia a seguire");
            $(elmnt).removeClass('red');
            $(elmnt).addClass('green');
            $('#sendmessage').remove();
          }else{
            var nome = $('.card-title').html();
            var aLink = document.createElement('a');
            $(aLink).html('Invia messaggio');
            $(aLink).addClass('btn green');
            $(aLink).attr('name',emailFollowed);
            $(aLink).css({
              'font-size':'12px',
              color: 'white'
            });
            $(aLink).attr('id','sendmessage');
            $(aLink).on('click',{param1:emailFollowed,param2:nome},function(event){
              var email = event.data.param1;
              var nome = event.data.param2;
              if(email.length>0 && nome.length>0){
              $.ajax({
                url: "../messageForm.php",
                type: 'POST',
                data: {email_followed:email,nome:nome.trim()},
                success: function(data){
                  $('#content-target').empty();
                  $('#content-target').append(data);
                },
                error: function(){
                  alertify.alert("Errore nella trasmissione dei dati! Riprovare");
                }
              });
            }else{
              alertify.alert("Errore nella trasmissione dei dati! Riprovare");
            }
            });
            $(elmnt).parent().append(aLink);
            $(elmnt).html("Non seguire più");
            $(elmnt).addClass('red');
            $(elmnt).removeClass('green');
          }
        }else if(result.success==0){
          alertify.alert("Errore nella trasmissione dei dati! Riprovare");
        }
      },  
      error:function(data){
        alertify.alert("Errore nella trasmissione dei dati! Riprovare");
      }
    });
  }
};
var listMessages = function(elmnt){
  var email = $(elmnt).attr('name');
  if(email!=null){
    $.ajax({
      url: "/funzioni/listMessagesProfile.php",
      type: 'POST',
      data: {email_user:email},
      success: function(data){
        $('#content-target').empty();
        $('#content-target').append(data);
      },
      error: function(){
        alertify.alert("Errore nella trasmissione dei dati! Riprovare");
      }
    });
  }else{
    alertify.alert("Errore nella trasmissione dei dati! Riprovare");
  }
};
</script>
<?php
else:
?>
<h2 class="flow-text">
  Profilo non trovato
</h2>
<?php
endif; //chiuso terzo if
?>
<?php
endif; //chiuso secondo if
?>
<?php
endif; //chiuso primo if
?>

