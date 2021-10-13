@extends('frontend.layout', [
	'namePage' => 'Checkout - Customer Form',
	'activePage' => 'checkout_customer_form'
])

@section('content')
	<style>
		.products-head {
			margin-top: 10px !important;
			padding-left: 40px !important;
			padding-right: 40px !important;
		}
		.he1 {
			font-weight: 300 !important;
			font-size: 12px !important;
		}
		.he2 {
			font-weight: 200 !important;
			font-size: 10px !important;
		}
		.he1x {
			font-weight: 600 !important;
			font-size: 14px !important;
		}
		.he2x {
			font-weight: 200 !important;
			font-size: 12px !important;
		}
		.he2x2 {
			font-weight: 500 !important;
			font-size: 14px !important;
		}
		.formslabelfnt {
			font-weight: 400 !important;
			font-size: 14px !important;
		}
		.btmp {
			margin-bottom: 15px !important;
		}
		.tbls {
			padding-bottom: 25px !important;
			padding-top: 25px !important;
		}
	</style>
	
	<main style="background-color:#0062A5;">
		<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-inner">
				<div class="carousel-item active" style="height: 13rem !important;">
					<img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important;">
					<div class="container">
						<div class="carousel-caption text-start" style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
							<center><h3 class="carousel-header-font">SHOPPING CART</h3></center>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>

	<main style="background-color:#ffffff;" class="products-head">
		<nav>
			<ol class="breadcrumb" style="font-weight: 300 !important; font-size: 14px !important;">
				<li class="breadcrumb-item">
					<a href="#" style="color: #000000 !important; text-decoration: none;">Review Your Orders</a>
				</li>
				<li class="breadcrumb-item">
					<a href="#" style="color: #000000 !important; text-decoration: underline;">Billing & Shipping Address</a>
				</li>
				<li class="breadcrumb-item active">
					<a href="#" style="color: #c1bdbd !important; text-decoration: none;">Place Order</a>
				</li>
			</ol>
		</nav>
	</main>
	
	<main style="background-color:#ffffff; min-height: 700px;" class="products-head">
		<div class="container-fluid">
			@php
				if(!Auth::check()){
					$action = '/checkout/summary';
				}else{
					$action = '/checkout/set_address';
				}
			@endphp
		
			<form action="{{ $action }}" method="post">
				@csrf
				<div class="row">
					<div class="col-md-8">
						<table class="table">
							<tr>
								<td class='col-md-9'>
									<p style="color:#212529 !important; letter-spacing: 1px !important; font-size:16px !important;  text-align: justify !important; font-weight: 600 !important;">Shipping Information</p>
								</td>
								@if(!Auth::check())
									<td>
										Already a member? <a href="">Log in</a>
									</td>
								@endif
							</tr>
						</table>
						<div class="row">
							<div class="col">
								<label for="fname" class="formslabelfnt">First Name : *</label>
								<input type="hidden" class="form-control formslabelfnt" id="logtype" name="logtype" value="1" required>
								<input type="text" class="form-control formslabelfnt" id="fname" name="fname" required>
							</div>
							<div class="col">
								<label for="lname" class="formslabelfnt">Last Name : *</label>
								<input type="text" class="form-control formslabelfnt" id="lname" name="lname" required>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col">
								<label for="Address1_1" class="formslabelfnt">Address Line 1 : *</label>
								<input type="text" class="form-control formslabelfnt" id="ship_Address1_1" name="ship_Address1_1" required>
							</div>
							<div class="col">
								<label for="Address2_1" class="formslabelfnt">Address Line 2 : </label>
								<input type="text" class="form-control formslabelfnt" id="Address2_1" name="ship_Address2_1">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col">
								<label for="province1_1" class="formslabelfnt">Province : *</label>
								<input type="text" class="form-control formslabelfnt" id="ship_province1_1" name="ship_province1_1" required>
							</div>
							<div class="col">
								<label for="City_Municipality1_1" class="formslabelfnt">City / Municipality : *</label>
								<input type="text" class="form-control formslabelfnt" id="ship_City_Municipality1_1" name="ship_City_Municipality1_1" required>
							</div>
							<div class="col">
								<label for="Barangay1_1" class="formslabelfnt">Barangay : *</label>
								<input type="text" class="form-control formslabelfnt" id="ship_Barangay1_1" name="ship_Barangay1_1" required>
							</div>
						</div>
						<br>
						<div class="row">{{-- Country Select Row --}}
							<div class="col">
								<label for="postal1_1" class="formslabelfnt">Postal Code : *</label>
								<input type="text" class="form-control formslabelfnt" id="ship_postal1_1" name="ship_postal1_1" required>
							</div>
							<div class="col">
								<label for="country_region1_1" class="formslabelfnt">Country / Region : *</label>
								<select class="form-control formslabelfnt" id="ship_country_region1_1" name="ship_country_region1_1" required>
									<option selected disabled value="">Choose...</option>
									<option value="Afghanistan">Afghanistan</option>
									<option value="Aland Islands">Aland Islands</option>
									<option value="Albania">Albania</option>
									<option value="Algeria">Algeria</option>
									<option value="American Samoa">American Samoa</option>
									<option value="Andorra">Andorra</option>
									<option value="Angola">Angola</option>
									<option value="Anguilla">Anguilla</option>
									<option value="Antarctica">Antarctica</option>
									<option value="Antigua and Barbuda">Antigua and Barbuda</option>
									<option value="Argentina">Argentina</option>
									<option value="Armenia">Armenia</option>
									<option value="Aruba">Aruba</option>
									<option value="Australia">Australia</option>
									<option value="Austria">Austria</option>
									<option value="Azerbaijan">Azerbaijan</option>
									<option value="Bahamas">Bahamas</option>
									<option value="Bahrain">Bahrain</option>
									<option value="Bangladesh">Bangladesh</option>
									<option value="Barbados">Barbados</option>
									<option value="Belarus">Belarus</option>
									<option value="Belgium">Belgium</option>
									<option value="Belize">Belize</option>
									<option value="Benin">Benin</option>
									<option value="Bermuda">Bermuda</option>
									<option value="Bhutan">Bhutan</option>
									<option value="Bolivia">Bolivia</option>
									<option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
									<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
									<option value="Botswana">Botswana</option>
									<option value="Bouvet Island">Bouvet Island</option>
									<option value="Brazil">Brazil</option>
									<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
									<option value="Brunei Darussalam">Brunei Darussalam</option>
									<option value="Bulgaria">Bulgaria</option>
									<option value="Burkina Faso">Burkina Faso</option>
									<option value="Burundi">Burundi</option>
									<option value="Cambodia">Cambodia</option>
									<option value="Cameroon">Cameroon</option>
									<option value="Canada">Canada</option>
									<option value="Cape Verde">Cape Verde</option>
									<option value="Cayman Islands">Cayman Islands</option>
									<option value="Central African Republic">Central African Republic</option>
									<option value="Chad">Chad</option>
									<option value="Chile">Chile</option>
									<option value="China">China</option>
									<option value="Christmas Island">Christmas Island</option>
									<option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
									<option value="Colombia">Colombia</option>
									<option value="Comoros">Comoros</option>
									<option value="Congo">Congo</option>
									<option value="Democratic Republic of the Congo">Democratic Republic of the Congo</option>
									<option value="Cook Islands">Cook Islands</option>
									<option value="Costa Rica">Costa Rica</option>
									<option value="Cote D'Ivoire">Cote D'Ivoire</option>
									<option value="Croatia">Croatia</option>
									<option value="Cuba">Cuba</option>
									<option value="Curacao">Curacao</option>
									<option value="Cyprus">Cyprus</option>
									<option value="Czech Republic">Czech Republic</option>
									<option value="Denmark">Denmark</option>
									<option value="Djibouti">Djibouti</option>
									<option value="Dominica">Dominica</option>
									<option value="Dominican Republic">Dominican Republic</option>
									<option value="Ecuador">Ecuador</option>
									<option value="Egypt">Egypt</option>
									<option value="El Salvador">El Salvador</option>
									<option value="Equatorial Guinea">Equatorial Guinea</option>
									<option value="Eritrea">Eritrea</option>
									<option value="Estonia">Estonia</option>
									<option value="Ethiopia">Ethiopia</option>
									<option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
									<option value="Faroe Islands">Faroe Islands</option>
									<option value="Fiji">Fiji</option>
									<option value="Finland">Finland</option>
									<option value="France">France</option>
									<option value="French Guiana">French Guiana</option>
									<option value="French Polynesia">French Polynesia</option>
									<option value="French Southern Territories">French Southern Territories</option>
									<option value="Gabon">Gabon</option>
									<option value="Gambia">Gambia</option>
									<option value="Georgia">Georgia</option>
									<option value="Germany">Germany</option>
									<option value="Ghana">Ghana</option>
									<option value="Gibraltar">Gibraltar</option>
									<option value="Greece">Greece</option>
									<option value="Greenland">Greenland</option>
									<option value="Grenada">Grenada</option>
									<option value="Guadeloupe">Guadeloupe</option>
									<option value="Guam">Guam</option>
									<option value="Guatemala">Guatemala</option>
									<option value="Guernsey">Guernsey</option>
									<option value="Guinea">Guinea</option>
									<option value="Guinea-Bissau">Guinea-Bissau</option>
									<option value="Guyana">Guyana</option>
									<option value="Haiti">Haiti</option>
									<option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
									<option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
									<option value="Honduras">Honduras</option>
									<option value="Hong Kong">Hong Kong</option>
									<option value="Hungary">Hungary</option>
									<option value="Iceland">Iceland</option>
									<option value="India">India</option>
									<option value="Indonesia">Indonesia</option>
									<option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
									<option value="Iraq">Iraq</option>
									<option value="Ireland">Ireland</option>
									<option value="Isle of Man">Isle of Man</option>
									<option value="Israel">Israel</option>
									<option value="Italy">Italy</option>
									<option value="Jamaica">Jamaica</option>
									<option value="Japan">Japan</option>
									<option value="Jersey">Jersey</option>
									<option value="Jordan">Jordan</option>
									<option value="Kazakhstan">Kazakhstan</option>
									<option value="Kenya">Kenya</option>
									<option value="Kiribati">Kiribati</option>
									<option value="Korea, Democratic People"s Republic of">Korea, Democratic People"s Republic of</option>
									<option value="Korea, Republic of">Korea, Republic of</option>
									<option value="Kosovo">Kosovo</option>
									<option value="Kuwait">Kuwait</option>
									<option value="Kyrgyzstan">Kyrgyzstan</option>
									<option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
									<option value="Latvia">Latvia</option>
									<option value="Lebanon">Lebanon</option>
									<option value="Lesotho">Lesotho</option>
									<option value="Liberia">Liberia</option>
									<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
									<option value="Liechtenstein">Liechtenstein</option>
									<option value="Lithuania">Lithuania</option>
									<option value="Luxembourg">Luxembourg</option>
									<option value="Macao">Macao</option>
									<option value="Macedonia, the Former Yugoslav Republic of">Macedonia, the Former Yugoslav Republic of</option>
									<option value="Madagascar">Madagascar</option>
									<option value="Malawi">Malawi</option>
									<option value="Malaysia">Malaysia</option>
									<option value="Maldives">Maldives</option>
									<option value="Mali">Mali</option>
									<option value="Malta">Malta</option>
									<option value="Marshall Islands">Marshall Islands</option>
									<option value="Martinique">Martinique</option>
									<option value="Mauritania">Mauritania</option>
									<option value="Mauritius">Mauritius</option>
									<option value="Mayotte">Mayotte</option>
									<option value="Mexico">Mexico</option>
									<option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
									<option value="Moldova, Republic of">Moldova, Republic of</option>
									<option value="Monaco">Monaco</option>
									<option value="Mongolia">Mongolia</option>
									<option value="Montenegro">Montenegro</option>
									<option value="Montserrat">Montserrat</option>
									<option value="Morocco">Morocco</option>
									<option value="Mozambique">Mozambique</option>
									<option value="Myanmar">Myanmar</option>
									<option value="Namibia">Namibia</option>
									<option value="Nauru">Nauru</option>
									<option value="Nepal">Nepal</option>
									<option value="Netherlands">Netherlands</option>
									<option value="Netherlands Antilles">Netherlands Antilles</option>
									<option value="New Caledonia">New Caledonia</option>
									<option value="New Zealand">New Zealand</option>
									<option value="Nicaragua">Nicaragua</option>
									<option value="Niger">Niger</option>
									<option value="Nigeria">Nigeria</option>
									<option value="Niue">Niue</option>
									<option value="Norfolk Island">Norfolk Island</option>
									<option value="Northern Mariana Islands">Northern Mariana Islands</option>
									<option value="Norway">Norway</option>
									<option value="OM">Oman</option>
									<option value="Oman">Pakistan</option>
									<option value="Palau">Palau</option>
									<option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
									<option value="Panama">Panama</option>
									<option value="Papua New Guinea">Papua New Guinea</option>
									<option value="Paraguay">Paraguay</option>
									<option value="Peru">Peru</option>
									<option value="Philippines">Philippines</option>
									<option value="Pitcairn">Pitcairn</option>
									<option value="Poland">Poland</option>
									<option value="Portugal">Portugal</option>
									<option value="Puerto Rico">Puerto Rico</option>
									<option value="Qatar">Qatar</option>
									<option value="Reunion">Reunion</option>
									<option value="Romania">Romania</option>
									<option value="Russian Federation">Russian Federation</option>
									<option value="Rwanda">Rwanda</option>
									<option value="Saint Barthelemy">Saint Barthelemy</option>
									<option value="Saint Helena">Saint Helena</option>
									<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
									<option value="Saint Lucia">Saint Lucia</option>
									<option value="Saint Martin">Saint Martin</option>
									<option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
									<option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
									<option value="Samoa">Samoa</option>
									<option value="San Marino">San Marino</option>
									<option value="Sao Tome and Principe">Sao Tome and Principe</option>
									<option value="Saudi Arabia">Saudi Arabia</option>
									<option value="Senegal">Senegal</option>
									<option value="Serbia">Serbia</option>
									<option value="Serbia and Montenegro">Serbia and Montenegro</option>
									<option value="Seychelles">Seychelles</option>
									<option value="Sierra Leone">Sierra Leone</option>
									<option value="Singapore">Singapore</option>
									<option value="Sint Maarten">Sint Maarten</option>
									<option value="Slovakia">Slovakia</option>
									<option value="Slovenia">Slovenia</option>
									<option value="Solomon Islands">Solomon Islands</option>
									<option value="Somalia">Somalia</option>
									<option value="South Africa">South Africa</option>
									<option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
									<option value="South Sudan">South Sudan</option>
									<option value="Spain">Spain</option>
									<option value="Sri Lanka">Sri Lanka</option>
									<option value="Sudan">Sudan</option>
									<option value="Suriname">Suriname</option>
									<option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
									<option value="Swaziland">Swaziland</option>
									<option value="Sweden">Sweden</option>
									<option value="Switzerland">Switzerland</option>
									<option value="Syrian Arab Republic">Syrian Arab Republic</option>
									<option value="Taiwan, Province of China">Taiwan, Province of China</option>
									<option value="Tajikistan">Tajikistan</option>
									<option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
									<option value="Thailand">Thailand</option>
									<option value="Timor-Leste">Timor-Leste</option>
									<option value="Togo">Togo</option>
									<option value="Tokelau">Tokelau</option>
									<option value="Tonga">Tonga</option>
									<option value="Trinidad and Tobago">Trinidad and Tobago</option>
									<option value="Tunisia">Tunisia</option>
									<option value="Turkey">Turkey</option>
									<option value="Turkmenistan">Turkmenistan</option>
									<option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
									<option value="Tuvalu">Tuvalu</option>
									<option value="Uganda">Uganda</option>
									<option value="Ukraine">Ukraine</option>
									<option value="United Arab Emirates">United Arab Emirates</option>
									<option value="United Kingdom">United Kingdom</option>
									<option value="United States">United States</option>
									<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
									<option value="Uruguay">Uruguay</option>
									<option value="Uzbekistan">Uzbekistan</option>
									<option value="Vanuatu">Vanuatu</option>
									<option value="Venezuela">Venezuela</option>
									<option value="Viet Nam">Viet Nam</option>
									<option value="Virgin Islands, British">Virgin Islands, British</option>
									<option value="Virgin Islands, U.s.">Virgin Islands, U.s.</option>
									<option value="Wallis and Futuna">Wallis and Futuna</option>
									<option value="Western Sahara">Western Sahara</option>
									<option value="Yemen">Yemen</option>
									<option value="Zambia">Zambia</option>
									<option value="Zimbabwe">Zimbabwe</option>
								</select>
							</div>
							<div class="col">
								<label for="Address_type1_1" class="formslabelfnt">Address Type : *</label>
								<select class="form-control formslabelfnt" id="ship_Address_type1_1" name="ship_Address_type1_1" required>
									<option selected disabled value="">Choose...</option>
									<option value="Business Address">Business Address</option>
									<option value="Home Address">Home Address</option>
								</select>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col">
								<label for="email1_1" class="formslabelfnt">Email Address : *</label>
								<input type="email" class="form-control formslabelfnt" id="ship_email" name="ship_email" required>
							</div>
							<div class="col">
								<label for="contactnumber1_1" class="formslabelfnt">Mobile Number : *</label>
								<input type="number" class="form-control formslabelfnt" id="ship_mobilenumber1_1" name="ship_mobilenumber1_1" required>
							</div>
							<div class="col">
								<label for="contactnumber1_1" class="formslabelfnt">Contact Number : </label>
								<input type="number" class="form-control formslabelfnt" id="contactnumber1_1" name="contactnumber1_1">
							</div>
						</div>
						<br><br>
						<div class="form-check">
							<input class="form-check-input" type="checkbox" id="myCheck" name="myCheck"  checked onclick="shipp_function()">
							<label class="form-check-label" for="flexCheckChecked" class="formslabelfnt">Billing address is the same as above</label>
						</div>
						<br/>
						
						<div id="billAddress" style="display: none;">{{-- BILLING FORM --}}
							<p style="color:#212529 !important; letter-spacing: 1px !important; font-size:16px !important;  text-align: justify !important; font-weight: 600 !important;">Billing Information</p>
							<div class="row">
								<div class="col">
									<label for="fname" class="formslabelfnt">First Name : *</label>
									<input type="text" class="form-control formslabelfnt" id="bill_fname" name="bill_fname">
								</div>
								<div class="col">
									<label for="lname" class="formslabelfnt">Last Name : *</label>
									<input type="text" class="form-control formslabelfnt" id="bill_lname" name="bill_lname">
								</div>
							</div>
							<br/>
							<div class="row">
								<div class="col">
									<label for="Address1_1" class="formslabelfnt">Address Line 1 : *</label>
									<input type="text" class="form-control formslabelfnt" id="Address1_1" name="Address1_1">
								</div>
								<div class="col">
									<label for="Address2_1" class="formslabelfnt">Address Line 2 : </label>
									<input type="text" class="form-control formslabelfnt" id="Address2_1" name="Address2_1">
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col">
									<label for="province1_1" class="formslabelfnt">Province : *</label>
									<input type="text" class="form-control formslabelfnt" id="province1_1" name="province1_1">
								</div>
								<div class="col">
									<label for="City_Municipality1_1" class="formslabelfnt">City / Municipality : *</label>
									<input type="text" class="form-control formslabelfnt" id="City_Municipality1_1" name="City_Municipality1_1">
								</div>
								<div class="col">
									<label for="Barangay1_1" class="formslabelfnt">Barangay : *</label>
									<input type="text" class="form-control formslabelfnt" id="Barangay1_1" name="Barangay1_1">
								</div>
							</div>
							<br>
							<div class="row">{{-- Country Select Row --}}
								<div class="col">
									<label for="postal1_1" class="formslabelfnt">Postal Code : *</label>
									<input type="text" class="form-control formslabelfnt" id="postal1_1" name="postal1_1">
								</div>
								<div class="col">
									<label for="country_region1_1" class="formslabelfnt">Country / Region : *</label>
									<select class="form-control formslabelfnt" id="country_region1_1" name="country_region1_1">
										<option selected disabled value="">Choose...</option>
										<option value="Afghanistan">Afghanistan</option>
										<option value="Aland Islands">Aland Islands</option>
										<option value="Albania">Albania</option>
										<option value="Algeria">Algeria</option>
										<option value="American Samoa">American Samoa</option>
										<option value="Andorra">Andorra</option>
										<option value="Angola">Angola</option>
										<option value="Anguilla">Anguilla</option>
										<option value="Antarctica">Antarctica</option>
										<option value="Antigua and Barbuda">Antigua and Barbuda</option>
										<option value="Argentina">Argentina</option>
										<option value="Armenia">Armenia</option>
										<option value="Aruba">Aruba</option>
										<option value="Australia">Australia</option>
										<option value="Austria">Austria</option>
										<option value="Azerbaijan">Azerbaijan</option>
										<option value="Bahamas">Bahamas</option>
										<option value="Bahrain">Bahrain</option>
										<option value="Bangladesh">Bangladesh</option>
										<option value="Barbados">Barbados</option>
										<option value="Belarus">Belarus</option>
										<option value="Belgium">Belgium</option>
										<option value="Belize">Belize</option>
										<option value="Benin">Benin</option>
										<option value="Bermuda">Bermuda</option>
										<option value="Bhutan">Bhutan</option>
										<option value="Bolivia">Bolivia</option>
										<option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
										<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
										<option value="Botswana">Botswana</option>
										<option value="Bouvet Island">Bouvet Island</option>
										<option value="Brazil">Brazil</option>
										<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
										<option value="Brunei Darussalam">Brunei Darussalam</option>
										<option value="Bulgaria">Bulgaria</option>
										<option value="Burkina Faso">Burkina Faso</option>
										<option value="Burundi">Burundi</option>
										<option value="Cambodia">Cambodia</option>
										<option value="Cameroon">Cameroon</option>
										<option value="Canada">Canada</option>
										<option value="Cape Verde">Cape Verde</option>
										<option value="Cayman Islands">Cayman Islands</option>
										<option value="Central African Republic">Central African Republic</option>
										<option value="Chad">Chad</option>
										<option value="Chile">Chile</option>
										<option value="China">China</option>
										<option value="Christmas Island">Christmas Island</option>
										<option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
										<option value="Colombia">Colombia</option>
										<option value="Comoros">Comoros</option>
										<option value="Congo">Congo</option>
										<option value="Democratic Republic of the Congo">Democratic Republic of the Congo</option>
										<option value="Cook Islands">Cook Islands</option>
										<option value="Costa Rica">Costa Rica</option>
										<option value="Cote D'Ivoire">Cote D'Ivoire</option>
										<option value="Croatia">Croatia</option>
										<option value="Cuba">Cuba</option>
										<option value="Curacao">Curacao</option>
										<option value="Cyprus">Cyprus</option>
										<option value="Czech Republic">Czech Republic</option>
										<option value="Denmark">Denmark</option>
										<option value="Djibouti">Djibouti</option>
										<option value="Dominica">Dominica</option>
										<option value="Dominican Republic">Dominican Republic</option>
										<option value="Ecuador">Ecuador</option>
										<option value="Egypt">Egypt</option>
										<option value="El Salvador">El Salvador</option>
										<option value="Equatorial Guinea">Equatorial Guinea</option>
										<option value="Eritrea">Eritrea</option>
										<option value="Estonia">Estonia</option>
										<option value="Ethiopia">Ethiopia</option>
										<option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
										<option value="Faroe Islands">Faroe Islands</option>
										<option value="Fiji">Fiji</option>
										<option value="Finland">Finland</option>
										<option value="France">France</option>
										<option value="French Guiana">French Guiana</option>
										<option value="French Polynesia">French Polynesia</option>
										<option value="French Southern Territories">French Southern Territories</option>
										<option value="Gabon">Gabon</option>
										<option value="Gambia">Gambia</option>
										<option value="Georgia">Georgia</option>
										<option value="Germany">Germany</option>
										<option value="Ghana">Ghana</option>
										<option value="Gibraltar">Gibraltar</option>
										<option value="Greece">Greece</option>
										<option value="Greenland">Greenland</option>
										<option value="Grenada">Grenada</option>
										<option value="Guadeloupe">Guadeloupe</option>
										<option value="Guam">Guam</option>
										<option value="Guatemala">Guatemala</option>
										<option value="Guernsey">Guernsey</option>
										<option value="Guinea">Guinea</option>
										<option value="Guinea-Bissau">Guinea-Bissau</option>
										<option value="Guyana">Guyana</option>
										<option value="Haiti">Haiti</option>
										<option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
										<option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
										<option value="Honduras">Honduras</option>
										<option value="Hong Kong">Hong Kong</option>
										<option value="Hungary">Hungary</option>
										<option value="Iceland">Iceland</option>
										<option value="India">India</option>
										<option value="Indonesia">Indonesia</option>
										<option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
										<option value="Iraq">Iraq</option>
										<option value="Ireland">Ireland</option>
										<option value="Isle of Man">Isle of Man</option>
										<option value="Israel">Israel</option>
										<option value="Italy">Italy</option>
										<option value="Jamaica">Jamaica</option>
										<option value="Japan">Japan</option>
										<option value="Jersey">Jersey</option>
										<option value="Jordan">Jordan</option>
										<option value="Kazakhstan">Kazakhstan</option>
										<option value="Kenya">Kenya</option>
										<option value="Kiribati">Kiribati</option>
										<option value="Korea, Democratic People's Republic of">Korea, Democratic People"s Republic of</option>
										<option value="Korea, Republic of">Korea, Republic of</option>
										<option value="Kosovo">Kosovo</option>
										<option value="Kuwait">Kuwait</option>
										<option value="Kyrgyzstan">Kyrgyzstan</option>
										<option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
										<option value="Latvia">Latvia</option>
										<option value="Lebanon">Lebanon</option>
										<option value="Lesotho">Lesotho</option>
										<option value="Liberia">Liberia</option>
										<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
										<option value="Liechtenstein">Liechtenstein</option>
										<option value="Lithuania">Lithuania</option>
										<option value="Luxembourg">Luxembourg</option>
										<option value="Macao">Macao</option>
										<option value="Macedonia, the Former Yugoslav Republic of">Macedonia, the Former Yugoslav Republic of</option>
										<option value="Madagascar">Madagascar</option>
										<option value="Malawi">Malawi</option>
										<option value="Malaysia">Malaysia</option>
										<option value="Maldives">Maldives</option>
										<option value="Mali">Mali</option>
										<option value="Malta">Malta</option>
										<option value="Marshall Islands">Marshall Islands</option>
										<option value="Martinique">Martinique</option>
										<option value="Mauritania">Mauritania</option>
										<option value="Mauritius">Mauritius</option>
										<option value="Mayotte">Mayotte</option>
										<option value="Mexico">Mexico</option>
										<option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
										<option value="Moldova, Republic of">Moldova, Republic of</option>
										<option value="Monaco">Monaco</option>
										<option value="Mongolia">Mongolia</option>
										<option value="Montenegro">Montenegro</option>
										<option value="Montserrat">Montserrat</option>
										<option value="Morocco">Morocco</option>
										<option value="Mozambique">Mozambique</option>
										<option value="Myanmar">Myanmar</option>
										<option value="Namibia">Namibia</option>
										<option value="Nauru">Nauru</option>
										<option value="Nepal">Nepal</option>
										<option value="Netherlands">Netherlands</option>
										<option value="Netherlands Antilles">Netherlands Antilles</option>
										<option value="New Caledonia">New Caledonia</option>
										<option value="New Zealand">New Zealand</option>
										<option value="Nicaragua">Nicaragua</option>
										<option value="Niger">Niger</option>
										<option value="Nigeria">Nigeria</option>
										<option value="Niue">Niue</option>
										<option value="Norfolk Island">Norfolk Island</option>
										<option value="Northern Mariana Islands">Northern Mariana Islands</option>
										<option value="Norway">Norway</option>
										<option value="OM">Oman</option>
										<option value="Oman">Pakistan</option>
										<option value="Palau">Palau</option>
										<option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
										<option value="Panama">Panama</option>
										<option value="Papua New Guinea">Papua New Guinea</option>
										<option value="Paraguay">Paraguay</option>
										<option value="Peru">Peru</option>
										<option value="Philippines">Philippines</option>
										<option value="Pitcairn">Pitcairn</option>
										<option value="Poland">Poland</option>
										<option value="Portugal">Portugal</option>
										<option value="Puerto Rico">Puerto Rico</option>
										<option value="Qatar">Qatar</option>
										<option value="Reunion">Reunion</option>
										<option value="Romania">Romania</option>
										<option value="Russian Federation">Russian Federation</option>
										<option value="Rwanda">Rwanda</option>
										<option value="Saint Barthelemy">Saint Barthelemy</option>
										<option value="Saint Helena">Saint Helena</option>
										<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
										<option value="Saint Lucia">Saint Lucia</option>
										<option value="Saint Martin">Saint Martin</option>
										<option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
										<option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
										<option value="Samoa">Samoa</option>
										<option value="San Marino">San Marino</option>
										<option value="Sao Tome and Principe">Sao Tome and Principe</option>
										<option value="Saudi Arabia">Saudi Arabia</option>
										<option value="Senegal">Senegal</option>
										<option value="Serbia">Serbia</option>
										<option value="Serbia and Montenegro">Serbia and Montenegro</option>
										<option value="Seychelles">Seychelles</option>
										<option value="Sierra Leone">Sierra Leone</option>
										<option value="Singapore">Singapore</option>
										<option value="Sint Maarten">Sint Maarten</option>
										<option value="Slovakia">Slovakia</option>
										<option value="Slovenia">Slovenia</option>
										<option value="Solomon Islands">Solomon Islands</option>
										<option value="Somalia">Somalia</option>
										<option value="South Africa">South Africa</option>
										<option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
										<option value="South Sudan">South Sudan</option>
										<option value="Spain">Spain</option>
										<option value="Sri Lanka">Sri Lanka</option>
										<option value="Sudan">Sudan</option>
										<option value="Suriname">Suriname</option>
										<option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
										<option value="Swaziland">Swaziland</option>
										<option value="Sweden">Sweden</option>
										<option value="Switzerland">Switzerland</option>
										<option value="Syrian Arab Republic">Syrian Arab Republic</option>
										<option value="Taiwan, Province of China">Taiwan, Province of China</option>
										<option value="Tajikistan">Tajikistan</option>
										<option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
										<option value="Thailand">Thailand</option>
										<option value="Timor-Leste">Timor-Leste</option>
										<option value="Togo">Togo</option>
										<option value="Tokelau">Tokelau</option>
										<option value="Tonga">Tonga</option>
										<option value="Trinidad and Tobago">Trinidad and Tobago</option>
										<option value="Tunisia">Tunisia</option>
										<option value="Turkey">Turkey</option>
										<option value="Turkmenistan">Turkmenistan</option>
										<option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
										<option value="Tuvalu">Tuvalu</option>
										<option value="Uganda">Uganda</option>
										<option value="Ukraine">Ukraine</option>
										<option value="United Arab Emirates">United Arab Emirates</option>
										<option value="United Kingdom">United Kingdom</option>
										<option value="United States">United States</option>
										<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
										<option value="Uruguay">Uruguay</option>
										<option value="Uzbekistan">Uzbekistan</option>
										<option value="Vanuatu">Vanuatu</option>
										<option value="Venezuela">Venezuela</option>
										<option value="Viet Nam">Viet Nam</option>
										<option value="Virgin Islands, British">Virgin Islands, British</option>
										<option value="Virgin Islands, U.s.">Virgin Islands, U.s.</option>
										<option value="Wallis and Futuna">Wallis and Futuna</option>
										<option value="Western Sahara">Western Sahara</option>
										<option value="Yemen">Yemen</option>
										<option value="Zambia">Zambia</option>
										<option value="Zimbabwe">Zimbabwe</option>
									</select>
								</div>
								<div class="col">
									<label for="Address_type1_1" class="formslabelfnt">Address Type : *</label>
									<select class="form-control formslabelfnt" id="Address_type1_1" name="Address_type1_1">
										<option selected disabled value="">Choose...</option>
										<option value="Business Address">Business Address</option>
										<option value="Home Address">Home Address</option>
									</select>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col">
									<label for="email1_1" class="formslabelfnt">Email Address : *</label>
									<input type="email" class="form-control formslabelfnt" id="email" name="email">
								</div>
								<div class="col">
									<label for="mobilenumber1_1" class="formslabelfnt">Mobile Number : *</label>
									<input type="number" class="form-control formslabelfnt" id="mobilenumber1_1" name="mobilenumber1_1">
								</div>
							</div>
						</div>

						<div class="container">
							<div class="row">
								<div class="col-lg-12">
									<br>
									<center>
										{{-- <div style="display: none !important;">
											<input type="text" name="item_code" value="{{ $cart_arr[0]['item_code'] }}"/>
											<input type="text" name="item_desc" value="{{ $cart_arr[0]['item_desc'] }}"/>
											<input type="text" name="price" value="{{ $cart_arr[0]['price'] }}"/>
											<input type="text" name="subtotal" value="{{ $cart_arr[0]['subtotal'] }}"/>
											<input type="text" name="qty" value="{{ $cart_arr[0]['quantity'] }}"/>
											<input type="text" name="shipping" value="{{ $cart_arr[0]['shipping'] }}"/>
											<input type="text" name="grand_total" value="{{ $cart_arr[0]['grand_total'] }}"/>
										</div> --}}
										
									</center>
								</div>
							</div>
						</div>

					</div>

					<div class="col-md-4">
						<div class="card" style="margin-left: 5%; background-color: #f4f4f4 !important; border-radius: 0rem !important;">
							<div class="card-body he1x" style="padding-bottom: 0px !important;">Cart Total<hr></div>
							<table class="table" id="cart-items">
								<thead>
								<tr>
									<th></th>
									<th>Product</th>
									<th>Qty</th>
									<th>Total</th>
								</tr>
								</thead>
								<tbody>
									@foreach ($cart_arr as $cart)
										<tr>
											<td class="col-md-2">
												<center>
													<img src="{{ asset('/storage/item/images/'.$cart['item_code'].'/gallery/preview/'.$cart['item_image']) }}" class="img-responsive" alt="" width="55" height="55">
												</center>
											</td>
											<td>{{ $cart['item_description'] }}</td>
											<td>{{ $cart['quantity'] }}</td>
											<td><span class="amount">{{ $cart['amount'] }}</span></td>
										</tr>
									@endforeach
								</tbody>
							</table>
							<div class="card-body he1x" style="padding-top: 0px !important; padding-bottom: 0px !important;">
								<div class="d-flex justify-content-between align-items-center">
									Subtotal <small class="text-muted stylecap he1x" id="cart-subtotal">P {{ number_format(collect($cart_arr)->sum('amount'), 2, '.', ',') }}</small>
								</div>
								<hr>
							</div>
							<div class="card-body he1x" id="ship_blk" style="padding-top: 0px !important; padding-bottom: 0px !important;">Shipping
								<div class="form-check">
									<label class="form-check-label" for="shipradio">&nbsp;</label>
								</div>
								<div class="d-flex justify-content-between align-items-center">
									<div class="form-check">
										<input class="form-check-input" type="radio" name="shipping_fee" id="Free Shipping" value="0" data-name="Free Delivery" required checked>
										<label class="form-check-label" for="Free Shipping">Free Shipping</label>
									</div>
									<small class="text-muted stylecap he1x">P 0.00</small>
								</div>
								<div class="d-flex justify-content-between align-items-center">
									<div class="form-check">
										<input class="form-check-input" type="radio" name="shipping_fee" id="Standard" value="500" data-name="Standard Delivery" required>
										<label class="form-check-label" for="Standard">Standard</label>
									</div>
									<small class="text-muted stylecap he1x">P 500.00</small>
								</div>
								<hr>
							</div>
							<div class="card-body he1x">
								<div class="d-flex justify-content-between align-items-center" style="color:#FF9D00 !important;">Total <small class="text-muted stylecap he1x" style="color:#FF9D00 !important;" id="grand-total">0.00</small>
								</div>
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-md-4">
							<a href="/cart" class="btn btn-lg btn-outline-primary col-md-10 mx-auto" role="button" style="background-color: #777575 !important; border-color: #777575 !important;">BACK</a>
						</div>
						
						<div class="col-md-4">
							&nbsp;
						</div>
						
						<div class="col-md-4">
							<input type="submit" class="btn btn-lg btn-outline-primary col-md-10 mx-auto" style="float: right;" value="PROCEED">
						</div>
					</div>
				</div>
			</form>

		</div>
	</main>
	
	<script>
		$(document).ready(function() {
			updateTotal();
			$('input[type="checkbox"]').click(function() {
				if($(this).prop("checked") == false) {
					$('#billAddress').slideDown();
					$("#Address1_1").prop('required',true);
					$("#email").prop('required',true);
					$("#province1_1").prop('required',true);
					$("#City_Municipality1_1").prop('required',true);
					$("#Barangay1_1").prop('required',true);
					$("#postal1_1").prop('required',true);
					$("#country_region1_1").prop('required',true);
					$("#Address_type1_1").prop('required',true);
					$("#email").prop('required',true);
					$("#mobilenumber1_1").prop('required',true);
					$("#bill_fname").prop('required',true);
					$("#bill_lname").prop('required',true);
				}else{
					$('#billAddress').slideUp();
					$("#Address1_1").prop('required',false);
					$("#email").prop('required',false);
					$("#province1_1").prop('required',false);
					$("#City_Municipality1_1").prop('required',false);
					$("#Barangay1_1").prop('required',false);
					$("#postal1_1").prop('required',false);
					$("#country_region1_1").prop('required',false);
					$("#Address_type1_1").prop('required',false);
					$("#email").prop('required',false);
					$("#mobilenumber1_1").prop('required',false);
					$("#bill_fname").prop('required',false);
					$("#bill_lname").prop('required',false);
				}
            });

			function updateTotal() {
				var subtotal = 0;
				$('#cart-items tbody tr').each(function(){
					var amount = $(this).find('.amount').eq(0).text();
					subtotal += parseFloat(amount);
				});
					
				var shipping_fee = $("input[name='shipping_fee']:checked").val();
				var total = parseFloat(shipping_fee) + subtotal;

				total = (isNaN(total)) ? 0 : total;

				// $('#cart-subtotal').text('P ' + subtotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,"));
				// $('#cart-subtotal').text('P ' + total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,"));
				$('#grand-total').text('P ' + total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,"));
			}

			function updateCart(type, id, quantity) {
				var data = {
					'id': id,
					'quantity': quantity,
					'_token': "{{ csrf_token() }}",
					'type': type
				}

				$.ajax({
					type:'PATCH',
					url:'/updatecart',
					data: data,
				});
			}

			// $('#checkout-btn').click(function(e){
			// 	e.preventDefault();
			// 	var data = {
			// 		'shipping_fee': $("input[name='shipping_fee']:checked").val(),
			// 		'shipping_name': $("input[name='shipping_fee']:checked").data('name'),
			// 		'_token': "{{ csrf_token() }}",
			// 	}

			// 	$.ajax({
			// 		type:'POST',
			// 		url:'/addshipping',
			// 		data: data,
			// 		success: function (response) {
			// 			window.location.href = "/checkout/billing";
			// 			// window.location.href = "/checkout/review_order";
			// 		},
			// 		error: function () {
			// 			alert('An error occured.');
			// 		}
			// 	});
			// });

			$(document).on('change', 'input[name="shipping_fee"]', function(){
				updateTotal();
			});
        });

	</script>
@endsection