<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
      <style type="text/css">
			body {margin: 0; padding: 0; min-width: 100%!important;font-family: 'poppins',sans-serif; }
			img {height: auto;}
			.content {width: 100%; max-width: 600px;}
			.header {padding: 40px 30px 20px 30px;}
			.innerpadding {padding: 30px 30px 30px 30px;}
			.borderbottom {border-bottom: 1px solid #f2eeed;}
			.subhead {font-size: 15px; color: #ffffff; font-family: 'poppins', sans-serif; letter-spacing: 10px;}
			.h1, .h2, .bodycopy {color: #153643; font-family: 'poppins',sans-serif;}
			.h1 {font-size: 33px; line-height: 38px; font-weight: bold;}
			.h2 {padding: 0 0 15px 0; font-size: 16px; line-height: 28px; font-weight: bold;}
			.bodycopy {font-size: 16px; line-height: 22px;}
			.button {text-align: center; font-size: 18px; font-family: 'poppins',sans-serif; font-weight: bold; padding: 0 30px 0 30px;}
			.button a {color: #ffffff; text-decoration: none;}
			.footer {padding: 20px 30px 15px 30px;}
			.footercopy {font-family: 'poppins',sans-serif; font-size: 14px; color: #ffffff;}
			.footercopy a {color: #ffffff; text-decoration: underline;}

			@media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
				body[yahoo] .hide {display: none!important;}
				body[yahoo] .buttonwrapper {background-color: transparent!important;}
				body[yahoo] .button {padding: 0px!important;}
				body[yahoo] .button a {background-color: #e05443; padding: 15px 15px 13px!important;}
				body[yahoo] .unsubscribe {display: block; margin-top: 20px; padding: 10px 50px; background: #2f3942; border-radius: 5px; text-decoration: none!important; font-weight: bold;}
			}
      </style>
   </head>

   <body yahoo style="background-color: #f2f3f4; padding: auto;">
		<table border="0" width="50%" bgcolor="#f2f3f4" cellpadding="0" cellspacing="0" style="margin: 1% auto 0 auto;">
			<tr>
				<td bgcolor="#126cb6" class="header">
					<table width="100%" align="left" border="0" cellpadding="0" cellspacing="0">
					  <tr>
						 <td height="70" style="padding: 0 10px 10px 0;">
							<center><img class="fix" src="{{ asset('/assets/logo_fumaco.png') }}" width="60%" border="0" alt="" /></center>
						 </td>
					  </tr>
					</table>
				 </td>
			</tr>
		</table>
		@yield('content')
		<table border="0" width="50%" bgcolor="#44525f" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;">
			<tr>
			  <td align="center" class="footercopy" style="padding: 3%;">
				&reg; Fumaco 2021<br/>
			  </td>
			</tr>
		</table>
   </body>
</html>