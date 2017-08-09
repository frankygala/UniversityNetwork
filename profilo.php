<?php
require_once('funzioni/database.php');
session_start();
?>
<h2 class="flow-text" style="text-align:center;">Profilo</h2>
<div class="card large blue-grey darken-1" style="height:650px;">
	<div class="card-image">
    <img src="/images/<?php echo $_SESSION['image']; ?>">
    <span class="card-title" style="color:black;font-weight:bold;"><?php echo $_SESSION['nome']." ".$_SESSION['cognome']; ?></span>
  </div>
  <div class="card-content white-text" style="height:100%;">
    <p>Tipo utente:<?php if($_SESSION['user']==0){ echo " Studente"; } else { echo " Professore"; } ?></p>
    <p>Email: <?php echo $_SESSION['email']; ?></p>
    <?php
    if($_SESSION['user']==0):
    ?>
    <p>Data di nascita: <?php echo $_SESSION['data_nascita']; ?></p>
    <p>Luogo di nascita: <?php echo $_SESSION['luogo_nascita']; ?> </p>
    <p>Anno immatricolazione: <?php echo $_SESSION['anno_immatricolazione']; ?> </p>
    <p>Corso: <?php echo $_SESSION['corso']; ?> </p>
    <a class="btn green left" onClick="appuntiCaricati(this)" style="cursor:pointer;color:white;margin-top:20px;">
        Appunti caricati
    </a>
    <a class="btn green right" onClick="progettiCaricati(this)" style="cursor:pointer;color:white;margin-top:20px;">
        Progetti caricati
    </a>
    <?php
    else:
    ?>
    <p>Ruolo: <?php echo $_SESSION['ruolo']; ?> </p>
    <a class="btn green left" onClick="newsCaricate(this)" style="cursor:pointer;color:white;margin-top:20px;">
        News caricate
    </a>
    <script type="text/javascript">
    var newsCaricate = function(){
          $('#content-target').load('/ultimeNews.php');
        };
    </script>
    <?php
    endif;
    ?>  
  </div>
    <div class="card-action" style="position:absolute;">
      <a class="btn green left" onClick="cambiaPassword(this)" style="cursor:pointer;color:white;">
        Cambia password
      </a>
      <a class="btn red right" onClick="deleteAccount(this)" style="color:white;cursor:pointer;">
        Elimina account
      </a>
    </div>
  </div>
  <script type="text/javascript">
  var progettiCaricati = function(){
      $('#content-target').load('/listaProgettiProfilo.php');
  };
   var appuntiCaricati = function(){
      $('#content-target').load('/listaAppuntiProfilo.php');
  };
  var deleteAccount = function(){
    $.ajax({
      url : "/funzioni/cancellaAccount.php",
      success: function(data){
        var result = JSON.parse(data);
        if(result.success==1){
          alertify.alert("Account eliminato con successo",function(){
            window.location.replace("/index.php");
          });
        }else{
          alertify.alert("Errore nell'eliminazione del profilo. Riprovare.");
        }
      },
      error: function(){  
        alertify.alert("Errore nella trasmissione dei dati. Riprovare.");
      }
    });
  };
  var cambiaPassword= function(){
    // prompt dialog
    alertify.prompt("Cambio password", function (e, str) {
    // str is the input text
    if (e) {
      $.ajax({
        url:'/funzioni/cambioPassword.php',
        type:'POST',
        data:{password:str},
        success: function(data){
          var result = JSON.parse(data);
          if(result.success==1){
            alertify.alert("Cambio password effettuato.");
          }else{
            alertify.alert("Errore cambio password. Riprovare");
          }
        },
        error: function(){
            alertify.alert("Errore tramissione dati. Riprovare");
        }
      });
    } else {
      alertify.alert("Cambio password annullato");
    }
}, "Inserire nuova password");
  };
  </script>