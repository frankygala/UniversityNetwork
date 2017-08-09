<?php
	require_once('database.php');
	session_start();
	$pdo = $databaseConnection -> getPdo();
	$partecipo=false;
	$sql = "SELECT * FROM progetto WHERE idprogetto=:idprogetto";
	if(isset($_POST['id_progetto'])):
		$id = $_POST['id_progetto'];
		$stmt = $pdo -> prepare($sql);
		$stmt -> bindParam(":idprogetto",$id,PDO::PARAM_INT);
		$stmt -> execute();
		$result = $stmt -> fetchAll();
		if(count($result)):
			$sql = "SELECT * FROM allegato WHERE id_prog_allegato=:idprogetto";
			$stmt = $pdo -> prepare($sql);	
			$stmt -> bindParam(":idprogetto",$id,PDO::PARAM_INT);
			$stmt -> execute();
			$resultAllegati = $stmt -> fetchAll();
			$sql = "SELECT * FROM partecipa,utente,studente WHERE id_progetto=:idprogetto AND email=email_partecipante_progetto AND email_studente=email";
			$stmt = $pdo -> prepare($sql);	
			$stmt -> bindParam(":idprogetto",$id,PDO::PARAM_INT);
			$stmt -> execute();
			$resultPartecipanti = $stmt -> fetchAll();
	?>
	<h2 class="flow-text" style="text-align:center;">Dettagli progetto</h2>
	<div class="row">
		<div class="col s12">
			<p class="title">
				<?php echo "Titolo: ".$result[0][1];
			 	?>
			</p>
			<p class="title">
				<?php echo "Descrizione: ".$result[0][2];
			 	?>
			</p>
			<p><?php echo "Data inizio: ".$result[0][3]; ?></p>
			<?php 
			$today = date('Y-m-d');
			$dataFineProgetto = date('Y-m-d',strtotime($result[0][4]));
			if($today>$dataFineProgetto):
			?>
			<p style="color:red;">Data fine:
				<?php
					echo $result[0][4];
				?>
			</p>
			<?php
				else:
			?>
			<p>Data fine:
				<?php
					echo $result[0][4];
				?>
			</p>
			<?php
				endif;
			?>
			<?php
			if($result[0][6]=='universitario'):
			$sql = "SELECT * FROM corso WHERE codice=:codiceCorso";
			$codice = $result[0][9];
			$stmt = $pdo -> prepare($sql);	
			$stmt -> bindParam(":codiceCorso",$codice,PDO::PARAM_INT);
			$stmt -> execute();
			$resultCorso = $stmt -> fetchAll();
			?>
			<p style="width:130%;">Corso di appartenenza:  <?php echo $resultCorso[0][3]."(".$resultCorso[0][5].")";?> </p>
			<p syle="margin-top: 10px;">Voto: 
				<?php 
				if($result[0][7]==null){
					echo "Non valutato";
				}else{
					echo $result[0][7];
				}
				?>
			</p>
			<p>Anno accademico: <?php echo $resultCorso[0][2]; ?></p>
			<?php
			else:
				$sqlAziende = "SELECT nome,sito,indirizzo FROM commissionato,azienda WHERE id_prog_commissionato=:idprogetto AND nome=nome_azienda";
				$stmt = $pdo -> prepare($sqlAziende);	
				$stmt -> bindParam(":idprogetto",$id,PDO::PARAM_INT);
				$stmt -> execute();
				$resultListaAziende = $stmt -> fetchAll();
			?>
			<p>Categoria: <?php echo $result[0][8]; ?></p>
			<?php
			if(count($resultListaAziende)):

			?>
			<h2 class="flow-text" style="text-align:center;">Aziende che hanno seguito/commissionato il progetto</h2>
			<ul class="collection">
				<?php
				for($i=0;$i<count($resultListaAziende);$i++){
				?>
				<li class="collection-item">
					<p class="title"><?php echo $resultListaAziende[$i][0]?></p>
					<a style="color:blue;" href='<?php echo $resultListaAziende[$i][1]?>'><?php echo 'Sito: '.$resultListaAziende[$i][1]?></a>
					<p><?php echo 'Indirizzo: '.$resultListaAziende[$i][2]?></p>
				</li>
				<?php
				}
				?>
			</ul>
			<?php
			else:
			?>
				<h2 class="flow-text" style="text-align:center;color:red;">Nessuna azienda ha seguito/commissionato il progetto</h2>
			<?php
			endif;
			?>
			<?php
			endif;
			?>
		</div>
	</div>
	<div class="row" style="margin-top:370px;">
	<h2 class="flow-text" style="text-align:center;">Allegati disponibili</h2>
		<?php
		for($i=0;$i<count($resultAllegati);$i++){
		?>
		<div class="col s4">
			<a class="btn green" target="blank" style="font-size:12px;" href="../files/<?php echo $resultAllegati[$i][2]; ?>">Allegato n° <?php echo $i+1; ?></a>
		</div>
		<?php
		}
		?>
	</div>
	<div class="row">
		<div class="col s12">
			<h2 class="flow-text" style="text-align:center;color:green;">Partecipanti: <?php echo count($resultPartecipanti); ?></h2>
			<ul class="collection" style="background-color:white;	max-height:500px;overflow-y:auto;">
				<?php
				for($i=0;$i<count($resultPartecipanti);$i++){
					if($resultPartecipanti[$i][0]==$_SESSION['email']){
						$partecipo = true;
					}
				?>
				<li class="collection-item avatar" style="height:100%;">
					<img src="../images/<?php echo $resultPartecipanti[$i][6]; ?>" alt="Immagine profilo" class="circle">
					<p class="title"><?php echo $resultPartecipanti[$i][4].' '.$resultPartecipanti[$i][5].' ('.$resultPartecipanti[$i][0].')'?></p>
					<a class="btn" onclick="profilo(this)" name="<?php echo $resultPartecipanti[$i][0]; ?>" style="font-size:12px;margin-top:20px;">
						Visualizza profilo
					</a>
					<?php
					if($partecipo):
						$partecipo=false;
					?>
					<div class="secondary-content">
						<p>ME</p>
					</div>
					<?php
					endif;
					?>
				</li>
				<?php
				}
				?>
			</ul>
			<?php
			$today = date('Y-m-d');
			$dataFineProgetto = date('Y-m-d',strtotime($result[0][4]));
			if($_SESSION['user']==0 && !$partecipo && $today<= $dataFineProgetto):
			?>
			<a class="btn" onclick="partecipa(this)" name="<?php echo $id;?>">Partecipa</a>
			<?php
			endif;
			?>
			<?php
			if($_SESSION['user']==1 && $result[0][6]=='universitario' && $_SESSION['email']==$result[0][12]):
			?>
			<div style="display:inline;">
				<a class="btn" onclick="assegnaVoto(this)" name="<?php echo $id;?>">Assegna voto</a>
				<input style="display:inline;width:30%;margin-left:5%;" type="text" placeholder="Inserisci voto" id="voto"/>
			</div>
			<?php
			endif;
			?>
		</div>
	</div>
	
	<script type="text/javascript">
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
				alertify.alert("Errore trasmissione dati");
				}
		}); 
		};
		var assegnaVoto = function(elmnt){
			var idprogetto=$(elmnt).attr('name');
			var voto = $('#voto').val();
			if(parseInt(voto) && voto<31 && voto>17){
			$.ajax({
				url:"/funzioni/assegnaVoto.php",
				type:"POST",
				data:{idprogetto:idprogetto,voto:voto},
				success: function(data){
					var result = JSON.parse(data);
  					if(result.success==1){
  					alertify.alert("Voto caricato con successo!");
  					$.ajax({
						url: "/funzioni/dettagliProgetto.php",
						type: "POST",
						data:{id_progetto:idprogetto},
						success:function(data){
							$('#content-target').empty();
							$('#content-target').append(data);
						},
						error:function(){
							alertify.alert("Errore nella trasmissione dei dati");
						}
					});
  					}else{
  						alertify.alert("Errore aggiornamento voto");
  					}
				},
				error:function(data){
					alertify.alert("Errore trasmissione dati");
				}
			});
		}else{
			alertify.alert("Errore! Controllare il campo voto (18,30)");
		}

		};
		var partecipa = function(elmnt){
			var idprogetto=$(elmnt).attr('name');
			$.ajax({
				url:"/funzioni/partecipaProgetto.php",
				type:"POST",
				data:{idprogetto:idprogetto},
				success: function(data){
					var result = JSON.parse(data);
	  					if(result.success==1){
	  					alertify.alert("Partecipazione effettuata!");
	  					$.ajax({
						url: "/funzioni/dettagliProgetto.php",
						type: "POST",
						data:{id_progetto:idprogetto},
						success:function(data){
							$('#content-target').empty();
							$('#content-target').append(data);
						},
						error:function(){
							alertify.alert("Errore nella trasmissione dei dati");
						}
					});
	  					}else{
	  						alertify.alert("Errore partecipazione");
	  					}
				},
				error:function(data){
					alertify.alert("Errore trasmissione dati");
				}
			}); 
		};
	</script>
	<?php 
	else:
	?>
	<h2 class="flow-text red" style="text-align:center;">Errore trasmissione dati</h2>
	<?php
	endif;
	else:
	?>	
	<h2 class="flow-text red" style="text-align:center;">Errore ricezione dei dati</h2>
<?php
endif;
?>