	<?php
	session_start();
	require_once("funzioni/database.php");
	if($_SESSION['user']==0):
	$pdo = $databaseConnection -> getPdo();
	$sql = "SELECT utente.email,utente.nome,utente.cognome,utente.foto,docente.ruolo,news.subject,news.stringa_testo,news.data_inserimento,news.idnews 
	FROM follower,utente,docente,news 
	WHERE follower.email_follower=:email_follower AND utente.email=follower.email_followed AND docente.email_professore=utente.email AND news.email_prof=docente.email_professore
	ORDER BY data_inserimento DESC
	";
	$stmt=$pdo->prepare($sql);
	$stmt -> bindParam(':email_follower',$_SESSION['email'],PDO::PARAM_STR);
	$stmt -> execute();
	$resultNews = $stmt -> fetchAll();
	?>
	<h2 class="flow-text" style="text-align:center;">Ultime news dai docenti</h2>

	<?php
	if(count($resultNews)):
		?>
	
	<div style="max-height:550px;overflow-y:auto;">
		<ul class="collection">
			<?php
			for($i=0;$i<count($resultNews);$i++){
				?>
				<li class="collection-item avatar">
					<img src="/images/<?php echo $resultNews[$i][3];?>" alt="" class="circle">
					<span class="title"><?php echo $resultNews[$i][1].' '.$resultNews[$i][2]?></span>
					<div class="secondary-content">
						<p ><?php echo substr($resultNews[$i][7],0,16);?></p>
					</div>
					<p style="font-weight:bold;font-size:20px;">
						<?php echo $resultNews[$i][5];?> 					        
					</p>
					<p>
						<?php echo $resultNews[$i][6];?>
					</p>
				</li>

				<?php
			}
			?>
		</ul>
	</div>
	<?php
	else: 
		?>
	<h2 class="flow-text" style="text-align:center;color:red;">Nessuna notizia presente</h2>
	<?
	endif;
	?>
	<?php
	else:
		$pdo = $databaseConnection -> getPdo();
	$sql = "SELECT utente.email,utente.nome,utente.cognome,utente.foto,docente.ruolo,news.subject,news.stringa_testo,news.data_inserimento,news.idnews
	FROM utente,docente,news 
	WHERE utente.email=:email AND docente.email_professore=utente.email AND news.email_prof=docente.email_professore
	ORDER BY data_inserimento DESC
	";
	$stmt=$pdo->prepare($sql);
	$stmt -> bindParam(':email',$_SESSION['email'],PDO::PARAM_STR);
	$stmt -> execute();
	$resultNews = $stmt -> fetchAll();
	?>
	<h2 class="flow-text" style="text-align:center;">Ultime news caricate</h2>
	<?php
	if(count($resultNews)):
		?>
	<div style="max-height:550px;overflow-y:auto">
		<ul class="collection">
			<?php
			for($i=0;$i<count($resultNews);$i++){
				?>

				<li class="collection-item avatar" style="height:100%;">
					<img src="/images/<?php echo $resultNews[$i][3];?>" alt="" class="circle">
					<span class="title"><?php echo $resultNews[$i][1].' '.$resultNews[$i][2]?></span>
					<div class="secondary-content">
						<p ><?php echo substr($resultNews[$i][7],0,16);?></p>
						<p name="<?php echo $resultNews[$i][8]; ?>" onclick="removeNews(this)" class="right" style="color:red;font-size:25px;" title="Elimina"><i class="mdi-content-remove-circle-outline"></i></p>
						<p name="<?php echo $resultNews[$i][8]; ?>" onclick="editnews(this)" class="right" style="color:green;font-size:25px;" title="Modifica"><i class="mdi-editor-mode-edit"></i></p>
					</div>
					<p style="font-weight:bold;font-size:20px;">
						Oggetto: <?php echo $resultNews[$i][5];?> 					        
					</p>
					<p>
						<?php echo $resultNews[$i][6];?>
					</p>
				</li>
				
				<?php
			}
			?>
		</ul>
	</div>
	<div id="modal2" class="modal">
		<div class="modal-content">
			<h2 class="flow-text" style="text-align:center;color:red;">Sicuro di eliminare la news selezionata?</h2>
		</div>
		<div class="modal-footer">
			<a class=" modal-action modal-close waves-effect waves btn-flat">No</a>
			<a id="removeNews" class=" modal-action modal-close waves-effect waves btn-flat red">Si</a>
		</div>
	</div>
	<script>
			var logout = function(){
				window.location.replace('/funzioni/logout.php');
			};
			var editnews = function(elmnt){
				var id = $(elmnt).attr('name');
					if(id!=null && id.length>0){
						$.ajax({
							url: '/editNewsForm.php',
							type: 'POST',
							data: {id:id},
							success:function(data){
								$('#content-target').empty();
								$('#content-target').append(data);
							},
							error: function(){
								alertify.alert('Errore nella trasmissione dei dati. Riprovare');
							}
						});
					}
			};
			var removeNews = function(elmnt){
				$('#modal2').openModal();
				$('#removeNews').on('click',{param1:elmnt},function(event){
					var elmnt = event.data.param1;
					var id = $(elmnt).attr('name');
					if(id!=null && id.length>0){
						$.ajax({
							url: '/funzioni/removeNews.php',
							type: 'POST',
							data: {id:id},
							success:function(data){
								var result = JSON.parse(data);
								if(result.success==1){
									$(elmnt).parent().parent().slideUp(function(){
										$(elmnt).parent().parent().remove();
									});
								}else if(result.success==0){
									alertify.alert('Errore nella trasmissione dei dati. Riprovare');
								}
							},
							error: function(){
								alertify.alert('Errore nella trasmissione dei dati. Riprovare');
							}
						});
					}
				});
			};
		</script>
	<?php
	else: 
		?>
	<h2 class="flow-text" style="color:red;text-align:center;">Nessuna notizia caricata</h2>
	<?php
	endif;
	endif;
	?>