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
	<!-- Scripts -->
	<script src="/lib/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="/lib/materialize.min.js"></script>
	<script type="text/javascript" src="/scripts/app.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#op').val('');
		});
	</script>
</head>
	<body>
		<header>
			<h1 class="flow-text title-register">Entra nel mondo delle Università</h1>
		</header>
		<div class="container">
			<form method="post" action="/funzioni/registrazione.php" enctype="multipart/form-data" id="registration-form">
				<input name="op" id="op" hidden />
				<div class="row">
					<div class="col s12">
						<p class="flow-text title-section"><i class="mdi-action-assignment"></i>Dati anagrafici</p>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s6">
						<input id="name" type="text" name="nome" class="validate" required />
						<label for="name">Nome</label>
					</div>
					<div class="input-field col s6">
						<input id="surname" type="text" name="cognome" class="validate" required />
						<label for="surname">Cognome</label>
					</div>
				</div>
				<div class="row">
					<div class="file-field input-field col s6">
						<input class="file-path validate" type="text" placeholder="Carica immagine profilo" required/>
						<div class="btn">
							<span>File</span>
							<input type="file" accept="image/*" name="foto" id="foto"/>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col s12">
						<p class="flow-text title-section"><i class="mdi-action-lock-outline"></i>Credenziali di accesso</p>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s6">
						<input id="email-register" type="email" class="validate" name="email" required />
						<label for="email-register">Email</label>
					</div>
					<div class="input-field col s6">
						<input id="password-register" type="password" name="password" class="validate" required />
						<label for="password-register">Password</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s4 ">
						<select id="role">
						  	<option value="0" disabled selected>Chi sei?</option>
						  	<option value="1">Professore</option>
						    <option value="2">Studente</option>
						  </select>
					</div>
					<div class="input-field col s4 offset-s4" hidden id="ruolo">
						<select name="ruolo">
							<option value="" disabled selected>Seleziona ruolo</option>
						   <option value="Ricercatore">Ricercatore</option>
						   <option value="Professore associato">Professore associato</option>
						   <option value="Professore ordinario">Professore ordinario</option>
						</select>
					</div>
					<div class="input-field col s4 offset-s4" hidden id="corso_laurea">
						  <select name="corso">
						  	<option value="" disabled selected>Seleziona corso laurea</option>
						    <option value="informatica">Informatica</option>
						    <option value="informatica per il management">Informatica per il management</option>
						  </select>
					</div>
				</div>
				<div class="row student" hidden>
					<div class="input-field col s4">
						<input type="date" id="birthday" class="datepicker" name="data_nascita"/>
						<label for="birthday">Data di nascita</label>
					</div>
					<div class="input-field col s4">
						<input type="text" id="placebirth" name="luogo_nascita"/>
	  					<label for="placebirth">Luogo di nascita</label>
					</div>
					<div class="input-field col s4">
						<input type="number" class="validate" name="immatricolazione" id="immatricolazione" />
	  					<label for="immatricolazione">Anno immatricolazione</label>
					</div>
				</div>
				<div class="row">
					<div class="col s12 center-align">
						<button class="waves-effect waves-light btn red" type="reset">Reset</button>
						<button class="waves-effect waves-light btn green" type="submit" id="send-registration">
						<i class="mdi-content-send right"></i>Invia registrazione</button>
					</div>
				</div>
			</form>
			<?php
			if(isset($_SESSION['error_registration']) && $_SESSION['error_registration']):
				$_SESSION['error_registration']=false;
			?>
			<div class="modal" id="modal1">
				<div class="modal-content">
					<h4 id="modal-title" class="error">Errore registrazione</h4>
					<p>
						<?php
							if(isset($_SESSION['reply']) && $_SESSION['reply']):
								$_SESSION['reply']=false;
						?>
						Errore durante la fase di registrazione. Email già presente.
						<?php
						else:
						?>
						Errore durante la fase di registrazione. Controllare la correttezza dei campi.
						<?php
						endif;
						?>
					</p>
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
	
	</body>
</html>