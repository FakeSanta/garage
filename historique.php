<?php
	require 'config.php';
  if(empty($_SESSION['username'])){
    header("location:login");
  }
  $title = "Historique | ".$brend;
  $page = "historique";
?>
<?php ob_start(); ?>
          <div class="content-wrapper">

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Consulter / </span>Historique des vidanges et contrôles techniques</h4>
              <select class="form-select" name="selectSort" id="selectSort">
                  <option value="DESC">Du + récent au + ancien</option>
                  <option value="ASC">Du + ancien au + récent</option>
                  <option value="CT">Contrôle technique uniquement</option>
                  <option value="VID">Vidange uniquement</option>
                  <?php
                    $fill_select = $connect->prepare('SELECT * FROM VEHICULE ORDER BY immatriculation ASC');
                    $fill_select->execute();
                    while($row = $fill_select->fetch(PDO::FETCH_ASSOC))
                    {
                  ?>
                    <option value="<?php print($row['id'])?>"><?php print($row['immatriculation'].' '.$row['marque'].' '.$row['modele'])?></option>
                  <?php
                    }
                  ?>
                </select>
                <hr class="my-5" />
              <div class="card">
                <h5 class="card-header">Tableau</h5>
                <div class="table-responsive text-nowrap">
                  <table class="table table-hover" id="car_selected">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Immatriculation</th>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Motorisation</th>
                        <th>dernière vidange effectuée</th>
                        <th>dernier CT effectué</th>
                        <th>Commentaire</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    </tbody>
                  </table>
                  <script>
                    function loadData(selectedValue) {
                      $.ajax({
                        url: "sort.php",
                        type: "POST",
                        data: {id: selectedValue},
                        success: function(response){
                          console.log(response);
                          var data = JSON.parse(response);
                          var html = '';
                          $.each(data, function(key, value) {
                            html += "<tr>";
                            html += "<td>"+value.id_histo+"</td>";
                            html += "<td><strong>"+value.immat+"</strong></td>";
                            html += "<td>"+value.marque+"</td>";
                            html += "<td>"+value.modele+"</td>";
                            html += "<td>"+value.motorisation+"</td>";
                            html += "<td>" + (value.date_vidange != '------' ? value.date_vidange : '')+"</td>";
                            html += "<td>" + (value.date_ct != '------' ? value.date_ct : '')+"</td>";
                            html += "<td style='white-space: pre-wrap;'>"+value.commentaire+"</td>";
                            html += "</tr> ";
                          });
                          $("#car_selected tbody").html(html);
                        }
                      });
                    }

                    $(document).ready(function(){
                      // Charge la valeur par défaut de la combobox
                      loadData($("#selectSort").val());

                      $("#selectSort").on("change", function(){
                        var selectedValue = $(this).val();
                        loadData(selectedValue);
                      });
                    });

                  </script>
                </div>
              </div>
              <hr class="my-5" />
            <?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>