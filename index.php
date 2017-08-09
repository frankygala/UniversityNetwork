<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
	<title>University Network</title>
	<link rel="stylesheet" href="/css/materialize.min.css" />
	<link rel="stylesheet" href="/css/style.css" />
	<link rel="stylesheet" href="/css/alertify.bootstrap.css" />
	<script src="/lib/jquery-1.10.2.min.js"></script>
</head>
<body>
	<h1 class="flow-text title-home">University Network</h1>
		<h1 class="flow-text description-home">Benvenuto nella rete universitaria più grande al mondo!</h1>
	<image class="homeImage" src="/images/homeimage.png" />
	<div class="container home">
		<form method="post" id="loginform" action="/funzioni/login.php">
			<div class="row">
				<div class="input-field col s4 offset-s4">
					<input id="username-login" type="email" name="email" class="validate" required />
					<label for="username-login">EMAIL</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s4 offset-s4">
					<input id="password-login" type="password" name="password" class="validate" required />
					<label for="password-login">PASSWORD</label>
				</div>
			</div>
			<div class="row">
				<div class="col s12 center-align">
					<button class="waves-effect waves-light btn green" type="submit">Accedi</button>
					<a href="/registration-form.php" class="waves-effect waves-light btn">Registrati</a>
				</div>
			</div>
		</form>

	<?php
	if(isset($_SESSION['error']) && $_SESSION['error']):
		$_SESSION['error']=false;
	?>	
	<div class="modal" id="modal1">
			<div class="modal-content">
					<h4 id="modal-title" class="error">Errore login</h4>
					<p>Dati errati</p>
				</div>
				<div class="modal-footer">
					<a class="modal-action modal-close waves-effect waves-green btn-flat">Chiudi</a>
				</div>
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
	<!-- Script -->
	<script type="text/javascript" src="/lib/materialize.min.js"></script>
	<script type="text/javascript" src="/scripts/app.js"></script>
</body>
</html>