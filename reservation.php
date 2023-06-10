<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '/var/www/html/vendor/phpmailer/phpmailer/src/Exception.php';
require '/var/www/html/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '/var/www/html/vendor/phpmailer/phpmailer/src/SMTP.php';
require("model/functions.php");
require 'config.php';
if(empty($_SESSION['username'])){
    header("location:login");
  }

// CSRF Protection


// Error Reporting Active
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Event Calendar">
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/voiture.ico" />

    <title>Réservation | <?php echo $brend ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="calendar/assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="calendar/assets/css/styles.css" rel="stylesheet">	
	<!-- DateTimePicker CSS -->
	<link href="calendar/assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">	
	<!-- DataTables CSS -->
    <link href="calendar/assets/css/dataTables.bootstrap.css" rel="stylesheet">	
	<!-- FullCalendar CSS -->
	<link href="calendar/assets/css/fullcalendar.css" rel="stylesheet" />
	<link href="calendar/assets/css/fullcalendar.print.css" rel="stylesheet" media="print" />	
	<!-- jQuery -->
    <script src="calendar/assets/js/jquery.js"></script>
	<!-- SweetAlert CSS -->
	<script src="calendar/assets/js/sweetalert.min.js"></script> 
	<link rel="stylesheet" type="text/css" href="calendar/assets/css/sweetalert.css">
    <!-- Custom Fonts -->
    <link href="calendar/assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Comfortaa:300" rel="stylesheet" type="text/css">

	<!-- ColorPicker CSS -->
	<link href="calendar/assets/css/bootstrap-colorpicker.css" rel="stylesheet">
	<script src="calendar/assets/js/isotope.pkgd.min.js"></script> 
</head>

	<body>
		<!-- Navigation -->
		<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-black ">
		  <div class="container topnav">
			<a class="navbar-brand" href="index"><h1><i class="fa fa-calendar" aria-hidden="true"></i> Réservation <?php echo $brend ?></h1></a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
			  <span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
			  <div class="navbar-nav">
				<!--<a class="nav-link active" aria-current="page" href="index">Home</a>
				<a class="nav-link" href="test.php">Event Calendar</a>
				<a class="nav-link" href="#ticket_events">Ticket Events</a>
				<a class="nav-link" href="#features">Features</a>-->
			  </div>
			</div>
		  </div>
		</nav>

		<!-- Header -->
	   <div id="home"></div>
		<!-- /.intro-header -->

		<!-- Page Content -->
		<div id="eventcalendar"></div>
		<div class="content-section-a">
			
			<!--BEGIN PLUGIN -->
			<div class="container">
			
			<!--<h1><i class="fa fa-calendar" aria-hidden="true"></i> Réservation </h1>-->
			
				<div class="row">
				   <div class="col-lg-12">
				<div class="panel panel-default dash">
					
					<div class="panel panel-default">
						<div class="panel-heading">
							<!-- Button trigger New Event modal -->
							<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#event">
							 <i class="fa fa-calendar" aria-hidden="true"></i> Nouvelle réservation
							</button>
							<!-- New Event Creation Modal -->
							<div class="modal fade" id="event" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class='modal-header'>
											<h5 class='modal-title'><i class='fa fa-calendar' aria-hidden='true'></i> Nouvelle réservation</h5>
											<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
										</div>
										<div class="modal-body">
											 <!-- New Event Creation Form -->
											<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" class="form-horizontal" name="novoevento">
												<fieldset>
													<!-- CSRF PROTECTION -->
													
													<!-- Text input-->
													<div class="form-group">
														<!-- dans l'icone -->
														<label class="col-md-3 control-label" for="voiture">Véhicule</label>
														<div class="col-md-4">
															<select name='voiture' class="form-control form-select input-md" id="config">
																
																<?php 
																
																$query = $connect->prepare("SELECT * FROM VEHICULE WHERE reservable = 1 ORDER BY modele ASC");
																$query->execute();																	
																while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
																	  
																	echo "
																	
																	<option value='".$row['id']."'>".$row['modele']." ".$row['marque']." | ".$row['immatriculation']."</option>
																	
																	";
																						
																  }
															
																?>
															</select>
														</div>
													</div>
													<!--<script>
														function loadData(selectedValue) {
														$.ajax({
															url: "request.php",
															type: "POST",
															data: {id: selectedValue},
															success: function(response){
																var data = JSON.parse(response);
																var html = '';
																$.each(data, function(key, value) {
																html += '<option value="">' + value.nom + '</strong></td>';
																});
																$("#car_selected tbody").html(html);
															}
															});
														}

														$(document).ready(function(){
															loadData($("#config").val());
															$("#config").on("change", function(){
																var selectedValue = $(this).val();
																loadData(selectedValue);
															});
														});
													</script>-->
													<!-- Text input-->
													<div class="form-group col-md-6">
														<label class="col-md-3 control-label" for="start">Du</label>
														<div class="input-group date form_date col-md-6" data-date="" data-date-format="dd-MM-yyyy hh:ii" data-link-field="start" data-link-format="yyyy-mm-dd hh:ii">
															<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span><input class="form-control" size="16" type="text" value="" readonly lang="fr">
														</div>
														<input id="start" name="start" type="hidden" value="" required>

													</div>

													<div class="form-group col-md-6">
														<label class="col-md-3 control-label" for="end">Au</label>
														<div class="input-group date form_date col-md-6" data-date="" data-date-format="dd-MM-yyyy hh:ii" data-link-field="end" data-link-format="yyyy-mm-dd hh:ii">
															<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span><input class="form-control" size="16" type="text" value="" readonly>
														</div>
														<input id="end" name="end" type="hidden" value="" required>

													</div>
																			

													<!-- Text input-->
													<div class="form-group">
														<label class="col-md-3 control-label" for="description">Motif</label>
														<div class="col-md-12">
															<textarea class="form-control" rows="5" name="description" id="description"></textarea>
														</div>
													</div>
												
													<!-- Button -->
													<div class="form-group">
														<label class="col-md-12 control-label" for="singlebutton"></label>
														<div class="col-md-4">
															<input type="submit" name="novoevento" class="btn btn-success" value="Ajouter" />
														</div>
													</div>

												</fieldset>
											</form>  
										</div>
										<div class="modal-footer">
											<button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Fermer</button>
										</div>
									</div>
								</div>
							</div>													
							<!-- New Event Creation Modal for the selectable date -->
							<div class="modal fade" id="event1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class='modal-header'>
											<h5 class='modal-title'><i class='fa fa-calendar' aria-hidden='true'></i> Nouvelle réservation</h5>
											<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
										</div>
										<div class="modal-body">
											 <!-- New Event Creation Form -->
											<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" class="form-horizontal" name="novoevento">
												<fieldset>
													<!-- CSRF PROTECTION -->
													
													<!-- Text input-->
													<div class="form-group">
														<!-- Dans le calendar-->
														<label class="col-md-3 control-label" for="voiture">Salle</label>
														<div class="col-md-4">
															<select name='voiture' class="form-control form-select input-md">
																
																<?php 
																
																$query = $connect->prepare("SELECT * FROM reservation ORDER BY id DESC");
																$query->execute();
																																	
																while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
																	  
																	echo "
																	
																	<option value='".$row['id']."'>".$row['id']."</option>
																	
																	";
																						
																  }
															
																?>
															</select>
														</div>
													</div>
													
													<!-- Text input-->
													<div class="form-group">
														<label class="col-md-3 control-label" for="color">Couleur</label>
														<div class="col-md-4">
															<div id="cp2" class="input-group colorpicker-component">
																<input id="cp2" type="text" class="form-control" name="color" value="#5367ce" required/>
																<span class="input-group-addon"><i></i></span>
															</div>
														</div>
													</div>

													
													<input id="start" class="form-control" name="start" type="hidden" value="">
													<input id="end" class="form-control" name="end" type="hidden" value="">

													<!-- Text input-->
													<div class="form-group">
														<label class="col-md-3 control-label" for="description">Description</label>
														<div class="col-md-12">
															<textarea class="form-control" rows="5" name="description" id="description"></textarea>
														</div>
													</div>

													<!-- Button -->
													<div class="form-group">
														<label class="col-md-12 control-label" for="singlebutton"></label>
														<div class="col-md-4">
															<input type="submit" name="novoevento" class="btn btn-success" value="Ajouter" />
														</div>
													</div>

												</fieldset>
											</form>  
										</div>
										<div class="modal-footer">
											<button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Fermer</button>
										</div>
									</div>
								</div>
							</div>														
							<?php
								if($_SESSION['role'] == 1 || $_SESSION['role'] == 2){
							?>
							<!-- Button trigger Delete Event modal -->
							<button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editevent">
								<i class="fa fa-edit" aria-hidden="true"></i> Modifier une réservation
							</button>

							<!-- Modal -->
							<div class="modal fade" id="editevent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class='modal-header'>
											<h5 class='modal-title'><i class='fa fa-calendar' aria-hidden='true'></i> Modifier réservation</h5>
											<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
										</div>
										<div class="modal-body">
											<!-- Modal featuring all events saved on database -->
											<?php echo listAllEventsEdit(); ?>

										</div>
										<div class="modal-footer">
											<button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Fermer</button>
										</div>
									</div>
								</div>
							</div>
							<!-- Button trigger Delete Event modal -->
							<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delevent">
								<i class="fa fa-close" aria-hidden="true"></i> Supprimer une réservation
							</button>

							<!-- Modal -->
							<div class="modal fade" id="delevent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class='modal-header'>
											<h5 class='modal-title'><i class='fa fa-calendar' aria-hidden='true'></i> Supprimer une réservation</h5>
											<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
										</div>
										<div class="modal-body">
											<!-- Modal featuring all events saved on database -->
											<?php echo listAllEventsDelete(); ?>

										</div>
										<div class="modal-footer">
											<button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Fermer</button>
										</div>
									</div>
								</div>
							</div>

							<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#deltype">
								<i class="fa fa-check" aria-hidden="true"></i> Valider une réservation
							</button>

							<!-- Modal -->
							<div class="modal fade" id="deltype" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class='modal-header'>
											<h5 class='modal-title'><i class='fa fa-calendar' aria-hidden='true'></i> Valider une réservation</h5>
											<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
										</div>
										<div class="modal-body">
											<!-- Modal featuring all types saved on database -->
											<?php echo listAllWaiting(); ?>

										</div>
										<div class="modal-footer">
											<button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Fermer</button>
										</div>
									</div>
								</div>
							</div>
							<?php
								}
							?>
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<div class="col-lg-12">
								<div id="events"></div>
							</div>				
						</div>
					</div>
				</div>
			</div>
			<?php

				// If user clicked on the new event button
				if (!empty($_POST['novoevento'])) {
					
					// Variables from form
							$voiture = htmlspecialchars($_POST['voiture'], ENT_QUOTES);
							//$image = $_FILES['image'];
							$description = trim(preg_replace('/\s+/', ' ',nl2br(str_replace( "'", "´", $_POST['description']))));
							//$location = trim(preg_replace('/\s+/', ' ',nl2br(str_replace( "'", "´", $_POST['location']))));							
							//$url = antiSQLInjection($_POST['url']);
							$start = $_POST['start'];
							$end = $_POST['end'];
							
					if (empty($start) || empty($end)) {
						echo "<script type='text/javascript'>swal('Ooops...!', 'Vous devez remplir les dates', 'error');</script>";	
						echo '<meta http-equiv="refresh" content="1; ./reservation">'; 
						return false;
					}
					if (!empty($start) || !empty($end) || !empty($voiture)) {
						// Saves informationon the database
						$verifDate = $connect->prepare("SELECT * FROM reservation, VEHICULE WHERE reservation.id_vehicule = VEHICULE.id AND id_vehicule =? AND start< ? AND end> ?");
						$verifDate->execute([$voiture, $end, $start]);
						if($verifDate->rowCount()){
							echo "<script type='text/javascript'>swal('Erreur !', 'Pas disponible dans ces crénaux', 'error');</script>";
							echo '<meta http-equiv="refresh" content="2; ./reservation">'; 
						}else{
							$new_start = date('d F H:i', strtotime($start));
							$new_end = date('d F H:i', strtotime($end));
							$new_end = str_replace( 
                                array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                                array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
                                $new_end
							);
							$new_start = str_replace( 
                                array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                                array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
                                $new_start
							);

							$getVoiture = $connect->prepare("SELECT * FROM VEHICULE WHERE id = :id");
							$getVoiture->execute(array('id' => $voiture));
							$rowVoiture = $getVoiture->fetch(PDO::FETCH_ASSOC);

							$content = "**".strtoupper($_SESSION['username'])."** - Création réservation de la ***".$rowVoiture['immatriculation']."*** du ***".$new_start."*** au ***".$new_end."***";
							sendDiscordAlert($content);
							$random_color = rand_color();
							if($_SESSION['role'] == 0){
								$accepted = 0;
							}else{
								$accepted = 1;
							}
							$sql = $connect->prepare("INSERT INTO reservation (id_vehicule, description, start, end, color, id_user, accepted)VALUES (?,?,?,?,?,?,?)");
							$sql->execute([$voiture,$description,$start,$end,$random_color,$_SESSION['id'],$accepted]);

							$getVoiture = $connect->prepare("SELECT * FROM VEHICULE WHERE id = :id");
							$getVoiture->execute(array('id' => $voiture));
							$infoVoiture = $getVoiture->fetch(PDO::FETCH_ASSOC);
							// If information is correctly saved		
							if (!$sql) {
							echo ("Can't insert into database: " . mysqli_error());
							return false;
							} else {
								if($accepted == 0){
									$mail = new PHPMailer();
									$mail->IsSMTP();
									$mail->Mailer = "smtp";

									$mail->SMTPDebug  = 0;  
									$mail->SMTPAuth   = TRUE;
									$mail->SMTPSecure = "tls";
									$mail->Port       = 587;
									$mail->Host       = "smtp.gmail.com";
									$mail->Username   = "supervision.decomble@gmail.com";
									$mail->Password   = "rvnqrxyankxtuegm";
									$mail->AddAddress("aurelienfevrier08@gmail.com","Auto ".$brend);
									$mail->SetFrom("supervision.decomble@gmail.com", "Auto ".$brend);
									$mail->Subject = $_SESSION['username']." - Demande une reservation pour ".$infoVoiture['modele']." ".$infoVoiture['marque']." | ".$infoVoiture['immatriculation']." de ".$new_start." à ".$new_end;
									$content = $_SESSION['username']." - Demande une reservation pour ".$infoVoiture['modele']." ".$infoVoiture['marque']." | ".$infoVoiture['immatriculation']." de ".$new_start." à ".$new_end;
									$mail->MsgHTML($content); 
									if(!$mail->Send()) {
										echo "Error while sending Email.";
										echo "<script>console.log(" . json_encode($mail) . ")</script>";
									}else{
									}
									echo "<script type='text/javascript'>swal('Demande créée !', 'En attente de confirmation d'un admin', 'success');</script>";
								}else{
									echo "<script type='text/javascript'>swal('Parfait !', 'Réservation créée', 'success');</script>";
								}
									echo '<meta http-equiv="refresh" content="3; ./reservation">'; 
									die();
							}		
							return true;
						}
					}

				}


				// If user clicked on the new event button
				if (!empty($_POST['novotipo'])) {
				 
					// Variables from form
					$title = htmlspecialchars($_POST['title'], ENT_QUOTES);
					
					// Saves informationon the database
					$sql = $connect->prepare("INSERT INTO type (title) VALUES (?)")->execute([$title]);
		 
					// If information is correctly saved			
					if (!$sql) {
					echo ("Can't insert into database: " . mysqli_error());
					return false;
					} else {
							echo "<script type='text/javascript'>swal('Parfait !', 'Salle ajoutée !', 'success');</script>";
							echo '<meta http-equiv="refresh" content="1; ./reservation">'; 
							die();
					}		
					return true;
				}

			?>
			<!-- Modal with events description -->
			<?php echo modalEvents(); ?>
				</div>

			</div>
			<!-- /.container -->

		</div>
		
		

		
		<!-- Plugin Description -->
		<div id="features">	

		<footer>
			<div class="container">
				<div class="row">
					<div class="col-lg-12">                   
						<p class="copyright text-muted small">Copyright &copy; Web Solution Info <?php echo date('Y')?>. Tous Droits Réservés</p>
					</div>
				</div>
			</div>
		</footer>

		<!-- Bootstrap Core JavaScript -->
		<script src="calendar/assets/js/bootstrap.min.js"></script>
		<!-- DataTables JavaScript -->
		<script src="calendar/assets/js/jquery.dataTables.js"></script>
		<script src="calendar/assets/js/dataTables.bootstrap.js"></script>
		<!-- Listings JavaScript delete options-->
		<script src="calendar/assets/js/listings.js"></script>
		<!-- Metis Menu Plugin JavaScript -->
		<script src="calendar/assets/js/metisMenu.min.js"></script>
		<!-- Moment JavaScript -->
		<script src="calendar/assets/js/moment.min.js"></script>
		<!-- FullCalendar JavaScript -->
		<script src="calendar/assets/js/fullcalendar.js"></script>
		<!-- FullCalendar Language JavaScript Selector -->
		<script src='calendar/assets/lang/fr.js'></script>
		<!-- DateTimePicker JavaScript -->
		<script type="text/javascript" src="calendar/assets/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
		<!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="calendar/assets/js/bootstrap.bundle.min.js" ></script>  
		<!-- Datetime picker initialization -->
		<script type="text/javascript">	
			"use strict";
			$('.form_date').datetimepicker({
				language:  'fr',
				weekStart: 1,
				todayBtn:  0,
				autoclose: 1,
				todayHighlight: 1,
				startView: 2,
				forceParse: 0
			});
		</script>	
		<!-- ColorPicker JavaScript -->
		<script src="calendar/assets/js/bootstrap-colorpicker.js"></script>
		<!-- Plugin Script Initialization for DataTables -->
		<script>
			"use strict";
			$(document).ready(function() {				
				$('.dataTables-example').dataTable({
					language:{
						"sLengthMenu": "Afficher _MENU_ entrées",
						"sSearch": "Rechercher :",
						"sInfo": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
						"oPaginate": {
							"sFirst": "Premier",
							"sLast": "Dernier",
							"sNext": "Suivant",
							"sPrevious": "Précédent"
						}
					}
				});
			});
		</script>
		<!-- ColorPicker Initialization -->
		<script>
			"use strict";
			$(function() {
				"use strict";
				$('#cp1').colorpicker();
				$('#cp2').colorpicker();
			});
		
		</script>
		<!-- JS array created from database -->
		<?php echo listEvents(); ?>
		
		<script>
			"use strict";
			// init Isotope
			var $grid = $('.grid').isotope({
			  itemSelector: '.element-item',
			  layoutMode: 'fitRows'
			});
			// filter functions
			var filterFns = {
			  // show if number is greater than 50
			  numberGreaterThan50: function() {
				var number = $(this).find('.number').text();
				return parseInt( number, 10 ) > 50;
			  },
			  // show if name ends with -ium
			  ium: function() {
				var name = $(this).find('.name').text();
				return name.match( /ium$/ );
			  }
			};
			// bind filter on select change
			$('.filters-select').on( 'change', function() {
			  // get filter value from option value
			  var filterValue = this.value;
			  // use filterFn if matches value
			  filterValue = filterFns[ filterValue ] || filterValue;
			  $grid.isotope({ filter: filterValue });
			});

			</script>
		
	</body>

</html>