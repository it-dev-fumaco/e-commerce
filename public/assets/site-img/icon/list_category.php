<?php

session_start();


if(!isset($_SESSION["adminloggedin"]) || $_SESSION["adminloggedin"] !== true){
    header("location: https://test.fumaco.com.ph/server/admin/v1/login.php");
    exit;
}
?>
<?php include 'config.php';?>
<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {

}




?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Fumaco | Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="dist/css/fumacoadmin.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">


<?php include 'nav.php';?>

<?php include 'side.php';?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">








    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>List Category Page</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Main</a></li>
              <li class="breadcrumb-item active">List Category Page</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">


        <div class="row">

          <div class="col-md-12">

            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">List Category</h3>
              </div>




                <div class="card-body">
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                      <th>Code</th>
                      <th>Name</th>
                      <th>image</th>
                      <th>slug</th>
                      <th>Action</th>

                    </tr>
                    </thead>
                    <tbody>


                      <?php
                      //$id_data = $_GET['id'];

                      $A1_sql = "SELECT * FROM fumaco_categories";
                      $data1_x = $fumaco_conn ->query($A1_sql);

                      if ($data1_x->num_rows > 0) {

                      while($data_1 = $data1_x->fetch_assoc())
                          {

                            $item_data0_fumaco = $data_1['id'];
                            $item_data1_fumaco = $data_1['name'];
                            $item_data2_fumaco = $data_1['image'];
                            $item_data3_fumaco = $data_1['slug'];

                                              echo '
                                                  <tr>
                                                    <td>'.$item_data0_fumaco.'</td>
                                                    <td>'.$item_data1_fumaco.'</td>
                                                    <td>'.$item_data2_fumaco.'</td>
                                                    <td>'.$item_data3_fumaco.'</td>



                                                    <td>



                                                    <button type="button" class="btn btn-info btn-sm active" data-toggle="modal" data-target="#PPPEdit'.$item_data0_fumaco.'">Edit</button>


                                                    <a href="delete_category.php?id='.$item_data0_fumaco.'" class="btn btn-danger btn-sm active" role="button" aria-pressed="true">Delete</a>



                                                    <div id="PPPEdit'.$item_data0_fumaco.'" class="modal fade" role="dialog">
                                                      <div class="modal-dialog">
                                                        <div class="modal-content">
                                                          <div class="modal-header">

                                                          <h4 class="modal-title">Edit : '.$item_data0_fumaco.'</h4>

                                                          <button type="button" class="close" data-dismiss="modal">&times;</button>


                                                          </div>
                                                          <div class="modal-body">





                                                          <div class="col-md-12">

                                                            <div class="card card-primary">
                                                              <div class="card-header">
                                                                <h3 class="card-title">Add Category</h3>
                                                              </div>

                                                              <form role="form" action="edit-category.php?id='.$item_data0_fumaco.'" method="post"  >
                                                                <div class="card-body">

                                                                  <div class="form-group">
                                                                    <label for="x1">Category Name : </label>
                                                                    <input type="text" class="form-control" id="x1" name="x1" value="'.$item_data1_fumaco.'" required>
                                                                  </div>


                                                                  <div class="form-group">
                                                                    <label for="x2">Image : </label>
                                                                    <!--<input type="text" class="form-control" id="x2" name="x2" value="" required>-->
                                                                    <br>

                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav1.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav1.jpg" width="30" ></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav2.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav2.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav3.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav3.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav4.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav4.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav5.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav5.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav6.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav6.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav7.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav7.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav8.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav8.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav9.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav9.jpg" width="30"></label>
                                                                    </div>

                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav10.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav10.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav11.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav11.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav12.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav12.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav13.jpg">
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav13.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav14.jpg">
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav14.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav16.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav16.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav17.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav17.jpg" width="30"></label>
                                                                    </div>

                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav18.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav18.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav19.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav19.jpg" width="30"></label>
                                                                    </div>



                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_27_Fumaco-Water.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_27_Fumaco-Water.jpg" width="30"></label>
                                                                    </div>



                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_26_Wall-lights.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_26_Wall-lights.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_25_Tracklights.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_25_Tracklights.jpg" width="30"></label>
                                                                    </div>



                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_24_Striplights.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_24_Striplights.jpg" width="30"></label>
                                                                    </div>



                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_23_Bollard.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_23_Bollard.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_22_Downlight-Recessed.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_22_Downlight-Recessed.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_21_Electrical-Boxes.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_21_Electrical-Boxes.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_20_Sockets.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_20_Sockets.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_19_Switches.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_19_Switches.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_18_Panel-Board.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_18_Panel-Board.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_17_Circuit-Breaker.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_17_Circuit-Breaker.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_16_Batten Type.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_16_Batten Type.jpg" width="30"></label>
                                                                    </div>


                                                                    <div class="form-check form-check-inline">
                                                                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_15_IP-rated-Luminaire.jpg" required>
                                                                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_15_IP-rated-Luminaire.jpg" width="30"></label>
                                                                    </div>





                                                                  </div>


                                                                  <div class="form-group">
                                                                    <label for="x3">Slug : </label>
                                                                    <input type="text" class="form-control" id="x3" name="x3" value="'.$item_data3_fumaco.'">
                                                                  </div>



                                                                </div>
                                                                <!-- /.card-body -->

                                                                <div class="card-footer">
                                                                    <input type="submit" class="btn btn-primary" value="Update">
                                                                </div>
                                                              </form>
                                                            </div>
                                                            <!-- /.card -->




                                                          </div>
























                                                          </div>
                                                          <div class="modal-footer">
                                                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                          </div>
                                                          </div>

                                                      </div>
                                                    </div>




                                                    </td>




                                                  </tr>
                                              ';





                        }
                      }
                      else {

                      }

                      ?>











                    </tbody>

                  </table>
                </div>



            </div>





          </div>



          <div class="col-md-12">

            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Category</h3>
              </div>

              <form role="form" action="add-category.php" method="post" >
                <div class="card-body">

                  <div class="form-group">
                    <label for="x1">Category Name : </label>
                    <input type="text" class="form-control" id="x1" name="x1" value="" required>
                  </div>


                  <div class="form-group">
                    <label for="x2">Image : </label>
                    <!--<input type="text" class="form-control" id="x2" name="x2" value="" required>-->
                    <br>

                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav1.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav1.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav2.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav2.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav3.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav3.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav4.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav4.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav5.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav5.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav6.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav6.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav7.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav7.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav8.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav8.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav9.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav9.jpg" width="30"></label>
                    </div>

                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav10.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav10.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav11.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav11.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav12.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav12.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav13.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav13.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav14.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav14.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav16.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav16.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav17.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav17.jpg" width="30"></label>
                    </div>

                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav18.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav18.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="nav19.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/nav19.jpg" width="30"></label>
                    </div>




                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_27_Fumaco-Water.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_27_Fumaco-Water.jpg" width="30"></label>
                    </div>



                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_26_Wall-lights.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_26_Wall-lights.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_25_Tracklights.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_25_Tracklights.jpg" width="30"></label>
                    </div>



                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_24_Striplights.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_24_Striplights.jpg" width="30"></label>
                    </div>



                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_23_Bollard.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_23_Bollard.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_22_Downlight-Recessed.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_22_Downlight-Recessed.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_21_Electrical-Boxes.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_21_Electrical-Boxes.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_20_Sockets.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_20_Sockets.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_19_Switches.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_19_Switches.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_18_Panel-Board.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_18_Panel-Board.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_17_Circuit-Breaker.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_17_Circuit-Breaker.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_16_Batten Type.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_16_Batten Type.jpg" width="30"></label>
                    </div>


                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="x2" id="x2" value="icons_15_IP-rated-Luminaire.jpg">
                      <label class="form-check-label" for="inlineRadio1"><img src="https://test.fumaco.com.ph/assets/site-img/icon/icons_15_IP-rated-Luminaire.jpg" width="30"></label>
                    </div>








                  </div>


                  <div class="form-group">
                    <label for="x3">Slug : </label>
                    <input type="text" class="form-control" id="x3" name="x3" value="">
                  </div>



                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <input type="submit" class="btn btn-primary" value="Add Category">
                </div>
              </form>
            </div>
            <!-- /.card -->




          </div>






        </div>

      </div>
    </section>

  </div>

<?php include 'footer.php';?>


  <aside class="control-sidebar control-sidebar-dark">

  </aside>

</div>


<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>



<script src="plugins/jquery/jquery.min.js"></script>

<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

<script src="dist/js/fumaco.js"></script>

<script type="text/javascript">
$(document).ready(function () {
  bsCustomFileInput.init();
});
</script>
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true,
      "autoWidth": false,
    });
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>



<script>
function myFunction() {

  alert("<?php echo $displayx;?>");
  window.location.href = "home-page.php";
  return false;


}

</script>


</body>
</html>
