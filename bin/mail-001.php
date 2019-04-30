<?php
require_once(dirname(__FILE__) . '/../lib/Mail.php');

if ($argc < 2 )
    {
        exit( "Usage: mail-001.php <period YYYY-mm>\n" );
    } 

$period = explode('-', $argv[1]);

$options = include(dirname(__FILE__) . '/../config.php');

$connection = new PDO("mysql:dbname=" . $options['database']['name'] . ";host=" . $options['database']['host'] . ';charset=utf8',
$options['database']['user'],
$options['database']['pass']);
$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 

$months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre','Diciembre'];
$monthName = $months[$period[1]-1];

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
inner join stores st on st.supplier_id=s.id and st.company_id=s.company_id
where
extract( year from o.used_date) = '" . $period[0] . "' and extract( month from o.used_date) ='" . $period[1] . "'
and status='used'
order by o.redeem_supplier_id asc, st.id, o.used_date asc; ";

//echo $sql;
    
$stmt = $connection->prepare($sql);
$stmt->execute(null);
$rows = $stmt->fetchAll();

$groupData = array();
foreach($rows as $rs){
    $docNumber = $rs['docNumber'];
    $groupData[$docNumber] = array(
        'docNumber' => $rs['docNumber'],
        'companyName' => $rs['companyName'],
        'docCurrency' => 'Soles',
        'docCurrencySym' => 'S/.',
        'monthName' => $monthName,
        'docTotal' => 0.00,
        'docSubtotal' => 0.00,
        'docIgv' => 0.00, 
        'detail' => array());
    $groupData[$docNumber]['docTotal'] += $rs['totalPedido'];
    $groupData[$docNumber]['docSubtotal'] += $rs['subtotalPedido'];
    $groupData[$docNumber]['docIgv'] += $rs['igvPedido'];
    $groupData[$docNumber] = array_merge($groupData[$docNumber], $config['appInfo']);
    array_push($groupData[$docNumber]['detail'], $rs); 
}

print_r($groupData);

foreach($groupData as $rs){
    $mailContent = generateAndGetMailContent('mail-001.tpl',$rs);
    echo $mailContent;
    generatePdf('mail-001.pdf.tpl', $rs, $rs['docNumber'] . '.pdf');
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