<?php
require_once(dirname(__FILE__) . '/../lib/Mail.php');

if ($argc < 2 )
{
    exit( "Usage: mail-001.php <period YYYY-mm>\n" );
} 

$period = explode('-', $argv[1]);

$options = include(dirname(__FILE__) . '/../config.php');

$connection = new PDO("mysql:dbname=" . $options['database']['name'] . ";host=" . $options['database']['host'],
$options['database']['user'],
$options['database']['pass']);
$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); 


$sql = "select
concat(lpad(o.redeem_store_id,4,'0'),'" . $period[0] . $period[1] . "') as detail_number
,'Soles' as detail_currency
,s.id as supplier_id
,st.id as store_id
,o.redeem_store_name
,st.email
,o.id
,o.used_date
,o.bonus_short_title
,o.consultant_document_number
,@st:=round(o.bonus_special_price/1.18,2) as 'subtotal'
,round(o.bonus_special_price-@st,2) as 'igv'
,round(o.bonus_special_price,2) as total
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
print_r($rows);

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
//generatePdf('mail-001.tpl', array(), 'outputfile.pdf');