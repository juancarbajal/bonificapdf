<?php
/**
 * Generate PDF File from HTML 
 * @param string $template  template name 
 * @param array $values values to replace in template 
 * @return string PDF file url
 **/
function generate(string $template, array $values){
    return 0;
}

$config = include(dirname(__FILE__) . '/../config.php');
//Open db
//$connection = new PDO("mysql:dbname=" . $config['database']['name'] . ";host=" . $config['database']['host'], $config['database']['user'], //$config['database']['pass']);
//Read all companies
//Foreach company read all documents from period (YYYYMM)
//Create PDFFile
define('SMARTY_DIR', dirname(__FILE__).'/smarty-3.1.33/libs/');
require_once(SMARTY_DIR . 'Smarty.class.php');
$smarty = new Smarty();
$smarty->setTemplateDir(dirname(__FILE__) . '/../var/template/');
$smarty->setCompileDir(dirname(__FILE__) . '/../var/smarty/templates_c/');
$smarty->setConfigDir(dirname(__FILE__) . '/../var/smarty/configs/');
$smarty->setCacheDir(dirname(__FILE__) . '/../var/smarty/cache/');
$smarty->assign('companyName','Ned');
//$smarty->debugging = true;
$output = $smarty->fetch('mail-001.tpl');//display

require_once dirname(__FILE__).'/dompdf/lib/html5lib/Parser.php';
require_once dirname(__FILE__).'/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once dirname(__FILE__).'/dompdf/lib/php-svg-lib/src/autoload.php';
require_once dirname(__FILE__).'/dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();

//Close db 
