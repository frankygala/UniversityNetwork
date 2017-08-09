var main = function(){
	$('select').material_select();
	$('.datepicker').pickadate({
    	selectMonths: true,
    	selectYears: 50,
    	format: 'yyyy-mm-dd',
    	closeOnSelect: true,
    	min: new Date(1951,0,0),
  		max: new Date(1996,11,31)
  	});
  	$('#role').on('change',function(){
  		var valore = $('#role').val();
  		if(valore==='1'){ //professore
			$('#ruolo').show();
			$('#corso_laurea').hide();
			$('.student').hide();
  		}else if (valore==='2'){ //studente
			$('#ruolo').hide();
			$('#corso_laurea').show();
			$('.student').show();
  		}
  	});
    $('#6').on('click',function(){
      $('#content-target').load('../insertProgettoExtraForm.php');
    });
  	/* Apre la pagina del profilo */
  	$('#me').on('click',function(){
  		$('#content-target').load('../profilo.php');
  	});
  	/* Permette ad un docente di creare un corso*/
  	$('#corso').on('click',function(){
  		$('#content-target').load('../createCorsoForm.php');
  	});
  	/* Permette di vedere la lista dei messaggi ricevuti ed inviati */
  	$('#messages').on('click',function(){
  		$('#content-target').load('/listMessages.php');
  	});
  	/* Se sei un docente, permette di mostrare il form per l'inserimento della news*/
  	$('#0').on('click',function(){
  		$('#content-target').load('../newsform.php');
  	});
  	/* Ti permette di ricercare gli utenti */
  	$('#1').on('click',function(){
  		$('#content-target').load('/finduser.php');
  	});
    /*Lista esercitazioni*/
    $('#2').on('click',function(){
      $('#content-target').load('../listaEsercitazioni.php');
    });
    /* Lista dei progetti extra */ 
    $('#progetti_extra_lista').on('click',function(){
      $('#content-target').load('../listaProgettiExtra.php');
    });
  	/* Lista degli utenti seguiti dall'utente loggato */
  	$('#3').on('click',function(){
  		$('#content-target').load('/funzioni/listFollower.php');
  	});
  	/* Lista degli utenti che seguono l'utente loggato */
  	$('#4').on('click',function(){
  		$('#content-target').load('/funzioni/listFollowed.php');
  	});
    /* Lista degli utenti che seguono l'utente loggato */
    $('#7').on('click',function(){
      $('#content-target').load('/listaCorsi.php');
    });
    $('#8').on('click',function(){
      $('#content-target').load('/stats.php');
    });
    $('#9').on('click',function(){
      $('#content-target').load('/ultimeNews.php');
    });
  	/*Effettua l'animazione sul menu*/
  	$('#menu').on('click',function(){
  		$('#menulist').slideToggle(500);
  	});
  	/*Permette al docente di creare l'esercitazione */
  	$('#esercitazione').on('click',function(){
  		$('#content-target').load('../creaEsercitazioneForm.php');
  	});
	//funzione per evitare che l'immagine di sfondo diventi draggable
	$('img').on('dragstart', function(event) { event.preventDefault(); });
	//funzione che gestisce il submit del form
	$('#registration-form').submit(function(event){
		var valoreOp = $('#role').val();
		//controllo quale utente intende registrarsi
		if(valoreOp != null && valoreOp==='2'){
			var immatricolazione = $('#immatricolazione').val();
			var luogo_nascita = $('#placebirth').val();
			var data_nascita=$('#birthday').val();
			var corso_laurea = $('#corso_laurea select').val();
			if(immatricolazione==null || luogo_nascita==null || data_nascita==null || corso_laurea==null){
				$('#modal1').openModal();
				$('#modal-title').html('Errore');
				$('#modal1 p').html('Alcuni campi presentano valori nulli!');
				event.preventDefault();
			}else{
				$('#op').val('0');
			}
		}else if(valoreOp!=null && valoreOp==='1'){
			var ruolo = $('#ruolo select').val();
			if(ruolo==null){
				$('#modal1').openModal();
				$('#modal-title').html('Errore');
				$('#modal1 p').html('Inserire il proprio ruolo da professore!');
				event.preventDefault();
			}else{
				$('#op').val('1');
			}
		}else if(valoreOp==null || valoreOp==='0'){
			$('#modal1').openModal();
			$('#modal1 p').html('Selezionare il tipo di utente che intende registrarsi!');
			$('#modal-title').html('Errore');
			event.preventDefault();
		}
	});
};
$(document).ready(main);