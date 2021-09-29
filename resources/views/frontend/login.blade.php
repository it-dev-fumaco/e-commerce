@extends('frontend.layout', [
  'namePage' => 'Login',
  'activePage' => 'login'
])

@section('content')
<main style="background-color:#0062A5;">
    <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" style="height: 13rem !important;">
            <img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important; ">
                <div class="container">
                    <div class="carousel-caption text-start" style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
                        <center><h3 class="carousel-header-font">LOGIN | REGISTER </h3></center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<main style="background-color:#ffffff;" class="products-head">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
              <br>
                <div id="logreg-forms" style="box-shadow: unset !important;">
                    @if(session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    @if(session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session()->get('error') }}
                        </div>
                    @endif

                    <form class="form-signin" action="/login" method="POST" style="max-width: 1000px !important; border-color: #efefef; border-style: solid; border-width: 1px; border-top: 8px solid #186eaa; ">
                        @csrf
                        <ul class="nav nav-tabs">
                            <li class="nav-item active" style="width: 50%;">
                            <a class="nav-link" href="#"><center><span class="login_2">Sign in</span></center></a>
                            </li>

                            <li class="nav-item" style="width: 50%;">
                            <a class="nav-link" href="#" type="button" id="btn-signup"><center><span class="login_2">Register</span></center></a>
                            </li>
                        </ul>
                        <center>
                        <div class="col-lg-4" style="text-align: left;">
                            <br>
                            <label for="InputUsername" class="login_1" style="padding-bottom:10px;">Username or email address *<br></label>
                            <label for="InputUsername" class="login_1">&nbsp;</label>
                            <br>

                            <input type="email" id="username" name="username" class="form-control" required="" autofocus="" value="{{ old('username') }}">
                            <br>
                            <label for="InputPassword" class="login_1" style="padding-bottom:10px;">Password *</label>
                            <label for="InputPassword" class="login_1">&nbsp;</label>
                            <br>

                            <input type="password" id="password" name="password" class="form-control" required="">

                            <br>
                            <input type="submit" class="btn btn-primary" value="&nbsp;&nbsp;LOGIN&nbsp;&nbsp;">

                            <a href="forgot" id="forgot_pswdx" class="forgot-1">Forgot password?</a>
                            <br>
                            <label for="InputPassword" class="status-1"></label>
                        </div>
                        </center>
                    </form>

                    <form action="/reset/password/" class="form-reset" style="border-color: #efefef; border-style: solid; border-width: 1px; border-top: 8px solid #186eaa; ">
                        <input type="email" id="resetEmail" class="form-control" placeholder="Email address" required="" autofocus="">
                        <button class="btn btn-primary btn-block" type="submit">Reset Password</button>
                        <a href="#" id="cancel_reset"><i class="fas fa-angle-left"></i> Back</a>
                    </form>

                    <form action="user_register" class="form-signup" method="post" style="max-width: 1000px !important; border-color: #efefef; border-style: solid; border-width: 1px; border-top: 8px solid #186eaa; ">
                        @csrf
                        <ul class="nav nav-tabs">
                            <li class="nav-item" style="width: 50%;">
                            <a class="nav-link" href="#" id="cancel_signup"><center><span class="login_2">Sign in</span></center></a>
                            </li>

                            <li class="nav-item" style="width: 50%;">
                            <a class="nav-link" href="#" type="button" id="btn-signup" style="border-bottom: 3px solid #dc6f12;"><center><span class="login_2">Register</span></center></a>
                            </li>
                        </ul>

                        <br>

                        <div class="row">
                            <div class="col-lg-6">
                                <label for="mobile_1" class="myprofile-font-form login_1">First Name : *</label>
                                <input type="text" class="form-control caption_1" id="fname" name="first_name" required>
                            </div>
                            
                            <div class="col-lg-6">
                                <label for="mobile_1" class="myprofile-font-form login_1">Last Name : *</label>
                                <input type="text" class="form-control caption_1" id="lname" name="last_name" required>
                            </div> 
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <label class="login_1">Username or email address *</label>
                                <input type="email" name="username" id="username" class="form-control caption_1" value="" required>
                                <span class="help-block"></span>
                            </div>

                            <div class="col-lg-6">
                                <label for="mobile_1" class="myprofile-font-form login_1">Mobile Number : *</label>
                                <input type="number" class="form-control caption_1" id="mobile_1" name="mobile" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="Address1_1" class="myprofile-font-form login_1">Address Line 1 : *</label>
                            <input type="text" class="form-control caption_1" id="Address1_1" name="address_line1" required>
                        </div>

                        <div class="form-group">
                            <label for="Address2_1" class="myprofile-font-form login_1">Address Line 2 : </label>
                            <input type="text" class="form-control caption_1" id="Address2_1" name="address_line2">
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <label for="province1_1" class="myprofile-font-form login_1">Province : *</label>
                                <input type="text" class="form-control caption_1" id="province1_1" name="province" required>
                            </div>
                            <div class="col-lg-4">
                                <label for="City_Municipality1_1" class="myprofile-font-form login_1">City / Municipality : *</label>
                                <input type="text" class="form-control caption_1" id="City_Municipality1_1" name="City_Municipality" required>
                            </div>
                            <div class="col-lg-4">
                                <label for="Barangay1_1" class="myprofile-font-form login_1">Barangay : *</label>
                                <input type="text" class="form-control caption_1" id="Barangay1_1" name="Barangay" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                              <label for="postal1_1" class="myprofile-font-form login_1">Postal Code : *</label>
                              <input type="text" class="form-control caption_1" id="postal1_1" name="postal" required>
                            </div>
                            <div class="col-lg-8">
                                <label for="country_region1_1" class="myprofile-font-form login_1">Country / Region : *</label>
                                <select class="form-control caption_1" id="country_region1_1" name="country_region" required>
                                    <option>-- Select country --</option>
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
                                    <option value="Philippines">Philippines</option>
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
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <label class="login_1">Password *</label>
                                <input type="password" name="password" id="password" class="form-control caption_1" value="" required>
                                <span class="help-block"></span>
                            </div>

                            <div class="col-lg-6">
                                <label class="login_1">Confirm Password *</label>
                                <input type="password" name="confirm_password" id="confirm_password"  class="form-control caption_1" value="" required>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <p class="reg_link" style=""><input type="checkbox" checked> &nbsp;Yes, I want to receive email updates and notifications.</p>
                            <p class="reg_link" style="">By signing up you agree to our Privacy Policy and Terms.</p>
                            <input type="submit" class="btn btn-primary" value="REGISTER"><br/>
                        </div>
                    </form>
                    <br>
                </div>
            </div>
        </div>
    </div>
</main>\

<main style="background-color:#ffffff;">
    <br>
    <br>
    <br>
</main>
 
<main style="background-color:#ffffff;">
    <br>
    <br>
    <br>
</main>

<main style="background-color:#ffffff;">
    <br>
    <br>
    <br>
</main>

<main style="background-color:#ffffff;">
    <br>
    <br>
    <br>
</main>
<style>
    .reg_link{
        font-size: 9pt;
        white-space: nowrap;
    }

    /* sign in FORM */
    #logreg-forms{
  
        margin:0vh auto;
        background-color:#ffffff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
      transition: all 0.3s cubic-bezier(.25,.8,.25,1);
    }
    #logreg-forms form {
        width: 100%;
        max-width: 410px;
        padding: 15px;
        margin: auto;
    }
    #logreg-forms .form-control {
        position: relative;
        box-sizing: border-box;
        height: auto;
        padding: 10px;
        font-size: 16px;
    }
    #logreg-forms .form-control:focus { z-index: 2; }
    #logreg-forms .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
    #logreg-forms .form-signin input[type="password"] {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
  
    #logreg-forms .social-login{
        width:390px;
        margin:0 auto;
        margin-bottom: 14px;
    }
    #logreg-forms .social-btn{
        font-weight: 100;
        color:white;
        width:190px;
        font-size: 0.9rem;
    }
  
    #logreg-forms a{
        display: block;
        padding-top:10px;
        color:lightseagreen;
        /* text-decoration: none !important;
        text-transform: none !important;
        color: #000; */
    }
  
    #logreg-form .lines{
        width:200px;
        border:1px solid red;
    }
  
  
    #logreg-forms button[type="submit"]{ margin-top:10px; }
  
    #logreg-forms .facebook-btn{  background-color:#3C589C; }
  
    #logreg-forms .google-btn{ background-color: #DF4B3B; }
  
    #logreg-forms .form-reset, #logreg-forms .form-signup{ display: none; }
  
    #logreg-forms .form-signup .social-btn{ width:210px; }
  
    #logreg-forms .form-signup input { margin-bottom: 2px;}
  
    .form-signup .social-login{
        width:210px !important;
        margin: 0 auto;
    }
  
    /* Mobile */
  
    @media screen and (max-width:500px){
        #logreg-forms{
  
        }
  
        #logreg-forms  .social-login{
            width:200px;
            margin:0 auto;
            margin-bottom: 10px;
        }
        #logreg-forms  .social-btn{
            font-size: 1.3rem;
            font-weight: 100;
            color:white;
            width:200px;
            height: 56px;
  
        }
        #logreg-forms .social-btn:nth-child(1){
            margin-bottom: 5px;
        }
        #logreg-forms .social-btn span{
            display: none;
        }
        #logreg-forms  .facebook-btn:after{
            content:'Facebook';
        }
  
        #logreg-forms  .google-btn:after{
            content:'Google+';
        }
  
    }
  
</style>
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
        font-weight: 400 !important;
        font-size: 14px !important;
      }
  
      .caption_2 {
        font-weight: 200 !important;
        font-size: 10px !important;
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
  
            .tbls{
  
              vertical-align: center !important;
  
            }
  
  
            .login_1 {
              font-weight: 400 !important;
              font-size: 12px !important;
            }
  
  
            .login_2 {
              font-weight: 400 !important;
              font-size: 14px !important;
              color: #655f5f !important;
            }
  
  
            .active {
              border-bottom: 3px solid #dc6f12;
            }
  
  
            .forgot-1 {
              font-weight: 200 !important;
              font-size: 13px !important;
              color: #655f5f !important;
            }
  
  
            .status-1{
              font-weight: 200 !important;
              font-size: 16px !important;
              color: red !important;
            }
  
  
      </style>
<script>

    function toggleResetPswd(e){
        e.preventDefault();
        $('#logreg-forms .form-signin').toggle() // display:block or none
        $('#logreg-forms .form-reset').toggle() // display:block or none
    }
    
    function toggleSignUp(e){
        e.preventDefault();
        $('#logreg-forms .form-signin').toggle(); // display:block or none
        $('#logreg-forms .form-signup').toggle(); // display:block or none
    }
    
    $(()=>{
        // Login Register Form
        $('#logreg-forms #forgot_pswd').click(toggleResetPswd);
        $('#logreg-forms #cancel_reset').click(toggleResetPswd);
        $('#logreg-forms #btn-signup').click(toggleSignUp);
        $('#logreg-forms #cancel_signup').click(toggleSignUp);
    })
    
    
</script>
@endsection