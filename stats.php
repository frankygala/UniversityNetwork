<?php
require_once('funzioni/database.php');
session_start();
$pdo = $databaseConnection -> getPdo();
$sqlAppunti = "SELECT * FROM utente,studente where email_studente=email ORDER BY num_appunti DESC limit 5";
$sqlProgetti = "SELECT * FROM utente,studente WHERE email_studente=email ORDER BY num_progetti DESC limit 5";
$sqlAppuntiProgetti = "SELECT * FROM utente,studente WHERE email_studente=email ORDER BY num_progetti DESC,num_appunti DESC limit 5";
$sqlClassificaAppunti = "SELECT * from appunto ORDER BY valutazione_media DESC limit 5";
$sqlConteggioAppunti = "SELECT count(*) from appunto";
$sqlConteggioProgetti = "SELECT count(*) from progetto";
$stmt = $pdo -> prepare($sqlAppunti);
$stmt -> execute();
$resultAppunti = $stmt->fetchAll();
$stmt = $pdo -> prepare($sqlProgetti);
$stmt -> execute();
$resultProgetti = $stmt->fetchAll();
$stmt = $pdo -> prepare($sqlAppuntiProgetti);
$stmt -> execute();
$resultAppuntiProgetti = $stmt->fetchAll();
$stmt = $pdo -> prepare($sqlClassificaAppunti);
$stmt -> execute();
$resultClassificaAppunti = $stmt->fetchAll();
$stmt = $pdo -> prepare($sqlClassificaAppunti);
$stmt -> execute();
$resultClassificaAppunti = $stmt->fetchAll();
$stmt = $pdo -> prepare($sqlConteggioAppunti);
$stmt -> execute();
$resultConteggioAppunti = $stmt->fetchAll();
$stmt = $pdo -> prepare($sqlConteggioProgetti);
$stmt -> execute();
$resultConteggioProgetti = $stmt->fetchAll();
?>

<h2 class="flow-text" style="text-align:center;"><b>Statistiche University Network</b></h2>

<div style="text-align:center">
<button onclick="showTabAppunti()" class="btn" style="font-size:12px;padding-right: 5px;padding-left: 5px;">Classifica studenti per n° appunti</button>
<button onclick="showTabProgetti()" class="btn" style="font-size:12px;padding-right: 5px;padding-left: 5px;">Classifica studenti per n° progetti</button>
<button onclick="showTabProgettiAppunti()" class="btn" style="font-size:12px;padding-right: 5px;padding-left: 5px;">Classifica studenti per entrambi</button>
<button onclick="showClassifica()" class="btn" style="font-size:12px;padding-right: 5px;padding-left: 5px;">Classifica degli appunti</button>
<button onclick="showStats()" class="btn" style="font-size:12px;padding-right: 5px;padding-left: 5px;">Statistiche generali</button>
</div>

<?php
	if(count($resultAppunti)):
?>
<div id ="tabAppunti" hidden>
<h4 class="flow-text" style="text-align:left;line-height: 0;margin-top: 65px;font-size: medium;">Classifica degli studenti più attivi nel caricamento degli appunti</h4>	
<table class="centered">
    	<thead>
          	<tr>
            	<th>Email</th>
              	<th>Nome</th>
              	<th>Cognome</th>
              	<th>Numero appunti creati</th>
          	</tr>
        </thead>
        <?php
			for($i=0;$i<count($resultAppunti);$i++){
		?>
        <tbody>
        	<tr>
            <td>
            <?php
            	echo $resultAppunti[$i][0];
            ?>
            </td>
            <td>
           	<?php
            	echo $resultAppunti[$i][2];
            ?>
            </td>
            <td>
            <?php
            	echo $resultAppunti[$i][3];
            ?>
            </td>
            <td>
            <?php
            	echo $resultAppunti[$i][11];
            ?>
            </td>
          	</tr>
        </tbody>
        <?php
			}
		?>
    </table>
</div>
<?php
endif;
?>
<?php
	if(count($resultProgetti)):
?>
<div id ="tabProgetti" hidden>
<h4 class="flow-text" style="text-align:left;line-height: 0;margin-top: 65px;font-size: medium;">Classifica degli studenti più attivi nel caricamento dei progetti</h4>	
<table class="centered">
    	<thead>
          	<tr>
            	<th>Email</th>
              	<th>Nome</th>
              	<th>Cognome</th>
              	<th>Numero progetti ai quali partecipa </th>
          	</tr>
        </thead>
        <?php
			for($i=0;$i<count($resultProgetti);$i++){
		?>
        <tbody>
        	<tr>
            <td>
            <?php
            	echo $resultProgetti[$i][0];
            ?>
            </td>
            <td>
           	<?php
            	echo $resultProgetti[$i][2];
            ?>
            </td>
            <td>
            <?php
            	echo $resultProgetti[$i][3];
            ?>
            </td>
            <td>
            <?php
            	echo $resultProgetti[$i][12];
            ?>
            </td>
          	</tr>
        </tbody>
        <?php
			}
		?>
    </table>
</div>
<?php
endif;
?>
<?php
	if(count($resultAppuntiProgetti)):
?>
<div id ="tabAppuntiProgetti" hidden>
<h4 class="flow-text" style="text-align:left;line-height: 0;margin-top: 65px;font-size: medium;">Classifica degli studenti più attivi nel caricamento dei progetti e degli appunti</h4>	
<table class="centered">
    	<thead>
          	<tr>
            	<th>Email</th>
              	<th>Nome</th>
              	<th>Cognome</th>
              	<th>Numero progetti ai quali partecipa</th>
              	<th>Numero appunti caricati</th>
          	</tr>
        </thead>
        <?php
			for($i=0;$i<count($resultAppuntiProgetti);$i++){
		?>
        <tbody>
        	<tr>
            <td>
            <?php
            	echo $resultAppuntiProgetti[$i][0];
            ?>
            </td>
            <td>
           	<?php
            	echo $resultAppuntiProgetti[$i][2];
            ?>
            </td>
            <td>
            <?php
            	echo $resultAppuntiProgetti[$i][3];
            ?>
            </td>
            <td>
            <?php
            	echo $resultAppuntiProgetti[$i][12];
            ?>
            </td>
            <td>
            <?php
            	echo $resultAppuntiProgetti[$i][11];
            ?>
            </td>
          	</tr>
        </tbody>
        <?php
			}
		?>
    </table>
</div>
<?php
endif;
?>
<?php
	if(count($resultClassificaAppunti)):
?>
<div id ="tabClassifica" hidden>
<h4 class="flow-text" style="text-align:left;line-height: 0;margin-top: 65px;font-size: medium;">Classifica degli appunti in base alla valutazione media</h4>	
<table class="centered">
    	<thead>
          	<tr>
            	<th>Titolo appunto</th>
              	<th>Email creatore</th>
              	<th>media</th>
          	</tr>
        </thead>
        <?php
			for($i=0;$i<count($resultClassificaAppunti);$i++){
		?>
        <tbody>
        	<tr>
            <td>
            <?php
            	echo $resultClassificaAppunti[$i][6];
            ?>
            </td>
            <td>
           	<?php
            	echo $resultClassificaAppunti[$i][1];
            ?>
            </td>
            <td>
            <?php
            	echo $resultClassificaAppunti[$i][9];
            ?>
            </td>
        </tbody>
        <?php
			}
		?>
    </table>
</div>
<?php
endif;
?>
<?php
	if(count($resultConteggioAppunti) && count($resultConteggioProgetti)):
?>
<div id ="tabConteggio" hidden>
<h4 class="flow-text" style="text-align:left;line-height: 0;margin-top: 65px;font-size: medium;">Statistiche generali</h4>	
<table class="centered">
    	<thead>
          	<tr>
            	<th>Numero totale di progetti caricati</th>
              	<th>Numero totale di appunti caricati</th>
          	</tr>
        </thead>
        <tbody>
        	<tr>
            <td>
            <?php
            	echo $resultConteggioProgetti[0][0];
            ?>
            </td>
            <td>
           	<?php
            	echo $resultConteggioAppunti[0][0];
            ?>
            </td>
        </tbody>
    </table>
</div>
<?php
endif;
?>

<script type="text/javascript">
var showTabAppunti = function(){
	$('#tabProgetti').hide();
	$('#tabAppuntiProgetti').hide();
	$('#tabClassifica').hide();
	$('#tabConteggio').hide();
	$('#tabAppunti').show();
};
var showTabProgetti = function(){
	$('#tabAppunti').hide();
	$('#tabAppuntiProgetti').hide();
	$('#tabClassifica').hide();
	$('#tabConteggio').hide();
	$('#tabProgetti').show();
};
var showTabProgettiAppunti = function(){
	$('#tabAppunti').hide();
	$('#tabProgetti').hide();
	$('#tabClassifica').hide();
	$('#tabConteggio').hide();
	$('#tabAppuntiProgetti').show();
};
var showClassifica = function(){
	$('#tabAppunti').hide();
	$('#tabProgetti').hide();
	$('#tabAppuntiProgetti').hide();
	$('#tabConteggio').hide();
	$('#tabClassifica').show();
};
var showStats = function(){
	$('#tabAppunti').hide();
	$('#tabProgetti').hide();
	$('#tabAppuntiProgetti').hide();
	$('#tabClassifica').hide();
	$('#tabConteggio').show();
};
</script>
