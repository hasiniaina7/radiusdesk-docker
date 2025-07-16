<?php

function convertPngToJpeg($pngPath, $jpegPath, $quality = 90) {
    if (!extension_loaded('gd')) {
        return false;
    }

    $image = imagecreatefrompng($pngPath);
    if (!$image) {
        return false;
    }

    // Créer un fond blanc pour remplacer la transparence
    $width = imagesx($image);
    $height = imagesy($image);
    $white_bg = imagecreatetruecolor($width, $height);
    $white = imagecolorallocate($white_bg, 255, 255, 255);
    imagefill($white_bg, 0, 0, $white);

    // Fusionner l'image PNG avec le fond blanc
    imagecopymerge($white_bg, $image, 0, 0, 0, 0, $width, $height, 100);

    // Sauvegarder en JPEG
    $result = imagejpeg($white_bg, $jpegPath, $quality);

    // Libérer la mémoire
    imagedestroy($image);
    imagedestroy($white_bg);

    return $result;
}
// Suppress all PHP errors and warnings
error_reporting(0);
ini_set('display_errors', 0);

// Start output buffering to prevent header issues
ob_start();

// Define constants
define('ROOT', __DIR__);
define('DS', DIRECTORY_SEPARATOR);
define('WWW_ROOT', __DIR__ . DS);

//require_once( "rdpdf.php");
require_once(ROOT . DS . 'vendor' . DS . "radiusdesk" . DS . "rdpdf" . DS . "rdpdf.php");

// Define sample output instructions
$output_instr = array(
    'format' => 'a4',
    'orientation' => 'P',
    'rtl' => false,
    'date' => true,
    'social_media' => false,
    'realm_detail' => true,
    't_and_c' => false,
    'profile_detail' => true,
    'extra_fields' => false,
    'logo_or_qr' => 'logo'
);

//echo $voucher_data;
// Define sample voucher data
/*$voucher_data = array(
    'realm1' => array(
        'name' => 'Sample WiFi Network',
        'icon_file_name' => 'default_logo.png',
        'street_no' => '123',
        'street' => 'Main Street',
        'town_suburb' => 'Downtown',
        'city' => 'Sample City',
        'country' => 'Sample Country',
        'lat' => 0,
        'lon' => 0,
        'url' => 'http://example.com',
        'email' => 'info@example.com',
        'phone' => '+1234567890',
        'cell' => '+0987654321',
        'fax' => '+1122334455',
        't_c_title' => 'Terms and Conditions',
        't_c_content' => "Rule 1: Use responsibly\nRule 2: No illegal activities\nRule 3: Respect bandwidth limits",
        'vouchers' => array(
            array(
                'logo'=>'img/realms/default_logo.png',
                'username' => 'user001',
                'password' => 'pass001',
                'profile' => 'Standard',
                'days_valid' => '7-00-00',
                'expiration' => '2024-12-31',
                'extra_name' => 'Location',
                'extra_value' => 'Building A'
            ),
            array(
                'username' => 'user002',
                'password' => 'pass002',
                'profile' => 'Premium',
                'days_valid' => '30-00-00',
                'expiration' => '2024-12-31',
                'extra_name' => 'Location',
                'extra_value' => 'Building B'
            )
        )
    )
);*/

function debug_console($data) {
    echo "<pre style='background:#111;color:#0f0;padding:10px;font-family:monospace;'>";
    print_r($data);
    echo "</pre>";
}

debug_console($voucher_data);


if(($output_instr['format'] == 'a4')||($output_instr['format'] == 'a4_page')){

  /*We use contants which had default values:
  TCPDF::__construct 	( 	  	
      $orientation = 'P',
        $unit = 'mm',
        $format = 'A4',
        $unicode = true,
        $encoding = 'UTF-8',
        $diskcache = false,
        $pdfa = false 
  ) 
  */		

    $pdf = new VoucherPdf($output_instr['orientation'], 'mm', 'A4', true, 'UTF-8', false);

    // set document (meta) information
    $pdf->SetCreator('TCPDF');
    $pdf->SetAuthor('RADIUSdesk');
    $pdf->SetTitle(__('Internet Access Voucher'));
    $pdf->SetSubject(__('Internet Access Voucher'));
    $pdf->SetKeywords(__('Internet Access Voucher'));

    $pdf->Title = __("Internet Access Voucher"); 
    $pdf->setRTL($output_instr['rtl']);

  //We attach the output instructions to the PDF
  $pdf->OutputInstr = $output_instr;

    // Fonction pour traiter les logos
    function processLogo($originalPath) {
        $pathInfo = pathinfo($originalPath);

        // Si c'est un PNG, essayer de le convertir
        if (strtolower($pathInfo['extension']) === 'png') {
            $jpegPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.jpg';

            if (convertPngToJpeg($originalPath, $jpegPath)) {
                return $jpegPath;
            }
        }

        return $originalPath;
    }
    
    //A4 all vouchers per realm
   /* if($output_instr['format'] == 'a4'){
        foreach(array_keys($voucher_data) as $key){
            $d = $voucher_data["$key"];
            // Traiter le logo
            $logoPath = 'img/realms/'.$d['icon_file_name'];
            $pdf->Logo = processLogo($logoPath);
      $pdf->RealmDetail	= $d;

      // add a page
            $pdf->AddPage();
      // do the vouchers
            $pdf->AddVouchers($d['vouchers']);
        } 
    }*/

if($output_instr['format'] == 'a4'){
        foreach(array_keys($voucher_data) as $key){
            $d = $voucher_data["$key"];
            // Traiter le logo
            $logoPath = 'webroot/img/realms/'.$d['icon_file_name'];
            $pdf->Logo = processLogo($logoPath);
            $pdf->RealmDetail	= $d;

      // add a page
            $pdf->AddPage();
      // do the vouchers
            $pdf->AddVouchers($d['vouchers']);
        } 
    }

    //A4 page per voucher
    if($output_instr['format'] == 'a4_page'){
        foreach(array_keys($voucher_data) as $key){
            $d = $voucher_data["$key"];
      //print_r($d);
            foreach($d['vouchers'] as $v){
        $pdf->RealmDetail = $d;
                // Traiter le logo
                $logoPath = 'webroot/img/realms/'.$d['icon_file_name'];
                $pdf->Logo = processLogo($logoPath);
                // add a page
                $pdf->AddPage();
                $pdf->AddVouchers(array($v));
            }
        } 
    }
    // Clean output buffer before PDF output
    ob_end_clean();
    
    //Close and output PDF document
    $pdf->Output('test.pdf', 'I');

}else{

    $pdf = new LabelPdf($output_instr['format']);
    $pdf->setRTL($output_instr['rtl']);
    $pdf->AddPage();
    $pdf->OutputInstr = $output_instr;
    foreach(array_keys($voucher_data) as $key){
        $d = $voucher_data["$key"];
        foreach($d['vouchers'] as $v){
            // Traiter le logo
            $logoPath = 'webroot/img/realms/'.$d['icon_file_name'];
            $pdf->Logo = processLogo($logoPath);
            $pdf->Add_Label($v);
        }
    } 
    
    // Clean output buffer before PDF output
    ob_end_clean();
    $pdf->Output('test.pdf', 'I');
}

//===============================
//Very important to 'reset' this
mb_internal_encoding('UTF-8');
//===============================

?>

