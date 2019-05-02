<?php
require_once(dirname(__FILE__) . '/../lib/Mail.php');
require dirname(__FILE__) . '/../lib/PHPMailer/src/Exception.php';
require dirname(__FILE__) . '/../lib/PHPMailer/src/PHPMailer.php';
require dirname(__FILE__) . '/../lib/PHPMailer/src/SMTP.php';

if ($argc < 2 )
    {
        exit( "Usage: mail-001.php <period YYYY-mm>\n" );
    } 

echo '========== Periodo : ' . $argv[1] . " ==========\n";

$period = explode('-', $argv[1]);

$dtDate= new DateTime($period[0] . '-' . $period[1] . '-01');

$options = include(dirname(__FILE__) . '/../config.php');

$connection = new PDO("mysql:dbname=" . $options['database']['name'] . ";host=" . $options['database']['host'] . ';charset=utf8',
$options['database']['user'],
$options['database']['pass']);
$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 

$months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre','Diciembre'];
$monthName = $months[$period[1]-1];
$firstDate = $dtDate->format('d/m/Y');
$lastDate = $dtDate->format('t/m/Y');

$sql = "select
concat(lpad(o.redeem_store_id,4,'0'),'" . $period[0] . $period[1] . "') as docNumber
,s.id as supplier_id
,st.id as store_id
,o.redeem_store_name as companyName
,st.email
,o.id as idPedido
,o.used_date as datePedido
,o.bonus_short_title as bonificacionPedido
,o.consultant_document_number as dniPedido
,@st:=round(o.bonus_special_price/1.18,2) as 'subtotalPedido'
,round(o.bonus_special_price-@st,2) as 'igvPedido'
,round(o.bonus_special_price,2) as 'totalPedido'
,o.status
from orders o
inner join suppliers s on o.redeem_supplier_id = s.id
inner join stores st on o.redeem_store_id = st.id
where
extract( year from o.used_date) = '" . $period[0] . "' and extract( month from o.used_date) ='" . $period[1] . "'
and status='used'
order by o.redeem_supplier_id asc, st.id, o.used_date asc; ";
//and status='used'
//echo $sql;
    
$stmt = $connection->prepare($sql);
$stmt->execute(null);
$rows = $stmt->fetchAll();
//print_r($rows);
$groupData = array();
foreach($rows as $rs){
    $docNumber = $rs['docNumber'];
    if (!isset($groupData[$docNumber])) {
        $groupData[$docNumber] = array(
            'docNumber' => $rs['docNumber'],
            'docYear' => $period[0],
            'docFrom' => $firstDate,
            'docTo' => $lastDate,
            'companyName' => $rs['companyName'],
            'docCurrency' => 'Soles',
            'docCurrencySym' => 'S/.',
            'monthName' => $monthName,
            'docTotal' => 0.00,
            'docSubtotal' => 0.00,
            'docIgv' => 0.00, 
            'email' => $rs['email'],
            'detail' => array());
        $groupData[$docNumber] = array_merge($groupData[$docNumber], $config['appInfo']);

    }
    $groupData[$docNumber]['docTotal'] += $rs['totalPedido'];
    $groupData[$docNumber]['docSubtotal'] += $rs['subtotalPedido'];
    $groupData[$docNumber]['docIgv'] += $rs['igvPedido'];
    array_push($groupData[$docNumber]['detail'], $rs);
}
//print_r($groupData);exit;
//print_r($groupData);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
foreach($groupData as $rs){
    $mailContent = generateAndGetMailContent('mail-001.tpl',$rs);
    //echo $mailContent;
    $pdfFile = dirname(__FILE__) . '/../var/pdf/' . $rs['docNumber'] . '.pdf';
    generatePdf('mail-001.pdf.tpl', $rs, $pdfFile);

    $mail = new PHPMailer;
    //$mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->SMTPAuth   = true;
    
    $mail->Host = $options['mail']['host'];
    $mail->Port = $options['mail']['port'];
    $mail->Username = $options['mail']['from'];
    $mail->Password = $options['mail']['password'];
    $mail->SMTPSecure = 'tls';
    $mail->CharSet = $options['mail']['charset'];
    $mail->setFrom($options['mail']['from'], $options['mail']['nameFrom']);
    echo 'DocNumber : ' . $rs['docNumber'] . "\n";
    echo 'CompanyName : ' . $rs['companyName'] . "\n";
    echo 'Email : ' . $rs['email'] . "\n";  
    if (isset($rs['email'])) {
        $arrEmail = explode(',', $rs['email']);
        foreach($arrEmail as $email)
            if (isValidEmail(trim($email))) $mail->addAddress(trim($email));
    }
    
    
    $mail->addReplyTo($options['mail']['reply'], $options['mail']['reply']);
    $mail->addAddress($options['mail']['cc']);
    $mail->Subject = 'Reporte de redenciones ' . $rs['companyName'];
    $mail->Body = $mailContent;
    $mail->addAttachment($pdfFile);
    //print_r($mail);
    if (!$mail->send()) {
        $msg .= "Mailer Error: " . $mail->ErrorInfo;
    } else {
        $msg .= "Message sent!";
    }
    echo $msg . "\n";
}
/*
  $redemStore = '---';
  $dataMail =  $config['appInfo'];
  $dataMail['detail'] = array();
  foreach ($rows as $rs) {
  if ($rs['detail_number'] != $redemStore) {
  //es un nuevo documento
  //enviamos el array
  if (count($dataMail['detail'])>0){
  }
  //reiniciamos el array
  $redemStore = $rs['detail_number'];
  $dataMail['detail'] = array();
  } else {
  //es el mismo documento, lo ponemos en la lista  
  array_push($dataMail['detail'], $rs);
  }
  }
*/
//generatePdf('mail-001.tpl', array(), 'outputfile.pdf');
