@extends('frontend.layout', [
	'namePage' => 'My Profile - Address Form',
	'activePage' => 'myprofile_address_form'
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
		.btmp {
			margin-bottom: 15px !important;
		}
		.caption_1 {
			font-weight: 200 !important;
			font-size: 14px !important;
		}
		.caption_2 {
			font-weight: 500 !important;
			font-size: 14px !important;
		}
		.order-font {
			font-weight: 200 !important;
			font-size: 14px !important;
		}
		.order-font-sub {
			font-weight: 200 !important;
			font-size: 10px !important;
		}
		.order-font-sub-b {
			font-weight: 300 !important;
			font-size: 14px !important;
		}
		.order-font-sub-b {
			font-weight: 300 !important;
			font-size: 14px !important;
		}
		.tbls{
			vertical-align: center !important;
		}
		.myprofile-font-form {
			font-weight: 500 !important;
			font-size: 14px !important;
		}
	</style>

	<main style="background-color:#0062A5;">
		<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-inner">
				<div class="carousel-item active" style="height: 13rem !important;">
					<img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important;">
					<div class="container">
						<div class="carousel-caption text-start" style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
							<center><h3 class="carousel-header-font">MY PROFILE</h3></center>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	
	<main style="background-color:#ffffff;" class="products-head">
		<div class="container-fluid">
			<div class="row" style="padding-left: 15%; padding-right: 0%; padding-top: 25px;">
				<div class="col-lg-2">
					<p class="caption_2">
						<a href="/myprofile/account_details" style="text-decoration: none; color: #000000;">Account Details</a>
					</p>
					<hr>
					<p class="caption_2">
						<a href="/myprofile/change_password" style="text-decoration: none; color: #000000;">Change Password</a>
					</p>
					<hr>
					<p class="caption_2" style="color:#186EA9 !important; font-weight:400 !important;">
						<a href="/myprofile/address" style="text-decoration: none;"><i class="fas fa-angle-double-right"></i> <span style="margin-left: 8px;">Address</span></a>
					</p>
					<hr>
					<p class="caption_2">
						<a href="/logout" style="text-decoration: none; color: #000000;">Sign Out</a>
					</p>
					<hr>
				</div>
				<div class="col-lg-8">
					@if(count($errors->all()) > 0)
               <div class="row">
						<div class="col">
							<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
								@foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach 
						  	</div>
						</div>
					</div>
               @endif
					<form action="/myprofile/address/{{ $type }}/save" method="POST">
						@csrf
						<strong><h4>New {{ ucfirst($type) }} Address :</h4></strong>
						<br>
						<div class="row">
							<div class="col">
								<label for="first_name" class="myprofile-font-form">First Name : *</label>
								<input type="text" class="form-control caption_1" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
							</div>
							<div class="col">
								<label for="last_name" class="myprofile-font-form">Last Name : *</label>
								<input type="text" class="form-control caption_1" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col">
								<label for="contact_no" class="myprofile-font-form">Contact Number : *</label>
								<input type="text" class="form-control caption_1" id="contact_no" name="contact_no" value="{{ old('contact_no') }}" required>
							</div>

							<div class="col">
								<label for="mobile_no" class="myprofile-font-form">Mobile Number : *</label>
								<input type="text" class="form-control caption_1" id="mobile_no" name="mobile_no" value="" required/>
							</div>

							<div class="col">
								<label for="email_address" class="myprofile-font-form">Email : *</label>
								<input type="email" class="form-control caption_1" id="email_address" name="email_address" value="{{ old('email_address') }}" required>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col">
								<label for="address_line1" class="myprofile-font-form">Address Line 1 : *</label>
								<input type="text" class="form-control caption_1" id="address_line1" name="address_line1" value="{{ old('address_line1') }}" required>
							</div>
							<div class="col">
								<label for="address_line2" class="myprofile-font-form">Address Line 2 : </label>
								<input type="text" class="form-control caption_1" id="address_line2" name="address_line2" value="{{ old('address_line2') }}">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col">
								<label for="province" class="myprofile-font-form">Province : *</label>
								<input type="text" class="form-control caption_1" id="province" name="province" value="{{ old('province') }}" required>
							</div>
							<div class="col">
								<label for="city" class="myprofile-font-form">City / Municipality : *</label>
								<input type="text" class="form-control caption_1" id="city" name="city" value="{{ old('city') }}" required>
							</div>
							<div class="col">
								<label for="barangay" class="myprofile-font-form">Barangay : *</label>
								<input type="text" class="form-control caption_1" id="barangay" name="barangay" value="{{ old('barangay') }}" required>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col">
								<label for="postal_code" class="myprofile-font-form">Postal Code : *</label>
								<input type="text" class="form-control caption_1" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required>
							</div>
							<div class="col">
								<label for="country" class="myprofile-font-form">Country / Region : *</label>
								<select class="form-control caption_1" id="country" name="country" required>
									<option value="AF">Afghanistan</option>
									<option value="AX">Aland Islands</option>
									<option value="AL">Albania</option>
									<option value="DZ">Algeria</option>
									<option value="AS">American Samoa</option>
									<option value="AD">Andorra</option>
									<option value="AO">Angola</option>
									<option value="AI">Anguilla</option>
									<option value="AQ">Antarctica</option>
									<option value="AG">Antigua and Barbuda</option>
									<option value="AR">Argentina</option>
									<option value="AM">Armenia</option>
									<option value="AW">Aruba</option>
									<option value="AU">Australia</option>
									<option value="AT">Austria</option>
									<option value="AZ">Azerbaijan</option>
									<option value="BS">Bahamas</option>
									<option value="BH">Bahrain</option>
									<option value="BD">Bangladesh</option>
									<option value="BB">Barbados</option>
									<option value="BY">Belarus</option>
									<option value="BE">Belgium</option>
									<option value="BZ">Belize</option>
									<option value="BJ">Benin</option>
									<option value="BM">Bermuda</option>
									<option value="BT">Bhutan</option>
									<option value="BO">Bolivia</option>
									<option value="BQ">Bonaire, Sint Eustatius and Saba</option>
									<option value="BA">Bosnia and Herzegovina</option>
									<option value="BW">Botswana</option>
									<option value="BV">Bouvet Island</option>
									<option value="BR">Brazil</option>
									<option value="IO">British Indian Ocean Territory</option>
									<option value="BN">Brunei Darussalam</option>
									<option value="BG">Bulgaria</option>
									<option value="BF">Burkina Faso</option>
									<option value="BI">Burundi</option>
									<option value="KH">Cambodia</option>
									<option value="CM">Cameroon</option>
									<option value="CA">Canada</option>
									<option value="CV">Cape Verde</option>
									<option value="KY">Cayman Islands</option>
									<option value="CF">Central African Republic</option>
									<option value="TD">Chad</option>
									<option value="CL">Chile</option>
									<option value="CN">China</option>
									<option value="CX">Christmas Island</option>
									<option value="CC">Cocos (Keeling) Islands</option>
									<option value="CO">Colombia</option>
									<option value="KM">Comoros</option>
									<option value="CG">Congo</option>
									<option value="CD">Congo, the Democratic Republic of the</option>
									<option value="CK">Cook Islands</option>
									<option value="CR">Costa Rica</option>
									<option value="CI">Cote D'Ivoire</option>
									<option value="HR">Croatia</option>
									<option value="CU">Cuba</option>
									<option value="CW">Curacao</option>
									<option value="CY">Cyprus</option>
									<option value="CZ">Czech Republic</option>
									<option value="DK">Denmark</option>
									<option value="DJ">Djibouti</option>
									<option value="DM">Dominica</option>
									<option value="DO">Dominican Republic</option>
									<option value="EC">Ecuador</option>
									<option value="EG">Egypt</option>
									<option value="SV">El Salvador</option>
									<option value="GQ">Equatorial Guinea</option>
									<option value="ER">Eritrea</option>
									<option value="EE">Estonia</option>
									<option value="ET">Ethiopia</option>
									<option value="FK">Falkland Islands (Malvinas)</option>
									<option value="FO">Faroe Islands</option>
									<option value="FJ">Fiji</option>
									<option value="FI">Finland</option>
									<option value="FR">France</option>
									<option value="GF">French Guiana</option>
									<option value="PF">French Polynesia</option>
									<option value="TF">French Southern Territories</option>
									<option value="GA">Gabon</option>
									<option value="GM">Gambia</option>
									<option value="GE">Georgia</option>
									<option value="DE">Germany</option>
									<option value="GH">Ghana</option>
									<option value="GI">Gibraltar</option>
									<option value="GR">Greece</option>
									<option value="GL">Greenland</option>
									<option value="GD">Grenada</option>
									<option value="GP">Guadeloupe</option>
									<option value="GU">Guam</option>
									<option value="GT">Guatemala</option>
									<option value="GG">Guernsey</option>
									<option value="GN">Guinea</option>
									<option value="GW">Guinea-Bissau</option>
									<option value="GY">Guyana</option>
									<option value="HT">Haiti</option>
									<option value="HM">Heard Island and Mcdonald Islands</option>
									<option value="VA">Holy See (Vatican City State)</option>
									<option value="HN">Honduras</option>
									<option value="HK">Hong Kong</option>
									<option value="HU">Hungary</option>
									<option value="IS">Iceland</option>
									<option value="IN">India</option>
									<option value="ID">Indonesia</option>
									<option value="IR">Iran, Islamic Republic of</option>
									<option value="IQ">Iraq</option>
									<option value="IE">Ireland</option>
									<option value="IM">Isle of Man</option>
									<option value="IL">Israel</option>
									<option value="IT">Italy</option>
									<option value="JM">Jamaica</option>
									<option value="JP">Japan</option>
									<option value="JE">Jersey</option>
									<option value="JO">Jordan</option>
									<option value="KZ">Kazakhstan</option>
									<option value="KE">Kenya</option>
									<option value="KI">Kiribati</option>
									<option value="KP">Korea, Democratic People"s Republic of</option>
									<option value="KR">Korea, Republic of</option>
									<option value="XK">Kosovo</option>
									<option value="KW">Kuwait</option>
									<option value="KG">Kyrgyzstan</option>
									<option value="LA">Lao People's Democratic Republic</option>
									<option value="LV">Latvia</option>
									<option value="LB">Lebanon</option>
									<option value="LS">Lesotho</option>
									<option value="LR">Liberia</option>
									<option value="LY">Libyan Arab Jamahiriya</option>
									<option value="LI">Liechtenstein</option>
									<option value="LT">Lithuania</option>
									<option value="LU">Luxembourg</option>
									<option value="MO">Macao</option>
									<option value="MK">Macedonia, the Former Yugoslav Republic of</option>
									<option value="MG">Madagascar</option>
									<option value="MW">Malawi</option>
									<option value="MY">Malaysia</option>
									<option value="MV">Maldives</option>
									<option value="ML">Mali</option>
									<option value="MT">Malta</option>
									<option value="MH">Marshall Islands</option>
									<option value="MQ">Martinique</option>
									<option value="MR">Mauritania</option>
									<option value="MU">Mauritius</option>
									<option value="YT">Mayotte</option>
									<option value="MX">Mexico</option>
									<option value="FM">Micronesia, Federated States of</option>
									<option value="MD">Moldova, Republic of</option>
									<option value="MC">Monaco</option>
									<option value="MN">Mongolia</option>
									<option value="ME">Montenegro</option>
									<option value="MS">Montserrat</option>
									<option value="MA">Morocco</option>
									<option value="MZ">Mozambique</option>
									<option value="MM">Myanmar</option>
									<option value="NA">Namibia</option>
									<option value="NR">Nauru</option>
									<option value="NP">Nepal</option>
									<option value="NL">Netherlands</option>
									<option value="AN">Netherlands Antilles</option>
									<option value="NC">New Caledonia</option>
									<option value="NZ">New Zealand</option>
									<option value="NI">Nicaragua</option>
									<option value="NE">Niger</option>
									<option value="NG">Nigeria</option>
									<option value="NU">Niue</option>
									<option value="NF">Norfolk Island</option>
									<option value="MP">Northern Mariana Islands</option>
									<option value="NO">Norway</option>
									<option value="OM">Oman</option>
									<option value="PK">Pakistan</option>
									<option value="PW">Palau</option>
									<option value="PS">Palestinian Territory, Occupied</option>
									<option value="PA">Panama</option>
									<option value="PG">Papua New Guinea</option>
									<option value="PY">Paraguay</option>
									<option value="PE">Peru</option>
									<option value="PH">Philippines</option>
									<option value="PN">Pitcairn</option>
									<option value="PL">Poland</option>
									<option value="PT">Portugal</option>
									<option value="PR">Puerto Rico</option>
									<option value="QA">Qatar</option>
									<option value="RE">Reunion</option>
									<option value="RO">Romania</option>
									<option value="RU">Russian Federation</option>
									<option value="RW">Rwanda</option>
									<option value="BL">Saint Barthelemy</option>
									<option value="SH">Saint Helena</option>
									<option value="KN">Saint Kitts and Nevis</option>
									<option value="LC">Saint Lucia</option>
									<option value="MF">Saint Martin</option>
									<option value="PM">Saint Pierre and Miquelon</option>
									<option value="VC">Saint Vincent and the Grenadines</option>
									<option value="WS">Samoa</option>
									<option value="SM">San Marino</option>
									<option value="ST">Sao Tome and Principe</option>
									<option value="SA">Saudi Arabia</option>
									<option value="SN">Senegal</option>
									<option value="RS">Serbia</option>
									<option value="CS">Serbia and Montenegro</option>
									<option value="SC">Seychelles</option>
									<option value="SL">Sierra Leone</option>
									<option value="SG">Singapore</option>
									<option value="SX">Sint Maarten</option>
									<option value="SK">Slovakia</option>
									<option value="SI">Slovenia</option>
									<option value="SB">Solomon Islands</option>
									<option value="SO">Somalia</option>
									<option value="ZA">South Africa</option>
									<option value="GS">South Georgia and the South Sandwich Islands</option>
									<option value="SS">South Sudan</option>
									<option value="ES">Spain</option>
									<option value="LK">Sri Lanka</option>
									<option value="SD">Sudan</option>
									<option value="SR">Suriname</option>
									<option value="SJ">Svalbard and Jan Mayen</option>
									<option value="SZ">Swaziland</option>
									<option value="SE">Sweden</option>
									<option value="CH">Switzerland</option>
									<option value="SY">Syrian Arab Republic</option>
									<option value="TW">Taiwan, Province of China</option>
									<option value="TJ">Tajikistan</option>
									<option value="TZ">Tanzania, United Republic of</option>
									<option value="TH">Thailand</option>
									<option value="TL">Timor-Leste</option>
									<option value="TG">Togo</option>
									<option value="TK">Tokelau</option>
									<option value="TO">Tonga</option>
									<option value="TT">Trinidad and Tobago</option>
									<option value="TN">Tunisia</option>
									<option value="TR">Turkey</option>
									<option value="TM">Turkmenistan</option>
									<option value="TC">Turks and Caicos Islands</option>
									<option value="TV">Tuvalu</option>
									<option value="UG">Uganda</option>
									<option value="UA">Ukraine</option>
									<option value="AE">United Arab Emirates</option>
									<option value="GB">United Kingdom</option>
									<option value="US">United States</option>
									<option value="UM">United States Minor Outlying Islands</option>
									<option value="UY">Uruguay</option>
									<option value="UZ">Uzbekistan</option>
									<option value="VU">Vanuatu</option>
									<option value="VE">Venezuela</option>
									<option value="VN">Viet Nam</option>
									<option value="VG">Virgin Islands, British</option>
									<option value="VI">Virgin Islands, U.s.</option>
									<option value="WF">Wallis and Futuna</option>
									<option value="EH">Western Sahara</option>
									<option value="YE">Yemen</option>
									<option value="ZM">Zambia</option>
									<option value="ZW">Zimbabwe</option>
								</select>
							</div>
							<div class="col">
								<label for="address_type" class="myprofile-font-form">Address Type : *</label>
								<select class="form-control caption_1" id="address_type" name="address_type" required>
									<option value="Business" {{ (old('address_type') == 'Business') ? 'selected' : '' }}>Business</option>
									<option value="Home" {{ (old('address_type') == 'Home') ? 'selected' : '' }}>Home</option>
								</select>
							</div>
						</div>
						<button type="submit" class="btn btn-primary mt-3 caption_1">SAVE ADDRESS</button>
						<br><br>
					</form>
				</div>
			</div>
		</div>
	</main>
@endsection

@section('script')

@endsection
