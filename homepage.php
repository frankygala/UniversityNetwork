<?php
session_start();
require_once("funzioni/database.php");
if(isset($_SESSION['user'])):
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
		<title>University Network</title>
		<link rel="stylesheet" href="/css/materialize.min.css" />
		<link rel="stylesheet" href="/css/style.css" />
		<link rel="stylesheet" href="/css/alertify.core.css" />
		<link rel="stylesheet" href="/css/alertify.bootstrap.css" />
		<script src="/lib/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="/lib/materialize.min.js"></script>
	</head>
	<body>
		<header>
				<div class="container">
				<nav>
    			<div class="nav-wrapper">
    				<ul id="nav-mobile" class="left">
    						<li style="margin-left: 30px;">
    						Ciao, <?php
			        echo $_SESSION['nome'].' '.$_SESSION['cognome']; ?>
    						</li>
    					</ul>
			      <ul id="nav-mobile" class="right hide-on-med-and-down">
			      	<li><a href="homepage.php">Home</a></li>
			        <li><a id="messages" class="clickable">Messaggi</a></li>
			        <li><a id="me" class="clickable">Profilo</a></li>
			        <li><a id="logout" onclick="logout()" class="clickable">Logout</a></li>
			      </ul>
		    	</div>
		 	</nav>
		 </div>
		</header>
		 	<div class="container">
		 	<div class="row">
		 		<div class="col s3">
		 			<h2 class="flow-text" >
		 				Menu
		 				<i id="menu" style="cursor:pointer;" class="mdi-navigation-menu left"></i>
		 			</h2>
		 			<ul class="collection" id="menulist">
		 				<?php 
		 			if($_SESSION['user']==1):
		 			?>
		 			<li class="collection-item" id="0" style="cursor:pointer;">
		 				Inserisci news
		 				<i class="mdi-content-add left"></i>
		 			</li>
		 			<li class="collection-item" id="corso" style="cursor:pointer;">
		 				Inserisci corso
		 				<i class="mdi-content-add left"></i>
		 			</li>
		 			<li class="collection-item" id="esercitazione" style="cursor:pointer;">
						Crea esercitazione
			     		<i class="mdi-content-content-paste left"></i>
			   		</li>
			   		<?php
					else:
		   			?>
		   			<li class="collection-item" id="6" style="cursor:pointer;">
		     			Crea progetto extra
		   			</li>
		   			<li class="collection-item" id="9" style="cursor:pointer;">
			     		Ultime news
			     		<i class="mdi-content-content-paste left"></i>
			   		</li>
		 			<?php
					endif;
		 			?>
		 				<li class="collection-item" id="1" style="cursor:pointer;">
		 					Ricerca utente
		 					<i class="mdi-action-search left"></i>
		 				</li>
		 				<li class="collection-item" id="2" style="cursor:pointer;">
			     			Lista esercitazioni
			   			</li>
			   			<li class="collection-item" id="progetti_extra_lista" style="cursor:pointer;">
			     			Lista progetti extra
			   			</li>
			   			<li class="collection-item" id="7" style="cursor:pointer;">
			     			Lista corsi disponibili
			   			</li>
			   			<li class="collection-item" id="3" style="cursor:pointer;">
			     			Chi seguo
			     			<i class="mdi-social-person left"></i>
			   			</li>
			   			<li class="collection-item" id="4" style="cursor:pointer;">
			     			Chi mi segue
			     			<i class="mdi-social-person left"></i>
			   			</li>
			   			<li class="collection-item" id="8" style="cursor:pointer;">
			     			Statistiche
			     			<i class="mdi-content-content-paste left"></i>
			   			</li>
		 			</ul>
		 		</div>
		 		<div class="col s9" id="content-target">
		 		
		 		</div>
		 	</div>
		 	
		 	<?php
		 	if($_SESSION['welcome']):
		 		$_SESSION['welcome']=false;
		 	?>
		 	<div class="modal" id="modal1">
				<div class="modal-content">
					<h4 id="modal-title">Benvenuto!</h4>
					<p>Questa è la prima volta che accedi in questo portale.</p>
					<p>Da qui potrai gestire i tuoi impegni universitari, che tu sia studente o professore.</p>
					<p>Potrai inviare messaggi, rimanere aggiornate sulle notizie dei docenti e partecipare ai progetti.</p>
				</div>
				<div class="modal-footer">
					<a class="modal-action modal-close waves-effect waves-green btn-flat">Chiudi</a>
				</div>
			</div>
			<script type="text/javascript">
				$(document).ready(function(){
					$('#modal1').openModal({
						dismissible:true
					});
				});
			</script>
		 	<?php
		 	endif;
		 	?>
		</div>
		
		<script type="text/javascript" src="/lib/alertify.min.js"></script>
		<script type="text/javascript" src="/scripts/app.js"></script>
		<script type="text/javascript">
			var logout = function(){
				window.location.replace('/funzioni/logout.php');
			};
		</script>
	</body>
</html>
<?php
else:
?>
<script type="text/javascript">
	window.location.replace('index.php');
</script>
<?php
endif;
?>