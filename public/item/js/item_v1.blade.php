<?php
include(app_path() . '/ShopFumaco/fumdata.php');
if(empty($_SESSION["buyer_id"])) {
$_SESSION["buyer_id"] = uniqid();
}
else {
  //echo $_SESSION["buyer_id"];
}

$con = mysqli_connect($DB_SERVER,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		die();
		}
$status="";


if (isset($_GET['action']) && $_GET['action']=="add"){

//add items
if (isset($_GET['code']) && $_GET['code']!=""){
$code = $_GET['code'];
$xqtyx = $_GET['dataqty'];
$result = mysqli_query(
$con,
"SELECT * FROM `fumaco_products` WHERE `code`='$code'"
);
$row = mysqli_fetch_assoc($result);
$name = $row['name'];
$code = $row['code'];
$price = $row['price'];
$image = $row['image'];


$cartArray = array(
 $code=>array(
 'name'=>$name,
 'code'=>$code,
 'price'=>$price,
 'quantity'=>$xqtyx,
 'image'=>$image)
);

if(empty($_SESSION["shopping_cart"])) {
    $_SESSION["shopping_cart"] = $cartArray;
    $status = '<div class="alert alert-success alert-dismissible fade show" role="alert">Product is added to your cart!</div>';
}else{
    $array_keys = array_keys($_SESSION["shopping_cart"]);
    if(in_array($code,$array_keys)) {
    $status = '<div class="alert alert-warning alert-dismissible fade show" role="alert">Product is already added to your cart!</div>';
    } else {
    $_SESSION["shopping_cart"] = array_merge(
    $_SESSION["shopping_cart"],
    $cartArray
    );
    $status = '<div class="alert alert-success alert-dismissible fade show" role="alert">Product is added to your cart!</div>';
 }
 }
}
//end of add items

}
//buy items

if (isset($_GET['action']) && $_GET['action']=="buy"){

  if (isset($_GET['code']) && $_GET['code']!=""){
  $code = $_GET['code'];
  $xqtyx = $_GET['dataqty'];
  $result = mysqli_query(
  $con,
  "SELECT * FROM `fumaco_products` WHERE `code`='$code'"
  );
  $row = mysqli_fetch_assoc($result);
  $name = $row['name'];
  $code = $row['code'];
  $price = $row['price'];
  $image = $row['image'];

  $cartArray = array(
   $code=>array(
   'name'=>$name,
   'code'=>$code,
   'price'=>$price,
   'quantity'=>$xqtyx,
   'image'=>$image)
  );

  if(empty($_SESSION["shopping_cart"])) {
      $_SESSION["shopping_cart"] = $cartArray;
      $status = '<div class="alert alert-success alert-dismissible fade show" role="alert">Product is added to your cart!</div>';
      header("Location:cart");
      exit();

  }else{
      $array_keys = array_keys($_SESSION["shopping_cart"]);
      if(in_array($code,$array_keys)) {
      $status = '<div class="alert alert-warning alert-dismissible fade show" role="alert">Product is already added to your cart!</div>';
      } else {
      $_SESSION["shopping_cart"] = array_merge(
      $_SESSION["shopping_cart"],
      $cartArray
      );
      $status = '<div class="alert alert-success alert-dismissible fade show" role="alert">Product is added to your cart!</div>';
      header("Location:cart");
      exit();
   }
   }
  }



}











?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Dhok Pochie">
    <title><?php echo $base_title_fumaco; ?></title>



		<link href="<?php echo url('/'); ?>/assets/dist/css/bootstrap.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>



		<link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="<?php echo url('/'); ?>/assets/fumaco.css" rel="stylesheet">

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script type="text/javascript" src="<?php echo url('/'); ?>/item/dist/xzoom.min.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo url('/'); ?>/item/dist/xzoom.css" media="all" />
		<script type="text/javascript" src="<?php echo url('/'); ?>/item/hammer.js/1.0.5/jquery.hammer.min.js"></script>
		<link type="text/css" rel="stylesheet" media="all" href="<?php echo url('/'); ?>/item/fancybox/source/jquery.fancybox.css" />
		<link type="text/css" rel="stylesheet" media="all" href="<?php echo url('/'); ?>/item/magnific-popup/css/magnific-popup.css" />
		<script type="text/javascript" src="<?php echo url('/'); ?>/item/fancybox/source/jquery.fancybox.js"></script>
		<script type="text/javascript" src="<?php echo url('/'); ?>/item/magnific-popup/js/magnific-popup.js"></script>



		<!--

	   -->

		<style>

    .spinner-wrapper {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #2e343a;
      z-index: 999999;
     padding-top: 15%;
    }
    .spinner {
      width: 40px;
      height: 40px;
      background-color: #0062A5;
      margin: 100px auto;
      -webkit-animation: sk-rotateplane 1.2s infinite ease-in-out;
      animation: sk-rotateplane 1.2s infinite ease-in-out;
}

@-webkit-keyframes sk-rotateplane {
  0% { -webkit-transform: perspective(120px) }
  50% { -webkit-transform: perspective(120px) rotateY(180deg) }
  100% { -webkit-transform: perspective(120px) rotateY(180deg)  rotateX(180deg) }
}

@keyframes sk-rotateplane {
  0% {
    transform: perspective(120px) rotateX(0deg) rotateY(0deg);
    -webkit-transform: perspective(120px) rotateX(0deg) rotateY(0deg)
  } 50% {
    transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg);
    -webkit-transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg)
  } 100% {
    transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);
    -webkit-transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);
  }
}




    </style>





    <style>

.breadcrumb-item+.breadcrumb-item::before {
content: ">"
}

.breadcrumb {
display: -ms-flexbox;
display: flex;
-ms-flex-wrap: wrap;
flex-wrap: wrap;
padding: .1rem 0rem !important;
margin-bottom: 0rem;
list-style: none;
background-color: #ffffff;
border-radius: .25rem
}

.single_product {
padding-top: 66px;
padding-bottom: 140px;
background-color: #ffffff;
margin-top: 0px;
padding: 17px
}

.product_name {
font-size: 20px;
font-weight: 400;
margin-top: 0px
}

.badge {
display: inline-block;
padding: 0.50em .4em;
font-size: 75%;
font-weight: 700;
line-height: 1;
text-align: center;
white-space: nowrap;
vertical-align: baseline;
border-radius: .25rem
}

.product-rating {
margin-top: 10px
}

.rating-review {
color: #5b5b5b
}

.product_price {
display: inline-block;
font-size: 30px;
font-weight: 500;
margin-top: 9px;
clear: left
}

.product_discount {
display: inline-block;
font-size: 17px;
font-weight: 300;
margin-top: 9px;
clear: left;
margin-left: 10px;
color: red
}

.product_saved {
display: inline-block;
font-size: 15px;
font-weight: 200;
color: #999999;
clear: left
}

.singleline {
margin-top: 1rem;
margin-bottom: .40rem;
border: 0;
border-top: 1px solid rgba(0, 0, 0, .1)
}

.product_info {
color: #4d4d4d;
display: inline-block
}

.product_options {
margin-bottom: 10px
}

.product_description {
padding-left: 0px
}

.product_quantity {
width: 104px;
height: 47px;
border: solid 1px #e5e5e5;
border-radius: 3px;
overflow: hidden;
padding-left: 8px;
padding-top: -4px;
padding-bottom: 44px;
float: left;
margin-right: 22px;
margin-bottom: 11px
}

.order_info {
margin-top: 18px
}

.shop-button {
height: 47px
}

.product_fav i {
line-height: 44px;
color: #cccccc
}

.product_fav {
display: inline-block;
width: 52px;
height: 46px;
background: #FFFFFF;
box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
border-radius: 11%;
text-align: center;
cursor: pointer;
margin-left: 3px;
-webkit-transition: all 200ms ease;
-moz-transition: all 200ms ease;
-ms-transition: all 200ms ease;
-o-transition: all 200ms ease;
transition: all 200ms ease
}

.br-dashed {
border-radius: 5px;
border: 1px dashed #dddddd;
margin-top: 6px
}

.pr-info {
margin-top: 2px;
padding-left: 2px;
margin-left: -14px;
padding-left: 0px
}

.break-all {
color: #5e5e5e
}

.image_selected {
display: -webkit-box;
display: -moz-box;
display: -ms-flexbox;
display: -webkit-flex;
display: flex;
flex-direction: column;
justify-content: center;
align-items: center;
width: calc(100% + 15px);
height: 525px;
-webkit-transform: translateX(-15px);
-moz-transform: translateX(-15px);
-ms-transform: translateX(-15px);
-o-transform: translateX(-15px);
transform: translateX(-15px);
border: solid 1px #e8e8e8;
box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.1);
overflow: hidden;
padding: 15px
}

.image_list li {
display: -webkit-box;
display: -moz-box;
display: -ms-flexbox;
display: -webkit-flex;
display: flex;
flex-direction: column;
justify-content: center;
align-items: center;
height: 165px;
border: solid 1px #e8e8e8;
box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.1) !important;
margin-bottom: 15px;
cursor: pointer;
padding: 15px;
-webkit-transition: all 200ms ease;
-moz-transition: all 200ms ease;
-ms-transition: all 200ms ease;
-o-transition: all 200ms ease;
transition: all 200ms ease;
overflow: hidden
}

@media (max-width: 390px) {
.product_fav {
display: none
}
}

.bbb_combo {
width: 100%;
margin-right: 7%;
padding-top: 21px;
padding-left: 20px;
padding-right: 20px;
padding-bottom: 24px;
border-radius: 5px;
margin-top: 0px;
text-align: -webkit-center
}

.bbb_combo_image {
width: 170px;
height: 170px;
margin-bottom: 15px
}

.fs-10 {
font-size: 10px
}

.step {
background: #167af6;
border-radius: 0.8em;
-moz-border-radius: 0.8em;
-webkit-border-radius: 6.8em;
color: #ffffff;
display: inline-block;
font-weight: bold;
line-height: 3.6em;
margin-right: 5px;
text-align: center;
width: 3.6em;
margin-top: 116px
}

.row-underline {
content: "";
display: block;
border-bottom: 2px solid #3798db;
margin: 0px 0px;
margin-bottom: 20px;
margin-top: 15px
}

.deal-text {
margin-left: -10px;
font-size: 25px;
margin-bottom: 10px;
color: #000;
font-weight: 700
}

.padding-0 {
padding-left: 0;
padding-right: 0
}

.padding-2 {
margin-right: 2px;
margin-left: 2px
}

.vertical-line {
display: inline-block;
border-left: 3px solid #167af6;
margin: 0 10px;
height: 364px;
margin-top: 4px
}

.p-rating {
color: green
}

.combo-pricing-item {
display: flex;
flex-direction: column
}

.boxo-pricing-items {
display: inline-flex
}

.combo-plus {
margin-left: 10px;
margin-right: 18px;
margin-top: 10px
}

.add-both-cart-button {
margin-left: 36px
}

.items_text {
color: #b0b0b0
}

.combo_item_price {
font-size: 18px
}

.p_specification {
font-weight: 500;
margin-left: 22px
}

.mt-10 {
margin-top: 10px
}



.single_product {
padding-top: 16px;
padding-bottom: 140px
}

.image_list li {
display: -webkit-box;
display: -moz-box;
display: -ms-flexbox;
display: -webkit-flex;
display: flex;
flex-direction: column;
justify-content: center;
align-items: center;
height: 165px;
border: solid 1px #e8e8e8;
box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
margin-bottom: 15px;
cursor: pointer;
padding: 15px;
-webkit-transition: all 200ms ease;
-moz-transition: all 200ms ease;
-ms-transition: all 200ms ease;
-o-transition: all 200ms ease;
transition: all 200ms ease;
overflow: hidden
}

.image_list li:last-child {
margin-bottom: 0
}

.image_list li:hover {
box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.3)
}

.image_list li img {
max-width: 100%
}

.image_selected {
display: -webkit-box;
display: -moz-box;
display: -ms-flexbox;
display: -webkit-flex;
display: flex;
flex-direction: column;
justify-content: center;
align-items: center;
width: calc(100% + 15px);
height: 525px;
-webkit-transform: translateX(-15px);
-moz-transform: translateX(-15px);
-ms-transform: translateX(-15px);
-o-transform: translateX(-15px);
transform: translateX(-15px);
border: solid 1px #e8e8e8;
box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
overflow: hidden;
padding: 15px
}

.image_selected img {
max-width: 100%
}

.product_category {
font-size: 12px;
color: rgba(0, 0, 0, 0.5)
}

.product_rating {
margin-top: 7px
}

.product_rating i {
margin-right: 4px
}

.product_rating i::before {
font-size: 13px
}

.product_text {
margin-top: 27px
}

.product_text p:last-child {
margin-bottom: 0px
}

.order_info {
margin-top: 16px
}

.product_quantity {
width: 182px;
height: 50px;
border: solid 1px #e5e5e5;
border-radius: 5px;
overflow: hidden;
padding-left: 25px;
float: left;
margin-right: 30px
}

.product_quantity span {
display: block;
height: 50px;
font-size: 16px;
font-weight: 300;
color: rgba(0, 0, 0, 0.5);
line-height: 50px;
float: left
}

.product_quantity input {
display: block;
width: 30px;
height: 50px;
border: none;
outline: none;
font-size: 16px;
font-weight: 300;
color: rgba(0, 0, 0, 0.5);
text-align: left;
padding-left: 9px;
line-height: 50px;
float: left
}

.quantity_buttons {
position: absolute;
top: 0;
right: 0;
height: 100%;
width: 29px;
border-left: solid 1px #e5e5e5
}

.quantity_inc,
.quantity_dec {
display: -webkit-box;
display: -moz-box;
display: -ms-flexbox;
display: -webkit-flex;
display: flex;
flex-direction: column;
align-items: center;
width: 100%;
height: 50%;
cursor: pointer
}

.quantity_control i {
font-size: 11px;
color: rgba(0, 0, 0, 0.3);
pointer-events: none
}

.quantity_control:active {
border: solid 1px rgba(14, 140, 228, 0.2)
}

.quantity_inc {
padding-bottom: 2px;
justify-content: flex-end;
border-top-right-radius: 5px
}

.quantity_dec {
padding-top: 2px;
justify-content: flex-start;
border-bottom-right-radius: 5px
}




.products-head {




  margin-top: 110px !important;
padding-left: 40px !important;
padding-right: 40px !important;

}


.products-head2 {




  margin-top: 0px !important;
padding-left: 40px !important;
padding-right: 40px !important;

}




.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0 !important;
    border-radius: 0 !important;
}



.btn-link {
    font-weight: 200;
    color: #373B3E;
    text-decoration: none;
}


.product-item-head-caption {
    font-weight: 200 !important;
    font-size: 14px!important;
    letter-spacing: 1px !important;
}





</style>

<!--
<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script type='text/javascript' src='https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js'></script>
-->


  </head>
  <body>
  <header>

    <?php include(app_path() . '/ShopFumaco/Header/navbar.php'); ?>

</header>

<?php

$fumaco_products_conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

if ($fumaco_products_conn->connect_error) {

  die();

}


$id_data = $_GET['id'];
$homebestselling = "SELECT * FROM fumaco_products WHERE id = '$id_data '";
$data_display3 = $fumaco_products_conn ->query($homebestselling);

if ($data_display3->num_rows > 0) {

while($data_3 = $data_display3->fetch_assoc())
    {
      $item_data00_fumaco = $data_3["code"];
      $item_data0_fumaco = $data_3["id"];
      $item_data1_fumaco = $data_3["name"];
      $item_data2_fumaco = $data_3["details"];
      $item_data3_fumaco = $data_3["image"];
      $item_data4_fumaco = $data_3["price"];
      $item_data5_fumaco = $data_3["star_f"];
      $item_data6_fumaco = $data_3["review_f"];
      $item_data7_fumaco = $data_3["brand"];
      $item_data8_fumaco = $data_3["quantity"];
      $item_data9_fumaco = $data_3["product_details"];
      $item_data10_fumaco = $data_3["categoryname"];
      $item_data11_fumaco = $data_3["combine_product_id"];
      $formatamountx3 =  number_format("$item_data4_fumaco",2);


    }
  }
else {
die();
}

?>




<main style="background-color:#ffffff;" class="products-head">

  <nav>
      <ol class="breadcrumb" style="font-weight: 300 !important;
    font-size: 14px !important;">
          <li class="breadcrumb-item"><a href="#" style="color: #000000 !important; text-decoration: underline;">Home</a></li>
          <li class="breadcrumb-item"><a href="#" style="color: #000000 !important; text-decoration: underline;">Products</a></li>
          <li class="breadcrumb-item active"><?php echo $item_data10_fumaco; ?></li>
          <li class="breadcrumb-item active"><?php echo $item_data7_fumaco; ?></li>
      </ol>
  </nav>





</main>



<br>



  <div class="container">





    <!-- fancy start -->



    <!-- fancy end -->



  </div>









	<main style="background-color:#ffffff;">

	  <div class="container marketing">

	  <div class="single_product" style="padding-bottom: 0px !important;">
	      <div class="container-fluid" style=" background-color: #fff; padding: 11px;">




						<div class="row">


							  <div class="col-lg-6">


									<div class="xzoom-container" style="width: 100% !important;">


										<img style="width: 100% !important;" class="xzoom4" id="xzoom-fancy" src="<?php echo url('/'); ?>/item/images/gallery/preview/01_b_car.jpg" xoriginal="<?php echo url('/'); ?>/item/images/gallery/original/01_b_car.jpg"  />


										<br><br>
										<div class="xzoom-thumbs">
											<a href="<?php echo url('/'); ?>/item/images/gallery/original/01_b_car.jpg"><img class="xzoom-gallery4" width="80" src="<?php echo url('/'); ?>/item/images/gallery/thumbs/01_b_car.jpg"  xpreview="<?php echo url('/'); ?>/item/images/gallery/preview/01_b_car.jpg"></a>
											<a href="<?php echo url('/'); ?>/item/images/gallery/original/02_o_car.jpg"><img class="xzoom-gallery4" width="80" src="<?php echo url('/'); ?>/item/images/gallery/preview/02_o_car.jpg"></a>
											<a href="<?php echo url('/'); ?>/item/images/gallery/original/03_r_car.jpg"><img class="xzoom-gallery4" width="80" src="<?php echo url('/'); ?>/item/images/gallery/preview/03_r_car.jpg"></a>
											<a href="<?php echo url('/'); ?>/item/images/gallery/original/04_g_car.jpg"><img class="xzoom-gallery4" width="80" src="<?php echo url('/'); ?>/item/images/gallery/preview/04_g_car.jpg"></a>
										</div>

									</div>




							  </div>


								<div class="col-lg-6 order-3">
	                  <div class="product_description">

	                      <div class="product_name"><?php //echo $item_data1_fumaco;?>Products Name Here</div>
	                      <div class="product-rating">

	                        <div class="d-flex justify-content-between align-items-center">
	                          <div class="btn-group stylecap">
	                            <span class="fa fa-star checked starcolor"></span>
	                            <span class="fa fa-star checked starcolor"></span>
	                            <span class="fa fa-star checked starcolor"></span>
	                            <span class="fa fa-star starcolorgrey"></span>
	                            <span class="fa fa-star starcolorgrey"></span>

	                            <span class="" style="color:#000000 !important; font-weight:200 !important;">&nbsp;&nbsp;( <?php echo $item_data6_fumaco; ?> Reviews )</span>
	                          </div>

	                        </div>

	                      </div>
	                      <div>
	                         <span class="product_price">₱ <?php echo $formatamountx3;?></span>
	                         <!--
	                          <strike class="product_discount">
	                           <span style='color:black'>₹ 2,000<span>
	                           </strike>
	                         -->
	                     </div>
	                      <div>
	                        <!--<span class="product_saved">You Saved:</span> <span style='color:black'>₹ 2,000<span>-->
	                        <p class="card-text product-head-caption"><?php //echo $item_data2_fumaco; ?> CRS Steel Coil , 2440 mm, 1220 mm, 0.5 MM</p>

	                      <p class="card-text">QTY&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;   <input type="number" value="<?php echo $_GET['dataqty']; ?>" id="quantity" name="quantity" min="1" max="50" style="width: 70px;" onchange="get_cnt()"> </p>
	                        <p class="card-text product-bell-caption">In-Stocks : <?php //echo $item_data8_fumaco; ?> Available &nbsp;&nbsp; <i class="fas fa-bell"></i></p>
	                      </div>


	                      <hr class="singleline">

	                      <div>
	                          <!--
	                          <div class="row">
	                              <div class="col-md-5">
	                                  <div class="br-dashed">
	                                      <div class="row">
	                                          <div class="col-md-3 col-xs-3"> <img src="https://img.icons8.com/color/48/000000/price-tag.png"> </div>
	                                          <div class="col-md-9 col-xs-9">
	                                              <div class="pr-info"> <span class="break-all">Get 5% instant discount + 10X rewards @ RENTOPC</span> </div>
	                                          </div>
	                                      </div>
	                                  </div>
	                              </div>
	                              <div class="col-md-7"> </div>
	                          </div>
	                        -->



	                          <div class="row" style="margin-top: 15px;">


																  <div class="col-xs-6"> <span class="product_options"></span>



				</div>

	                              <div class="col-xs-6">


																	<form>
	  <div class="row">


			<div class="col">
				<label for="exampleFormControlSelect1">Color Temperature	</label>
		    <select class="form-control" id="exampleFormControlSelect1">
		      <option>4000K</option>
		      <option>2</option>
		      <option>3</option>
		      <option>4</option>
		      <option>5</option>
		    </select>
	    </div>


			<div class="col">
				<label for="exampleFormControlSelect1">Wattage	</label>
				<select class="form-control" id="exampleFormControlSelect1">
				<option>40 Watts</option>
				<option>60 Watts</option>
				<option>120 Watts</option>
				<option>140 Watts</option>
				<option>200 Watts</option>
				</select>
	    </div>



	  </div>
	</form>

	<br>

	<form>
	<div class="row">


	<div class="col">
	<label for="exampleFormControlSelect1">Voltage</label>
	<select class="form-control" id="exampleFormControlSelect1">
	<option>230V, 50/60Hz</option>
	<option>2</option>
	<option>3</option>
	<option>4</option>
	<option>5</option>
	</select>
	</div>


	<div class="col">
	<label for="exampleFormControlSelect1">Lamp Type</label>
	<select class="form-control" id="exampleFormControlSelect1">
	<option>LED</option>
	<option>LAMP</option>

	</select>
	</div>



	</div>
	</form>





	                             </div>

	                          </div>
	                      </div>

	                      <br>

	                      <div>
	                        <!--<span class="product_saved">You Saved:</span> <span style='color:black'>₹ 2,000<span>-->
	                      <!--  <p class="card-text product-bell-caption">Availability : 5 Only &nbsp;&nbsp; <i class="fas fa-bell"></i></p>-->



	                     </div>

	                      <hr class="singleline">
	                      <div class="order_info d-flex flex-row">
	                          <form action="#">
	                      </div>
	                      <div class="row">

	                          <div class="col-xs-6">




	                            <a class="btn btn-lg btn-outline-primary" href="item?id=<?php echo $id_data;?>&code=<?php echo $item_data00_fumaco;?>&action=add&dataqty=<?php echo $_GET['dataqty']; ?>" role="button">Add to Cart</a>
	                            <a class="btn btn-lg btn-outline-primary" href="item?id=<?php echo $id_data;?>&code=<?php echo $item_data00_fumaco;?>&action=buy&dataqty=<?php echo $_GET['dataqty']; ?>" role="button"><i class="fas fa-shopping-cart"></i>&nbsp;&nbsp;&nbsp;Buy Now</a>


	                            <script>


	                            function get_cnt() {

	                                var x_qty = document.getElementById("quantity").value;

	                                window.location.href = "item?id=<?php echo $_GET['id'];?>&dataqty=" + (x_qty);


	                            }


	                            </script>
	                            <div class="message_box" style="margin:10px 0px;">


	                              <?php echo $status; ?>




	                            <!--<button type="button" class="btn btn-lg btn-outline-primary">Add to Cart</button>
	                            <button type="button" class="btn btn-lg btn-outline-primary"><i class="fas fa-shopping-cart"></i>&nbsp;&nbsp;&nbsp;Buy Now</button>-->

	                            <!--  <div class="product_fav"><i class="fas fa-heart"></i></div> -->
	                          </div>
	                      </div>

	                      <div class="row">

	                          <!--<p class="card-text product-bell-caption">Share &nbsp;<i class="fab fa-facebook-f"></i> &nbsp; &nbsp;<i class="fab fa-instagram"></i>&nbsp; &nbsp;<i class="fab fa-twitter"></i></p>-->
	                          <br>
	                      </div>

	                  </div>
	              </div>
	          </div>


	      </div>



	  </div>

	</div>




	</main>










<main style="background-color:#0062A5;">
  <br>
</main>

<?php include(app_path() . '/ShopFumaco/Footer/footer.php');?>
<script src="<?php echo url('/'); ?>/assets/dist/js/bootstrap.bundle.js"></script>
<script>


$('.btn-number').click(function(e){
    e.preventDefault();

    fieldName = $(this).attr('data-field');
    type      = $(this).attr('data-type');
    var input = $("input[name='"+fieldName+"']");
    var currentVal = parseInt(input.val());
    if (!isNaN(currentVal)) {
        if(type == 'minus') {

            if(currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            }
            if(parseInt(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }

        } else if(type == 'plus') {

            if(currentVal < input.attr('max')) {
                input.val(currentVal + 1).change();
            }
            if(parseInt(input.val()) == input.attr('max')) {
                $(this).attr('disabled', true);
            }

        }
    } else {
        input.val(0);
    }
});
$('.input-number').focusin(function(){
   $(this).data('oldValue', $(this).val());
});
$('.input-number').change(function() {

    minValue =  parseInt($(this).attr('min'));
    maxValue =  parseInt($(this).attr('max'));
    valueCurrent = parseInt($(this).val());

    name = $(this).attr('name');
    if(valueCurrent >= minValue) {
        $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the minimum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    if(valueCurrent <= maxValue) {
        $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the maximum value was reached');
        $(this).val($(this).data('oldValue'));
    }


});
$(".input-number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

</script>
<script>

$(document).ready(function() {
//Preloader
preloaderFadeOutTime = 800;
 function hidePreloader() {
   var preloader = $('.spinner-wrapper');
  preloader.fadeOut(preloaderFadeOutTime);
 }
hidePreloader();
});
</script>


<script src="<?php echo url('/'); ?>/item/js/foundation.min.js"></script>
<script src="<?php echo url('/'); ?>/item/js/setup.js"></script>








</body>
</html>
