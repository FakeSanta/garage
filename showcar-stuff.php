<?php
	require 'config.php';
  if(empty($_SESSION['username'])){
    header("location:login");
  }
  $title = "Équipement du véhicule | ".$brend;
  $page = "showcar-stuff";
?>
<?php ob_start(); ?>
  <div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
      <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Consulter / </span>Équipement du véhicule</h4>
      <div class="mb-3">
          <label for="carburant" class="form-label">Choix du véhicule</label>
          <select class="form-select" name="immatSelect" id="select_car">
          <?php
            $fill_select = $connect->prepare('SELECT * FROM VEHICULE ORDER BY modele ASC');
            $fill_select->execute();
            while($row = $fill_select->fetch(PDO::FETCH_ASSOC))
            {
          ?>
            <option value="<?php print($row['id'])?>"><?php print($row['modele'].' '.$row['marque'].' | '.$row['immatriculation'])?></option>
          <?php
            }
          ?>
          </select>
        </div>
      <!-- Basic Bootstrap Table -->
      <div class="card">               
        <div class="table-responsive text-nowrap">
        <table class="table" id="car_selected">
            <thead>
              <tr>
                <th>Equipement</th>
                <th>Marque</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
            </tbody>
          </table>
          <script>
            function loadData(selectedValue) {
              $.ajax({
                  url: "request.php",
                  type: "POST",
                  data: {id: selectedValue},
                  success: function(response){
                    var data = JSON.parse(response);
                    var html = '';
                    $.each(data, function(key, value) {
                      html += '<tr>';
                      html += '<td><strong>' + value.nom + '</strong></td>';
                      html += '<td><strong>' + value.marque + '</strong></td>';
                      html += '</tr>';
                    });
                    $("#car_selected tbody").html(html);
                  }
                });
            }

            $(document).ready(function(){
              loadData($("#select_car").val());
              $("#select_car").on("change", function(){
                var selectedValue = $(this).val();
                loadData(selectedValue);
              });
            });
          </script>
    </div>
  </div>
      <!--/ Basic Bootstrap Table -->

  <hr class="my-5" />
<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>