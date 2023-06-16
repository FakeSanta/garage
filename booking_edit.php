<?php
require("model/functions.php");
require 'config.php';

// Error Reporting Active
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Event Calendar">
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/voiture.ico" />

    <title>Réservation | <?php echo $brend ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../assets/css/styles.css" rel="stylesheet">	
	<!-- DateTimePicker CSS -->
	<link href="../assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">	
	<!-- DataTables CSS -->
    <link href="../assets/css/dataTables.bootstrap.css" rel="stylesheet">	
	<!-- FullCalendar CSS -->
	<link href="../assets/css/fullcalendar.css" rel="stylesheet" />
	<link href="../assets/css/fullcalendar.print.css" rel="stylesheet" media="print" />	
	<!-- jQuery -->
    <script src="../assets/js/jquery.js"></script>
	<!-- SweetAlert CSS -->
	<script src="../assets/js/sweetalert.min.js"></script> 
	<link rel="stylesheet" type="text/css" href="../assets/css/sweetalert.css">
    <!-- Custom Fonts -->
    <link href="../assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Comfortaa:300" rel="stylesheet" type="text/css">

	<!-- ColorPicker CSS -->
	<link href="../assets/css/bootstrap-colorpicker.css" rel="stylesheet">
	<script src="../assets/js/isotope.pkgd.min.js"></script> 
</head>

	<body>
		<!-- Page Content -->
		<div class="content-section-a">
			
			<!--BEGIN PLUGIN -->
			<div class="container">
				<div class="row">
				    <div class="col-lg-12">
						<div class="panel panel-default dash">
					  <h2 class="sub-header">Modifier Réservation</h2>
					  
						 <form id="editEvent" method="post" enctype="multipart/form-data" class="form-horizontal" name="editEvent">
						 
							<!-- Text input-->
							<div class="form-group">
								<label class="col-md-3 control-label" for="title">Véhicule</label>															
								<div class="col-md-4">
									<select name='title' class="form-control form-select input-md">
										
										<?php
										$id_vehicule = $_GET['id_vehicule']; 								
										$query = $connect->prepare("SELECT * FROM VEHICULE WHERE id = ? UNION SELECT * FROM VEHICULE WHERE reservable = 1");
										$query->execute([$id_vehicule]);
											
										while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
											  
											echo "
											
											<option value='".$row['id']."'>".$row['modele']." ".$row['marque']." | ".$row['immatriculation']."</option>
											
											";
																
										  }
									
										?>
									</select>
								</div>
							</div>

							<?php echo editEvent($_GET['id']); ?>
							
							<!-- Button -->
							<div class='form-group'>
								<label class='col-md-8 control-label' for='singlebutton'></label>
								<div class='col-md-4'>
									<input type='submit' name='editEvent' class='btn btn-success' value='Modifier' />
								</div>
							</div>

							</fieldset>
						</form>

					</div>
				  </div>
			<?php
	
			if(isset($_POST['editEvent']))
				{
					updateEvent($_GET['id'],$_POST['title'],trim(preg_replace('/\s+/', ' ',nl2br(str_replace( "'", "´", $_POST['description'])))),$_POST['start'],$_POST['end']);
				}
				
			?>
			<!-- Modal with events description -->
			<?php echo modalEvents(); ?>
				</div>

			</div>
			<!-- /.container -->

		</div>

		<!-- Footer -->
		<footer>
			<div class="container">
				<div class="row">
					<div class="col-lg-12">                   
						<p class="copyright text-muted small">Copyright &copy; Web Solution Info <?php echo date('Y') ?>. Tous Droits Réservés</p>
					</div>
				</div>
			</div>
		</footer>

		<!-- Bootstrap Core JavaScript -->
		<script src="../assets/js/bootstrap.min.js"></script>
		<!-- DataTables JavaScript -->
		<script src="../assets/js/jquery.dataTables.js"></script>
		<script src="../assets/js/dataTables.bootstrap.js"></script>
		<!-- Listings JavaScript delete options-->
		<script src="../assets/js/listings.js"></script>
		<!-- Metis Menu Plugin JavaScript -->
		<script src="../assets/js/metisMenu.min.js"></script>
		<!-- Moment JavaScript -->
		<script src="../assets/js/moment.min.js"></script>
		<!-- FullCalendar JavaScript -->
		<script src="../assets/js/fullcalendar.js"></script>
		<!-- FullCalendar Language JavaScript Selector -->
		<script src='../assets/lang/fr.js'></script>
		<!-- DateTimePicker JavaScript -->
		<script type="text/javascript" src="../assets/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
		<!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="../assets/js/bootstrap.bundle.min.js" ></script>  
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
		<script src="../assets/js/bootstrap-colorpicker.js"></script>
		<!-- Plugin Script Initialization for DataTables -->
		<script>
			"use strict";
			$(document).ready(function() {				
				$('.dataTables-example').dataTable();
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