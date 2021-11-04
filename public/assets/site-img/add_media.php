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


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.css">


<style>

.modal-open .modal {
    overflow-x: hidden;
    overflow-y: auto;
    z-index: 9999999 !important;
}

.fade:not(.show) {
    opacity: 1;
        background: rgba(25, 25, 25, .5);
}


.modal.fade .modal-dialog {
    transition: -webkit-transform 0.3s ease-out;
    transition: transform 0.3s ease-out;
    transition: transform 0.3s ease-out, -webkit-transform 0.3s ease-out;
    -webkit-transform: translate(0, -50px);
    transform: translate(0, -50px);
    padding-top: 150px;
}


.modal-title {
    margin-bottom: 0;
    line-height: 1.5;
    display: none;
}

.popover {
  display: none;

}

</style>

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
            <h1>Add Media</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Main</a></li>
              <li class="breadcrumb-item active">View Media</li>
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
                <h3 class="card-title">Media</h3>
              </div>

              <form role="form" action="add_mediarecords.php?" method="post" enctype="multipart/form-data">
                <div class="card-body">

                  <div class="form-group">
                    <label for="x1">Media Name</label>
                    <input type="text" class="form-control" id="" name="x1" value="" required>
                  </div>


                  <div class="form-group">
                    <label for="fileToUpload">File input</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="fileToUpload" id="fileToUpload">
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                      </div>
                      <div class="input-group-append">
                        <span class="input-group-text" id="">Add Record</span>
                      </div>
                    </div>
                  </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <input type="submit" class="btn btn-primary" value="Upload">
                </div>
              </form>
            </div>
            <!-- /.card -->


          </div>





          <!--end-->










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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>



<script>
(function() {
	var content =
		"<p><img src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQzC_Ho_08G0m7PyxJOPLpPujM9UTLxvaE-5nXewscnqa3GMWjGwg' alt='Image result for summernote.js'></p><h1>Summernote</h1>";
	var $sumNote = $("#ta-1")
		.summernote({
			dialogsInBody: true,
			dialogsFade: true,
			height: "150px",
		})
		.data("summernote");
})();

</script>




</body>
</html>
