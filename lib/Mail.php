<?php
$config = include(dirname(__FILE__) . '/../config.php');
require_once dirname(__FILE__).'/dompdf/lib/html5lib/Parser.php';
//require_once dirname(__FILE__).'/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
//require_once dirname(__FILE__).'/dompdf/lib/php-svg-lib/src/autoload.php';
require_once dirname(__FILE__).'/dompdf/src/Autoloader.php';
require_once dirname(__FILE__).'/php-svg-lib-master/src/autoload.php';
Dompdf\Autoloader::register();
use Dompdf\Dompdf;
use Dompdf\Options;

define("DOMPDF_ENABLE_REMOTE", false);
error_reporting(E_ALL&~E_NOTICE);
/**
 * Generate PDF File from HTML 
 * @param string $template  template name 
 * @param array $keysAndValues keys and values to replace in the template 
 * @param string $pdfFile filename of PDF generated 
 * @return integer 0 ok 1: error
 **/
function generatePdf(string $templateFile, array $keysAndValues, string $pdfFile){
    define('SMARTY_DIR', dirname(__FILE__).'/smarty-3.1.33/libs/');
    require_once(SMARTY_DIR . 'Smarty.class.php');
    $smarty = new Smarty();
    $smarty->setTemplateDir(dirname(__FILE__) . '/../var/template/');
    $smarty->setCompileDir(dirname(__FILE__) . '/../var/smarty/templates_c/');
    $smarty->setConfigDir(dirname(__FILE__) . '/../var/smarty/configs/');
    $smarty->setCacheDir(dirname(__FILE__) . '/../var/smarty/cache/');
    foreach($keysAndValues as $key=> $value){
        $smarty->assign($key,$value);
    }
    //$smarty->debugging = true;
    $output = $smarty->fetch($templateFile);//display

    $options = new Options();
    $options->set('defaultFont', 'Courier');
    $options->set('enable_remote', false);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($output);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $outputPDF = $dompdf->output();
    file_put_contents($pdfFile, $outputPDF);
    
    return 0;
}

/**
 * Generate MailContent 
 * @param string $template  template name 
 * @param array $keysAndValues keys and values to replace in the template 
 * @return string content from mail 
 **/
function generateAndGetMailContent(string $templateFile, array $keysAndValues){
    define('SMARTY_DIR', dirname(__FILE__).'/smarty-3.1.33/libs/');
    require_once(SMARTY_DIR . 'Smarty.class.php');
    $smarty = new Smarty();
    $smarty->setTemplateDir(dirname(__FILE__) . '/../var/template/');
    $smarty->setCompileDir(dirname(__FILE__) . '/../var/smarty/templates_c/');
    $smarty->setConfigDir(dirname(__FILE__) . '/../var/smarty/configs/');
    $smarty->setCacheDir(dirname(__FILE__) . '/../var/smarty/cache/');
    foreach($keysAndValues as $key=> $value){
        $smarty->assign($key,$value);
    }
    //$smarty->debugging = true;
    $output = $smarty->fetch($templateFile);//display
    
    return $output;

}
//generatePdf('mail-001.tpl', array(), 'outputfile.pdf');

//Close db 
