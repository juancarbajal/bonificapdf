<?php return  array (
'database' => array(
		   'host' => 'localhost',
		   'name' => 'prod_bonificacion',
		   'user'=> 'jcarbajal',
		   'pass'=>'P@ssw0rd'
		   ),

'appInfo' => array(
		  'appCompanyName' => 'Twinkle SAC',
		  'appCompanyRuc' => '20604281424',
		  'appCompanyAddress' => 'Av. Juan De Aliaga 427 Lima, Lima, Magdalena Del Mar',
		  'appName' => 'Twinkle',
		  'appPhone' => '956 772 024',
		  'appMail' => 'contabilidad@twinkle.pe',
		  'appLogo' => dirname(__FILE__) . '/var/static/logo-original-twinkle.jpg'
		  
		  ),
'mail' => array(
	   'host' => 'smtp.gmail.com',
	   'port' => '587',
	   'charset'=> 'utf-8',
	   'from' => 'twinkleproveedores@gmail.com', //'reportes@twinklelatam.com',
	   'nameFrom' => 'Twinkle',
	   'reply' => 'no-reply@twinklelatam.com',
	   'cc' => 'ljordan@twinklelatam.com',
	   'password' => 'Twinkle*2019' //'Seidor2019$'
)

);
?>