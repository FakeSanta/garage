<?php
  $admin = true;
	require 'config.php';
  if(empty($_SESSION['username'])){
    header("location:login");
  }
  $title = "Suivi du carburant | Decomble";
  $page = "suivi_plein";
?>
<?php ob_start(); ?>
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Consulter / </span>Suivi du carburant</h4>

              <!-- Basic Bootstrap Table -->
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                    <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                      <div class="card-title">
                        <h5 class="text-nowrap mb-2">Dépense en carburant</h5>
                        <span class="badge bg-label-warning rounded-pill">Année <?php echo date('Y')?></span>
                      </div>
                      <?php
                        $sql_N = $connect->prepare("SELECT SUM(cout_plein) AS total_cout_annee_en_cours FROM CARBURANT WHERE YEAR(date_plein) = YEAR(CURDATE())");
                        $sql_N->execute();
                        $value_N = $sql_N->fetch(PDO::FETCH_COLUMN);

                        $sql_LastY = $connect->prepare("SELECT SUM(cout_plein) AS total_cout_annee_en_cours FROM CARBURANT WHERE YEAR(date_plein) = YEAR(CURDATE()) -1");
                        $sql_LastY->execute();
                        $value_LastY = $sql_LastY->fetch(PDO::FETCH_COLUMN);

                        $difference = $connect->prepare("SELECT YEAR(CURDATE()) AS annee_en_cours, SUM(CASE WHEN YEAR(date_plein) = YEAR(CURDATE()) AND MONTH(date_plein) <= MONTH(CURDATE()) 
                                                        THEN cout_plein ELSE 0 END) AS total_cout_annee_en_cours, YEAR(CURDATE()) - 1 AS annee_precedente, SUM(CASE WHEN YEAR(date_plein) = YEAR(CURDATE()) - 1 AND MONTH(date_plein) <= MONTH(CURDATE()) THEN cout_plein ELSE 0 END) AS total_cout_annee_precedente, SUM(CASE WHEN YEAR(date_plein) = YEAR(CURDATE()) AND MONTH(date_plein) <= MONTH(CURDATE()) 
                                                        THEN cout_plein ELSE 0 END) - SUM(CASE WHEN YEAR(date_plein) = YEAR(CURDATE()) - 1 AND MONTH(date_plein) <= MONTH(CURDATE()) THEN cout_plein ELSE 0 END) AS difference_cout FROM CARBURANT");
                        $difference->execute();
                        $tempo = $difference->fetch(PDO::FETCH_ASSOC);
                      ?>
                      <div class="mt-sm-auto">
                        <small class="text-<?php if($value_N > $tempo['total_cout_annee_precedente']){ print('danger');}else{print('success');}?> text-nowrap fw-semibold"
                          ><i class="bx bx-chevron-<?php if($value_N > $tempo['total_cout_annee_precedente']){ print('up');}else{print('down');}?>"></i><?php if($value_N > $tempo['total_cout_annee_precedente']){ print('+');}else{print('-');}?> <?php echo str_replace('.',',',$tempo['difference_cout'])?> € (différence avec N-1 à la même date)</small
                        >
                        <h3 class="mb-0"><?php echo str_replace('.',',',$value_N)?> €</h3>
                      </div>
                    </div>
                    <div id="profileReportChart"></div>
                  </div>
                </div>
              </div>
              <hr class="my-5" />
              <div class="card">
                <h5 class="card-header">Parc Auto <?php echo $brend?></h5>
                  <div class="input-group input-group-merge">
                    <span id="basic-icon-default-fullname2" class="input-group-text"
                      ><i class="bx bx-calendar"></i
                    ></span>
                    <select class="form-select" name="selectSort" id="selectSort">
                    <?php
                        $fill_select = $connect->prepare('SELECT DISTINCT YEAR(date_plein) AS annee FROM CARBURANT ORDER BY YEAR(date_plein) DESC');
                        $fill_select->execute();
                        while($row = $fill_select->fetch(PDO::FETCH_ASSOC))
                        {
                    ?>
                        <option value="<?php print($row['annee'])?>"><?php print($row['annee'])?></option>
                    <?php
                        }
                    ?>
                    </select>
                  </div>
                <div class="table-responsive text-nowrap">
                  <table class="table table-hover" id="year_selected">
                    <thead>
                      <tr>
                        <th>Immatriculation</th>
                        <th>Modèle</th>
                        <th>Prix</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    </tbody>
                  </table>
                  <script>
                    function loadData(selectedValue) {
                      $.ajax({
                        url: "sort-plein.php",
                        type: "POST",
                        data: {id: selectedValue},
                        success: function(response){
                          console.log(response);
                          var data = JSON.parse(response);
                          var html = '';
                          $.each(data, function(key, value) {
                            html += "<tr>";
                            html += "<td>"+value.immatriculation+"</td>";
                            html += "<td><strong>"+value.modele+"</strong></td>";
                            html += "<td class='badge bg-label-";
                            if(value.motorisation === 'Diesel') {
                                html += "warning";
                            } else if(value.motorisation === 'Essence') {
                                html += "success";
                            } else if(value.motorisation === 'Electrique') {
                                html += "info";
                            } else {
                                html += "primary";
                            }
                            html += " me-1 rounded-pill'>" + value.cout_total.replace('.',',') + " €</td>";
                            html += "</tr> ";
                          });
                          $("#year_selected tbody").html(html);
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
              <!--/ Basic Bootstrap Table -->

              <hr class="my-5" />
            <!-- / Content -->
            <?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>