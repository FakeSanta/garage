<?php
session_start();

function dbConnect(){
    define('dbhost', 'localhost');
    define('dbuser', 'admin');
    define('dbpass', 'pacharubie1577');
    define('dbname', 'GESTION');
    
    // Connecting database
    try {
        $connect = new PDO("mysql:host=".dbhost."; dbname=".dbname, dbuser, dbpass);
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connect;
    }
    catch(PDOException $e) {
        echo $e->getMessage();
    }
}
function checkCarExist($immat){
    try{
        $connectdb = dbConnect();
        $query_exist = $connectdb->prepare("SELECT * FROM VEHICULE WHERE immatriculation =:immatriculation");
        $query_exist->execute(
            array(
                'immatriculation' => $immat
              )
        );
        if($query_exist->rowCount()) {
            return true;
        }else{
            return false;
        }
    }
    catch(PDOException $e) {
        echo $e->getMessage();
    }
}


function insertCarWithKm($immat, $marque, $modele, $motorisation,$kilometrage, $utilitaire){
    $connectdb = dbConnect();
    $insert = $connectdb->prepare('INSERT INTO VEHICULE (immatriculation, marque, modele, motorisation, kilometrage, utilitaire, rdv_pris, reservable) VALUES (?,?,?,?,?,?,0,0)')->execute([$immat, $marque, $modele, $motorisation, $kilometrage, $utilitaire]);
}

function insertCarKm($immat, $marque, $modele, $motorisation, $utilitaire){
    $connectdb = dbConnect();
    $insert = $connectdb->prepare('INSERT INTO VEHICULE (immatriculation, marque, modele, motorisation, utilitaire, rdv_pris, reservable) VALUES (?,?,?,?,?, 0,0)')->execute([$immat, $marque, $modele, $motorisation, $utilitaire]);
}

function checkLogin($login,$password){
    $connectdb = dbConnect();

    $query = "SELECT * FROM USER WHERE username = :username";  
    $statement = $connectdb->prepare($query);  
    $statement->execute(  
         array(  
              'username'     =>     $login,  
         )  
    );
	$row = $statement->fetch(PDO::FETCH_ASSOC);
	if(password_verify($password, $row['mdp'])){
		return true;
	}else{
		return false;
	}
}

function getLogInfo($login){
    $connectdb = dbConnect();
    $statement = $connectdb->prepare("SELECT * FROM USER WHERE username =?");
    $statement->execute([$login]);
    $row = $statement->fetch();
    $array = [
        'id' => $row['id'],
        'username' => $row['username'],
        'role' => $row['role']
    ];
    return $array;
}

function sendDiscordAlert($content){
    $webhookurl = "https://discordapp.com/api/webhooks/1114114201858887690/wbxb1tbZou4oqJisT0_JbjiZln_MiFpyAn_kGE-vY8H8zcPdbeeBdsAJhtESzmEMtevX";

    //=======================================================================================================
    // Compose message. You can use Markdown
    // Message Formatting -- https://discordapp.com/developers/docs/reference#message-formatting
    //========================================================================================================

    $timestamp = date("c", strtotime("now"));

    $json_data = json_encode([
        // Message
        "content" => $content,
        
        // Username
        "username" => "Auto Decomble",

        // Avatar URL.
        // Uncoment to replace image set in webhook
        //"avatar_url" => "https://ru.gravatar.com/userimage/28503754/1168e2bddca84fec2a63addb348c571d.jpg?size=512",

        // Text-to-speech
        "tts" => false,

        // File upload
        // "file" => "",

        /* Embeds Array
        "embeds" => [
            [
                // Embed Title
                //"title" => "PHP - Send message to Discord (embeds) via Webhook",

                // Embed Type
                //"type" => "rich",

                // Embed Description
                //"description" => "Description will be here, someday, you can mention users here also by calling userID <@12341234123412341>",

                // URL of title link
                //"url" => "https://gist.github.com/Mo45/cb0813cb8a6ebcd6524f6a36d4f8862c",

                // Timestamp of embed must be formatted as ISO8601
                //"timestamp" => $timestamp,

                // Embed left border color in HEX
                "color" => hexdec( "3366ff" ),

                /* Footer
                "footer" => [
                    "text" => "GitHub.com/Mo45",
                    "icon_url" => "https://ru.gravatar.com/userimage/28503754/1168e2bddca84fec2a63addb348c571d.jpg?size=375"
                ],

                // Image to send
                "image" => [
                    "url" => "https://ru.gravatar.com/userimage/28503754/1168e2bddca84fec2a63addb348c571d.jpg?size=600"
                ],

                // Thumbnail
                //"thumbnail" => [
                //    "url" => "https://ru.gravatar.com/userimage/28503754/1168e2bddca84fec2a63addb348c571d.jpg?size=400"
                //],

                // Author
                "author" => [
                    "name" => "krasin.space",
                    "url" => "https://krasin.space/"
                ],

                // Additional Fields array
                "fields" => [
                    // Field 1
                    [
                        "name" => "Field #1 Name",
                        "value" => "Field #1 Value",
                        "inline" => false
                    ],
                    // Field 2
                    [
                        "name" => "Field #2 Name",
                        "value" => "Field #2 Value",
                        "inline" => true
                    ]
                    // Etc..
                ]
            ]
        ]*/

    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );


    $ch = curl_init( $webhookurl );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt( $ch, CURLOPT_POST, 1);
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt( $ch, CURLOPT_HEADER, 0);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec( $ch );
    // If you need to debug, or find out why you can't send message uncomment line below, and execute script.
    // echo $response;
    curl_close( $ch );
}

function getBrowser()
{
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $browser = "N/A";

    $browsers = [
        '/msie/i' => 'Internet explorer',
        '/firefox/i' => 'Firefox',
        '/safari/i' => 'Safari',
        '/chrome/i' => 'Chrome',
        '/edge/i' => 'Edge',
        '/opera/i' => 'Opera',
        '/mobile/i' => 'Téléphone',
    ];

    foreach ($browsers as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $browser = $value;
        }
    }

    return $browser;
}

function listEvents()
{
	global $connect;
	$sql = $connect->prepare("SELECT * FROM reservation, VEHICULE, USER WHERE VEHICULE.id = reservation.id_vehicule AND reservation.id_user = USER.id AND accepted = 1");
	$sql->execute();
	
    $row = $sql->rowCount(); //changed
		
	echo "
		
		<script>		
		document.addEventListener('DOMContentLoaded', function() {
			 var calendarEl = document.getElementById('events');
        var events = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
		  locale: 'fr'
        });
			$('#events').fullCalendar({
				lang: 'en',
				defaultDate: '".date("Y-m-d")."',
				editable: true,
				eventLimit: true,
				 selectable: true,
            plugins: ['interaction', 'dayGrid'],
				displayEventTime: false,	
				header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay,listMonth'
				},				
				
				// Modal Box View							
				eventClick:  function(event, jsEvent, view) {					
					$('#modalTitle').html(event.title);
					var imgName=event.image;
					document.getElementById('imageDiv').innerHTML = '<img src='+imgName+' onerror=".'this.style.display="none"'." class=".'img-responsive'." alt=".''." >';
					$('#modalBody').html(event.description);
					$('#modalBodyLoc').html(event.location);
					$('#startTime').html(moment(event.start).format('HH:mm'));
					$('#endTime').html(moment(event.end).format('HH:mm'));
					$('#fullCalModal').modal('show');
					
					 return false;
				},
				
				// Dragable Event Update
				eventDrop: function(event, delta) {
				   var start = $.fullCalendar.moment(event.start).format();
				   var end = $.fullCalendar.moment(event.end).format();
				   $.ajax({
				   url: 'events_update.php',
				   data: 'description='+ event.description +'&title='+ event.id_vehicule +'&start='+ start +'&end='+ end +'&color='+ event.color +'&id='+ event.id ,
				   type: 'POST',
				   success: function(json) {
					swal('Parfait !', 'Réservation mise à jour !', 'success');
						 setTimeout(function () {
							location.reload()
						}, 1000);
					}
				   });
				},
				
				// Popover View				
				eventRender: function(eventObj, element) {
					element.on('click', e => e.preventDefault());
					var imgName = eventObj.image;
					var start = moment(eventObj.start).format('HH:mm');
					var end = moment(eventObj.end).format('HH:mm');					
					  element.popover({	
						html: true,
						title: eventObj.title,
						//Use the folowing line if you want to display the title and date on pophover view title
						/*title: eventObj.title + ' ' + start + ' - ' + end,*/
						content: '<img src='+imgName+' class=".'img-responsive popover'." onerror=".'this.style.display="none"'." alt=".''." >' + '<br/>' + eventObj.description,						
						trigger: 'hover',
						placement: 'bottom',
						container: 'body',					
					  });
				},	

				// Selectable date to create events
				selectHelper: true,
				selectable: true,	
				select: function( start, end, jsEvent, view ) {
					// set values in inputs
					$('#event1').find('input[name=start]').val(
						start.format('YYYY-MM-DD HH:mm')
					);
					$('#event1').find('input[name=end]').val(
						end.format('YYYY-MM-DD HH:mm')
					);					
					// show modal dialog
					$('#event1').modal('show');				   
				},

				events: [
					";
					while ($row = $sql->fetch()) {
				echo "
					{
						id: '".$row['id']."',
						title: '".($row['modele'])." | ".$row['immatriculation']." | ".strtoupper($row['username'])."',
						description: '".$row['description']."',
						location: '".$row['username']."',					
						start: '".$row['start']."',
						end: '".$row['end']."',
						color: '".$row['color']."',
						allDay: false
					},"; 	
			} ;
			echo "
				],
						
			});				
		});			
	</script>
	";	
}

// Display events information inside a modal box
function modalEvents()
{

	echo "	
	
	<div id='fullCalModal' class='modal' tabindex='-1'>
	  <div class='modal-dialog'>
		<div class='modal-content'>
		  <div class='modal-header'>
			<h5 class='modal-title'><i class='fa fa-calendar' aria-hidden='true'></i> Info Réservation</h5>
			<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Fermer'></button>
		  </div>
		  <div class='modal-body'>
				<div class='table-responsive'>
					<div class='col-md-12'>
						<h4><i class='fa fa-car' aria-hidden='true'></i> <span id='modalTitle'></span></h4>
						<p><i class='fa fa-clock-o' aria-hidden='true'></i> de <span id='startTime'></span> à <span id='endTime'></span></p>
					</div>
					<div class='col-md-12'>	
						<div id='imageDiv'> </div>
						<br/>
						<h4><i class='fa fa-flag'></i> Motif :</h4>
						 <p id='modalBody'></p>
						 <br />
						<h4><i class='fa fa-user'></i> Par :</h4>
						 <p id='modalBodyLoc'></p>
					</div>
				</div>
			</div>
		  <div class='modal-footer'>
			<button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Fermer</button>
		  </div>
		</div>
	  </div>
	</div>
";
}

// Display all events
function listAllEventsDelete()
{
	global $connect;
	$sql = $connect->prepare("SELECT reservation.id AS reservation_id,
	reservation.id_vehicule AS reservation_id_vehicule,
	reservation.description AS reservation_description,
	reservation.start AS reservation_start,
	reservation.end AS reservation_end,
	reservation.color AS reservation_color,
	reservation.id_user AS reservation_id_user,
	reservation.accepted AS reservation_accepted,
	VEHICULE.id AS vehicule_id,
	VEHICULE.immatriculation AS vehicule_immatriculation,
	VEHICULE.marque AS vehicule_marque,
	VEHICULE.modele AS vehicule_modele,
	VEHICULE.motorisation AS vehicule_motorisation,
	VEHICULE.kilometrage AS vehicule_kilometrage,
	VEHICULE.utilitaire AS vehicule_utilitaire,
	VEHICULE.reservable AS vehicule_reservable,
	USER.id AS user_id,
	USER.username AS user_username,
	USER.mdp AS user_mdp,
	USER.role AS user_role
FROM reservation, VEHICULE, USER
WHERE VEHICULE.id = reservation.id_vehicule
AND reservation.id_user = USER.id
AND accepted = 1
ORDER BY reservation_start ASC");
	$sql->execute();
    $row = $sql->rowCount();
		
		echo "<table class='table table-striped table-bordered table-hover dataTables-example' id='dataTables-example'>";
		echo "  <thead>
                <tr>	
                  <th>Véhicule</th>
				  <th>Début</th>
				  <th>Fin</th>
				  <th>Par</th>
				  <th></th>
                </tr>
              </thead>";
		while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
			$dateTime = strtotime($row['reservation_start']) ;
			$start = strftime('%d-%B-%Y %H:%M',$dateTime);
			$start = str_replace( 
				array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
				array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
				$start
			);
			$dateTime = strtotime($row['reservation_end']) ;
			$end = strftime('%d-%B-%Y %H:%M',$dateTime);
			$end = str_replace( 
				array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
				array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
				$end
			);
			
			// Print out the contents of each row into a table
			echo "<tr><td>";		
			echo $row['vehicule_modele']." | ".$row['vehicule_immatriculation'];
			echo "</td><td>"; 
			echo $start;
			echo "</td><td>";		
			echo $end;
			echo "</td><td>";		
			echo $row['user_username'];
			echo "</td><td class='r'>
			<a href='javascript:EliminaEvento(". $row['reservation_id'] . ")'class='btn btn-danger btn-sm' role='button'><i class='fa fa-fw fa-trash'></i> Suppr.</a></td>";
			echo "</tr>"; 	
		} 

		echo "</table>";
	
}

// Display all events
function listAllEventsEdit()
{
	global $connect;
	$sql = $connect->prepare("SELECT * FROM reservation, VEHICULE WHERE reservation.id_vehicule = VEHICULE.id AND accepted = 1 ORDER BY start ASC");
	$sql->execute();
    $row = $sql->rowCount();
		
		echo "<table class='table table-striped table-bordered table-hover dataTables-example' id='dataTables-example'>";
		echo "  <thead>
                <tr>	
                  <th>Véhicule</th>
				  <th>Début</th>
				  <th>Fin</th>
				  <th></th>
                </tr>
              </thead>";
		while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
			$dateTime = strtotime($row['start']) ;
			$start = strftime('%d-%B-%Y %H:%M',$dateTime);
			$start = str_replace( 
				array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
				array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
				$start
			);
			$dateTime = strtotime($row['end']) ;
			$end = strftime('%d-%B-%Y %H:%M',$dateTime);
			$end = str_replace( 
				array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
				array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
				$end
			);
			// Print out the contents of each row into a table
			echo "<tr><td>";		
			echo $row['modele']." | ".$row['immatriculation'];
			echo "</td><td>"; 
			echo $start;
			echo "</td><td>";		
			echo $end;
			echo "</td><td class='r'>
			<a href='booking_edit.php?id=". $row['id'] . "' class='btn btn-primary btn-sm' role='button'><i class='fa fa-fw fa-edit'></i> Modifier</a></td>";
			echo "</tr>"; 	
		} 

		echo "</table>";
	
}

function convertDate($date){
	$dateTime = strtotime($date) ;
	$newdate = strftime('%d %B %Y %H:%M',$dateTime);
	$newdate = str_replace( 
		array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
		array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
		$newdate
	);
	return $newdate;
}

function listAllWaitingIndex(){
	global $connect;
	$sql = $connect->prepare("SELECT reservation.id AS reservation_id,
	reservation.id_vehicule AS reservation_id_vehicule,
	reservation.description AS reservation_description,
	reservation.start AS reservation_start,
	reservation.end AS reservation_end,
	reservation.color AS reservation_color,
	reservation.id_user AS reservation_id_user,
	reservation.accepted AS reservation_accepted,
	VEHICULE.id AS vehicule_id,
	VEHICULE.immatriculation AS vehicule_immatriculation,
	VEHICULE.marque AS vehicule_marque,
	VEHICULE.modele AS vehicule_modele,
	VEHICULE.motorisation AS vehicule_motorisation,
	VEHICULE.kilometrage AS vehicule_kilometrage,
	VEHICULE.utilitaire AS vehicule_utilitaire,
	VEHICULE.reservable AS vehicule_reservable,
	USER.id AS user_id,
	USER.username AS user_username,
	USER.mdp AS user_mdp,
	USER.role AS user_role
	FROM reservation, VEHICULE, USER
	WHERE VEHICULE.id = reservation.id_vehicule
	AND reservation.id_user = USER.id
	AND accepted = 0
	ORDER BY reservation_start ASC");
	$sql->execute();
	if($sql->rowCount()){
		echo '<table class="table table-hover">';
		echo '<thead>';
			echo '<tr>';
				echo '<th>Véhicule</th>';
				echo '<th>Du</th>';
				echo '<th>Au</th>';
				echo '<th>Par</th>';
				echo '<th>Valider</th>';
				echo '<th>Refuser</th>';
			echo '</tr>';
		echo '</thead>';
		echo'<tbody class="table-border-bottom-0">';
		while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
			$id = $row['reservation_id'];
			$start = convertDate($row['reservation_start']);
			$end = convertDate($row['reservation_end']);
			echo"<tr>";
				echo"<td><b>".$row['vehicule_modele']."</b> | ".$row['vehicule_immatriculation']."</td>";
				echo"<td>". $start."</td>";
				echo"<td>". $end."</td>";
				echo"<td>". $row['user_username']."</td>";
				echo"<td><a href='javascript:EliminaTipo(".$id.")'class='btn btn-success btn-sm' role='button'><i class='fa fa-fw fa-trash'></i> Valider</a></td>";
				echo"<td><a href='javascript:rejectBooking(".$id.")'class='btn btn-warning btn-sm' role='button'><i class='fa fa-fw fa-trash'></i> Refuser</a></td>";
			echo"</tr>";
		}
		echo"</tbody>";
		echo"</table>";
	}else{
		echo'<p class="mb-4">';
			echo'Pas de réservation à valider';
		echo'</p>';
	}
}
// Display all Types (sort of category for Events)
function listAllWaiting()
{
	global $connect;
	$sql = $connect->prepare("SELECT reservation.id AS reservation_id,
	reservation.id_vehicule AS reservation_id_vehicule,
	reservation.description AS reservation_description,
	reservation.start AS reservation_start,
	reservation.end AS reservation_end,
	reservation.color AS reservation_color,
	reservation.id_user AS reservation_id_user,
	reservation.accepted AS reservation_accepted,
	VEHICULE.id AS vehicule_id,
	VEHICULE.immatriculation AS vehicule_immatriculation,
	VEHICULE.marque AS vehicule_marque,
	VEHICULE.modele AS vehicule_modele,
	VEHICULE.motorisation AS vehicule_motorisation,
	VEHICULE.kilometrage AS vehicule_kilometrage,
	VEHICULE.utilitaire AS vehicule_utilitaire,
	VEHICULE.reservable AS vehicule_reservable,
	USER.id AS user_id,
	USER.username AS user_username,
	USER.mdp AS user_mdp,
	USER.role AS user_role
FROM reservation, VEHICULE, USER
WHERE VEHICULE.id = reservation.id_vehicule
AND reservation.id_user = USER.id
AND accepted = 0
ORDER BY reservation_start ASC");
	$sql->execute();
    $row = $sql->rowCount();
		
		echo "<table class='table table-striped table-bordered table-hover dataTables-example' id='dataTables-example'>";
		echo "  <thead>
                <tr>
					<th>Véhicule</th>				
					<th>Début</th>					
					<th>Fin</th>
					<th>Par</th>
					<th></th>
					<th></th>
                </tr>
              </thead>";
		while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
			$dateTime = strtotime($row['reservation_start']) ;
			$start = strftime('%d-%B-%Y %Hh%M',$dateTime);
			$start = str_replace( 
				array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
				array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
				$start
			);
			$dateTime = strtotime($row['reservation_end']) ;
			$end = strftime('%d-%B-%Y %Hh%M',$dateTime);
			$end = str_replace( 
				array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
				array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
				$end
			);
			// Print out the contents of each row into a table
			echo "<tr><td>";		
			echo $row['vehicule_modele']." | ".$row['vehicule_immatriculation'];
			echo "</td>";
			echo "<td>";		
			echo $start;
			echo "</td>";
			echo "<td>";		
			echo $end;
			echo "</td>";
			echo "<td>";		
			echo $row['user_username'];
			echo "</td>";		
			echo "<td class='r'>			
			<a href='javascript:EliminaTipo(". $row['reservation_id'] . ")'class='btn btn-success btn-sm' role='button'><i class='fa fa-fw fa-check'></i> Valider</a></td>";
			echo "<td class='r'>			
			<a href='javascript:rejectBooking(". $row['reservation_id'] . ")'class='btn btn-warning btn-sm' role='button'><i class='fa fa-fw fa-trash'></i> Refuser</a></td>";
			echo "</tr>"; 	
		} 

		echo "</table>";
	
}

function getTitle($id)
{
	global $connect;
	$sql = $connect->prepare("select * from reservation WHERE id= :id");
	$sql->execute(array('id' => $id));
	$row = $sql->fetch(PDO::FETCH_ASSOC);
	
	echo "<option value='".$row['id_vehicule']."' required>".$row['id_vehicule']."</option>";
}

// Edit Themes Information
function editEvent($id)
{
	global $connect;
	$sql = $connect->prepare("SELECT * FROM reservation WHERE id= :id");
	$sql->execute(array('id' => $id));
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    echo "
				<fieldset>		
					<div class='form-group col-md-4'>
						<label class='col-md-3 control-label' for='start'>Start Date</label>
						<div class='input-group date form_date col-md-3' data-date='' data-date-format='yyyy-mm-dd hh:ii' data-link-field='start' data-link-format='yyyy-mm-dd hh:ii'>
							<span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span><input class='form-control' size='4' type='text' value='".$row['start']."' readonly>
						</div>
						<input id='start' name='start' type='hidden' value='".$row['start']."' required>

					</div>

					<div class='form-group col-md-4'>
						<label class='col-md-3 control-label' for='end'>End Date</label>
						<div class='input-group date form_date col-md-3' data-date='' data-date-format='yyyy-mm-dd hh:ii' data-link-field='end' data-link-format='yyyy-mm-dd hh:ii'>
							<span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span><input class='form-control' size='4' type='text' value='".$row['end']."' readonly>
						</div>
						<input id='end' name='end' type='hidden' value='".$row['end']."' required>

					</div>


					<!-- Text input-->
					<div class='form-group'>
						<label class='col-md-3 control-label' for='description'>Description</label>
						<div class='col-md-6'>
							<textarea class='form-control' rows='5' name='description' id='description'>".$row['description']."</textarea>
						</div>
					</div>
					
	
				";

}


// Update Themes Information
function updateEvent($id,$voiture,$description,$start,$end)
{
	global $connect;
	$query = $connect->prepare("UPDATE reservation SET id_vehicule = :id_vehicule , description = :description, start = :start, end = :end WHERE id = :id");
	$query->execute(
		array(
			'id' => $id,
			'id_vehicule' => $voiture,
			'description' => $description,
			'start' => $start,
			'end' => $end,
		)
		);
	if (!$query) {
		echo ("No data was inserted!");
		return false;
	} else {
			echo "<script type='text/javascript'>swal('Super !', 'Réservation mise à jour!', 'success');</script>";
			echo '<meta http-equiv="refresh" content="2; ./">'; 
			die();
			}				
			return true;
}


// Display all events
function listAllEvents()
{
	global $conection;
	$sql = mysqli_query($conection, "SELECT * FROM VEHICULE, reservation WHERE VEHICULE.id = reservation.id_vehicule ORDER BY start ASC");
    $row = mysqli_num_rows($sql);
	
		while ($row = mysqli_fetch_array($sql)) {
			//define date and time
			$starttime = $row['start'];
			$start = date('H:i',strtotime($starttime));
			$endtime = $row['end'];
			$end = date('H:i',strtotime($endtime));
			$data = date('d F Y',strtotime($starttime));
						
			echo "
			<div class='element-item transition ".$row['modele']." ".$row['marque']."' data-category='transition'>
				<article class='card fl-left'>
				  <section class='dates'>
					 <time datetime='".$data."'>
					 <span>".$data."</span>
					 </time>
				  </section>
				  <section class='card-cont'>
					 <h3>".$row['modele']."</h3>
					 <div class='even-date'>
						<i class='fa fa-clock-o'></i>
						
					";
						
						$s = date('H:i',strtotime($starttime));
						$e = date('H:i',strtotime($endtime));
						
						if ($s == $e)
						{
							echo "<span> All Day </span>";

						}
						if ($s != $e)
						{
							echo "<span> ".$start." to ".$end."</span>";
						}
					
				echo "
						
					 </div>
					 </br> 
				  </section>
			   </article>
			</div>
			";	
		} 
			
}

function rand_color() {
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}