<?php

function post_request($url, array $params) {
$postdata = http_build_query(
    $params
);

$opts = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peername' => false
        // Instead ideally use
        // 'cafile' => 'path Certificate Authority file on local filesystem'
    ),
    'http' => array(
        'method' => 'POST',
        'header' => 'Content-type: application/x-www-form-urlencoded'."\r\n",
        'content' => $postdata
    )
);

$context = stream_context_create($opts);

return file_get_contents($url, false, $context);
}

function deleteOlderFiles($path,$days) {
  if ($handle = opendir($path)) {
    while (false !== ($file = readdir($handle))) {
      $filelastmodified = filemtime($path . $file);
      if((time() - $filelastmodified) > $days*24*3600)
      {
        if(is_file($path . $file)) {
          unlink($path . $file);
        }
      }
    }
    closedir($handle);
  }
}

deleteOlderFiles('uploads/', 7);

$target_file_to = "./uploads/download_".uniqid().".txt";
if(isset($_POST['txt']) || isset($_POST['txtfile'])) {

$txt = $_POST['txt'];
if(isset($_FILES['txtfile'])) {
$target_file = "./uploads/download_".uniqid().".txt";
if (move_uploaded_file($_FILES['txtfile']["tmp_name"], $target_file))
	$txt = file_get_contents($target_file);
$txtfile=$target_file;
$txtTofile=$target_file_to;
}

$from = $_POST['from'];
$to = $_POST['to'];
// When you have your own client ID and secret, put them down here:
$CLIENT_ID = "bilkedominic@gmail.com";
$CLIENT_SECRET = "07bbc21c01524c62b757f1be54b493dc";
$txtTo ="";

$output = str_split($_POST['txt'], 2400);

foreach($output as $txttmp) {
// Specify your translation requirements here:
$postData = array(
  'fromLang' => $_POST['from'],
  'toLang' => $_POST['to'],
  'text' => $txttmp
);

$headers = array(
  'Content-Type: application/json',
  'X-WM-CLIENT-ID: '.$CLIENT_ID,
  'X-WM-CLIENT-SECRET: '.$CLIENT_SECRET
);


$url = 'http://api.whatsmate.net/v1/translation/translate';
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

$txtTo .= curl_exec($ch);

curl_close($ch);
}

file_put_contents($target_file_to, $txtTo);

$speech_arr = array('ar' => 'ar-eg',
'bg' => 'bg-bg',
'ca' => 'ca-es',
'zh-CN' => 'zh-cn',
'hr' => 'hr-hr',
'cs' => 'cs-cz',
'da' => 'da-dk',
'nl' => 'nl-be',
'en' => 'en-us',
'fi' => 'fi-fi',
'fr' => 'fr-fr',
'de' => 'de-de',
'el' => 'el-gr',
'hi' => 'hi-in',
'hu' => 'hu-hu',
'id' => 'id-id',
'it' => 'it-it',
'ja' => 'ja-jp',
'ko' => 'ko-kr',
'ms' => 'ms-my',
'nb' => 'nb-no', 
'pl' => 'pl-pl',
'pt' => 'pt-pt',
'ro' => 'ro-ro',
'ru' => 'ru-ru',
'sk' => 'sk-sk',
'sl' => 'sl-si',
'es' => 'es-es',
'sv' => 'sv-se',
'ta' => 'ta-in',
'th' => 'th-th',
'tr' => 'tr-tr',
'vi' => 'vi-vn');

}

$tesseract_arr = array('ar' => 'ar-eg',
'bg' => 'bul',
'ca' => 'cat',
'zh-CN' => 'chi_sim',
'hr' => 'hrv',
'cs' => 'ces',
'da' => 'dan',
'nl' => 'nld',
'en' => 'eng',
'fi' => 'fin',
'fr' => 'fra',
'de' => 'deu',
'el' => 'ell',
'hi' => 'hin',
'hu' => 'hun',
'id' => 'ind',
'it' => 'ita',
'ja' => 'jpn',
'ko' => 'kor',
'ms' => 'msa',
'nb' => 'nor', 
'po' => 'pol',
'pt' => 'por',
'ro' => 'ron',
'ru' => 'rus',
'sk' => 'slk',
'sl' => 'slv',
'es' => 'spa',
'sv' => 'swe',
'ta' => 'tam',
'th' => 'tha',
'tr' => 'tur',
'vi' => 'vie');

if(isset($_FILES['pdffile']["name"])) {
$target_dir = "uploads/";
$target_file = $target_dir . uniqid().'_'.basename($_FILES["pdffile"]["name"]);
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$target_file = $target_dir . uniqid().'.'.$imageFileType;
if (move_uploaded_file($_FILES["pdffile"]["tmp_name"], $target_file))
{
$Url      = "https://text-konvertierung.bilke-projects.com/convert_file.php?lang=".$tesseract_arr[$_POST['from']]."&fileToUpload=".$target_file; 
$txt = file_get_contents($Url);
}
}
?>

<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    
    <!--====== Title ======-->
    <title>Text-Konvertierung</title>
    
    <meta name="description" content="Text Konvertierung in Übersetzung und Sprache.">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--====== Favicon Icon ======-->
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/png">
        
    <!--====== Animate CSS ======-->
    <link rel="stylesheet" href="assets/css/animate.css">
        
    <!--====== Magnific Popup CSS ======-->
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
        
    <!--====== Slick CSS ======-->
    <link rel="stylesheet" href="assets/css/slick.css">
        
    <!--====== Line Icons CSS ======-->
    <link rel="stylesheet" href="assets/css/LineIcons.css">
        
    <!--====== Font Awesome CSS ======-->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
        
    <!--====== Bootstrap CSS ======-->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    
    <!--====== Default CSS ======-->
    <link rel="stylesheet" href="assets/css/default.css">
    
    <!--====== Style CSS ======-->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <link rel="stylesheet" href="./style.css" />

      <link href="./index.css" rel="stylesheet" />

</head>

<body>
    <!--[if IE]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
  <![endif]-->    
   
   
    <!--====== PRELOADER PART START ======-->

    <div class="preloader">
        <div class="loader">
            <div class="ytp-spinner">
                <div class="ytp-spinner-container">
                    <div class="ytp-spinner-rotator">
                        <div class="ytp-spinner-left">
                            <div class="ytp-spinner-circle"></div>
                        </div>
                        <div class="ytp-spinner-right">
                            <div class="ytp-spinner-circle"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--====== PRELOADER PART ENDS ======-->
    
    <!--====== HEADER PART START ======-->
    
    <header class="header-area">
        <div class="navbar-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <nav class="navbar navbar-expand-lg">
                            <a class="navbar-brand" href="index.html">
                                <!--img src="assets/images/logo.svg" alt="Logo"-->
                            </a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                                <ul id="nav" class="navbar-nav ml-auto">
                                    <li class="nav-item active">
                                        <a class="page-scroll" href="#start">Start</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll" href="#ocr">OCR</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll" href="#text-eingeben">Text</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll" href="#uebersetzung">Übersetzung</a>
                                    </li>
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll" href="#sprache">Sprache</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll" href="#impressum">Impressum</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll" href="#datenschutz">Datenschutz</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll" href="#kontakt">Kontakt</a>
                                    </li>
                                </ul>
                            </div> <!-- navbar collapse -->
                            
                            <!--div class="navbar-btn d-none d-sm-inline-block">
                                <a class="main-btn" data-scroll-nav="0" href="#pricing">Free Trial</a>
                            </div-->
                        </nav> <!-- navbar -->
                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
        </div> <!-- navbar area -->
        <div id="start" class="header-hero bg_cover" style="background-image: url(assets/images/banner-bg.svg)">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="header-hero-content text-center">
                            <h3 class="header-sub-title wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.2s">Text-Konvertierung</h3><br>
                            <h2 class="header-title wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.5s">Mit diesem Tool können Sie Ihre Texte von OCR als Textübersetzung oder Text zu Sprache konvertieren.<br> Bitte geben Sie Ihre Texte ein!</h2>
                            <p class="text wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.8s">OCR und Text Konvertierung in Übersetzung und Sprache.</p>
                            <a href="#ocr" class="main-btn wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="1.1s">Los geht's!</a>
                        </div> <!-- header hero content -->
                    </div>
                </div> <!-- row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="header-hero-image text-center wow fadeIn" data-wow-duration="1.3s" data-wow-delay="1.4s">
                            <img src="assets/images/invisible.png" alt="hero">
                        </div> <!-- header hero image -->
                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
            <div id="particles-1" class="particles"></div>
        </div> <!-- header hero -->
    </header>
    
    <!--====== HEADER PART ENDS ======-->
    
    
    
    
    <!--====== ABOUT PART START ======-->
    
    
    <section id="ocr" class="about-area pt-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="about-content mt-50 wow fadeInLeftBig" data-wow-duration="1s" data-wow-delay="0.5s">
                        <div class="section-title">
                            <div class="line"></div>
                            <h3 class="title">Texterkennung:</h3><br>
                        </div> <!-- section title -->
                   <img src="assets/images/erkennung.png" alt="about"> 

                    </div> <!-- about content -->
                </div>
                <div class="col-lg-6">
                    <div class="about-image text-center mt-50 wow fadeInRightBig" data-wow-duration="1s" data-wow-delay="0.5s">
                           	<form method="post"  action="index.php#text-eingeben" name="formular" enctype="multipart/form-data">
<div style="text-align:center;">
  <label>Wählen Sie eine PDF- oder BILD-Datei von Ihrem Rechner aus und die Sprache von der Datei: <br><br>
    <input name="pdffile" type="file" size="100" accept=".jpg, .jpeg, .png, .gif, .pdf"> 
  </label>  
</div>
<label for="from">von Sprache:</label><br>
<select id="from" name="from">
    <option value="de">German - Deutsch</option>
    <option value="bg">Bulgarian - български</option>
    <option value="ca">Catalan - català</option>
    <option value="zh">Chinese - 中文</option>
    <option value="hr">Croatian - hrvatski</option>
    <option value="cs">Czech - čeština</option>
    <option value="da">Danish - dansk</option>
    <option value="nl">Dutch - Nederlands</option>
    <option value="en">English</option>
    <option value="fi">Finnish - suomi</option>
    <option value="fr">French - français</option>
    <option value="de">German - Deutsch</option>
    <option value="el">Greek - Ελληνικά</option>
    <option value="hi">Hindi - हिन्दी</option>
    <option value="hu">Hungarian - magyar</option>
    <option value="id">Indonesian - Indonesia</option>
    <option value="it">Italian - italiano</option>
    <option value="ms">Malay - Bahasa Melayu</option>
    <option value="pl">Polish - polski</option>
    <option value="pt">Portuguese - português</option>
    <option value="ro">Romanian - română</option>
    <option value="ru">Russian - русский</option>
    <option value="sk">Slovak - slovenčina</option>
    <option value="es">Spanish - español</option>
    <option value="sv">Swedish - svenska</option>
    <option value="ta">Tamil - தமிழ்</option>
    <option value="tr">Turkish - Türkçe</option>
    <option value="vi">Vietnamese - Tiếng Việt</option>
</select><br/><br/>
<input name="submit" type="submit" value="Erkennen" class="main-btn" />
</form>
                    </div> <!-- about image -->
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
        <div class="about-shape-1">
            <img src="assets/images/about-shape-1.svg" alt="shape">
        </div>
    </section>

    <section id="text-eingeben" class="about-area pt-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="about-content mt-50 wow fadeInLeftBig" data-wow-duration="1s" data-wow-delay="0.5s">
                        <div class="section-title">
                            <div class="line"></div>
                            <h3 class="title">Text eingeben:</h3><br>
                        </div> <!-- section title -->
                   <img src="assets/images/book.jpg" alt="about"> 

                    </div> <!-- about content -->
                </div>
                <div class="col-lg-6">
                    <div class="about-image text-center mt-50 wow fadeInRightBig" data-wow-duration="1s" data-wow-delay="0.5s">
                           	<form method="post"  action="index.php#uebersetzung" name="formular" enctype="multipart/form-data">
<textarea id="txt" name="txt"  rows="15" cols="45" ><?php echo $txt; ?></textarea><br/><br/>
<label for="from">von Sprache:</label><br>
<select id="from" name="from">
    <option value="de"<?php if($from=="de") echo " selected "; ?>>German - Deutsch</option>
    <option value="bg"<?php if($from=="bg") echo " selected "; ?>>Bulgarian - български</option>
    <option value="ca"<?php if($from=="ca") echo " selected "; ?>>Catalan - català</option>
    <option value="zh"<?php if($from=="zh") echo " selected "; ?>>Chinese - 中文</option>
    <option value="hr"<?php if($from=="hr") echo " selected "; ?>>Croatian - hrvatski</option>
    <option value="cs"<?php if($from=="cs") echo " selected "; ?>>Czech - čeština</option>
    <option value="da"<?php if($from=="da") echo " selected "; ?>>Danish - dansk</option>
    <option value="nl"<?php if($from=="nl") echo " selected "; ?>>Dutch - Nederlands</option>
    <option value="en"<?php if($from=="en") echo " selected "; ?>>English</option>
    <option value="fi"<?php if($from=="fi") echo " selected "; ?>>Finnish - suomi</option>
    <option value="fr"<?php if($from=="fr") echo " selected "; ?>>French - français</option>
    <option value="de"<?php if($from=="de") echo " selected "; ?>>German - Deutsch</option>
    <option value="el"<?php if($from=="el") echo " selected "; ?>>Greek - Ελληνικά</option>
    <option value="hi"<?php if($from=="hi") echo " selected "; ?>>Hindi - हिन्दी</option>
    <option value="hu"<?php if($from=="hu") echo " selected "; ?>>Hungarian - magyar</option>
    <option value="id"<?php if($from=="id") echo " selected "; ?>>Indonesian - Indonesia</option>
    <option value="it"<?php if($from=="it") echo " selected "; ?>>Italian - italiano</option>
    <option value="ms"<?php if($from=="ms") echo " selected "; ?>>Malay - Bahasa Melayu</option>
    <option value="pl"<?php if($from=="pl") echo " selected "; ?>>Polish - polski</option>
    <option value="pt"<?php if($from=="pt") echo " selected "; ?>>Portuguese - português</option>
    <option value="ro"<?php if($from=="ro") echo " selected "; ?>>Romanian - română</option>
    <option value="ru"<?php if($from=="ru") echo " selected "; ?>>Russian - русский</option>
    <option value="sk"<?php if($from=="sk") echo " selected "; ?>>Slovak - slovenčina</option>
    <option value="es"<?php if($from=="es") echo " selected "; ?>>Spanish - español</option>
    <option value="sv"<?php if($from=="sv") echo " selected "; ?>>Swedish - svenska</option>
    <option value="ta"<?php if($from=="ta") echo " selected "; ?>>Tamil - தமிழ்</option>
    <option value="tr"<?php if($from=="tr") echo " selected "; ?>>Turkish - Türkçe</option>
    <option value="vi"<?php if($from=="vi") echo " selected "; ?>>Vietnamese - Tiếng Việt</option>
</select><br/><br/>
<label for="to">zu Sprache:</label><br>
<select id="to" name="to">
    <option value="en"<?php if($to=="en") echo " selected "; ?>>English</option>
    <option value="bg"<?php if($to=="bg") echo " selected "; ?>>Bulgarian - български</option>
    <option value="ca"<?php if($to=="ca") echo " selected "; ?>>Catalan - català</option>
    <option value="zh-CN"<?php if($to=="zh-CN") echo " selected "; ?>>Chinese - 中文</option>
    <option value="hr"<?php if($to=="hr") echo " selected "; ?>>Croatian - hrvatski</option>
    <option value="cs"<?php if($to=="cs") echo " selected "; ?>>Czech - čeština</option>
    <option value="da"<?php if($to=="da") echo " selected "; ?>>Danish - dansk</option>
    <option value="nl"<?php if($to=="nl") echo " selected "; ?>>Dutch - Nederlands</option>
    <option value="en"<?php if($to=="en") echo " selected "; ?>>English</option>
    <option value="fi"<?php if($to=="fi") echo " selected "; ?>>Finnish - suomi</option>
    <option value="fr"<?php if($to=="fr") echo " selected "; ?>>French - français</option>
    <option value="de"<?php if($to=="de") echo " selected "; ?>>German - Deutsch</option>
    <option value="el"<?php if($to=="el") echo " selected "; ?>>Greek - Ελληνικά</option>
    <option value="hi"<?php if($to=="hi") echo " selected "; ?>>Hindi - हिन्दी</option>
    <option value="hu"<?php if($to=="hu") echo " selected "; ?>>Hungarian - magyar</option>
    <option value="id"<?php if($to=="id") echo " selected "; ?>>Indonesian - Indonesia</option>
    <option value="it"<?php if($to=="it") echo " selected "; ?>>Italian - italiano</option>
    <option value="ms"<?php if($to=="ms") echo " selected "; ?>>Malay - Bahasa Melayu</option>
    <option value="pl"<?php if($to=="pl") echo " selected "; ?>>Polish - polski</option>
    <option value="pt"<?php if($to=="pt") echo " selected "; ?>>Portuguese - português</option>
    <option value="ro"<?php if($to=="ro") echo " selected "; ?>>Romanian - română</option>
    <option value="ru"<?php if($to=="ru") echo " selected "; ?>>Russian - русский</option>
    <option value="sk"<?php if($to=="sk") echo " selected "; ?>>Slovak - slovenčina</option>
    <option value="es"<?php if($to=="es") echo " selected "; ?>>Spanish - español</option>
    <option value="sv"<?php if($to=="sv") echo " selected "; ?>>Swedish - svenska</option>
    <option value="ta"<?php if($to=="ta") echo " selected "; ?>>Tamil - தமிழ்</option>
    <option value="tr"<?php if($to=="tr") echo " selected "; ?>>Turkish - Türkçe</option>
    <option value="vi"<?php if($to=="vi") echo " selected "; ?>>Vietnamese - Tiếng Việt</option>
</select><br/><br/>
<input name="submit" type="submit" value="Konvertieren" class="main-btn" />
</form>
                    </div> <!-- about image -->
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
        <div class="about-shape-1">
            <img src="assets/images/about-shape-1.svg" alt="shape">
        </div>
    </section>
    
    <!--====== ABOUT PART ENDS ======-->

    <!--====== ABOUT PART START ======-->
    
    <section id="uebersetzung" class="about-area pt-70">
        <div class="about-shape-2">
            <img src="assets/images/about-shape-2.svg" alt="shape">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="about-content mt-50 wow fadeInLeftBig" data-wow-duration="1s" data-wow-delay="0.5s">
                        <div class="section-title">
                            <div class="line"></div>
                            <h3 class="title">Übersetzung:</h3>
                        </div> <!-- section title -->
                       <img src="assets/images/translate.jpg" alt="about">
                    </div> <!-- about content -->
                </div>
                <div class="col-lg-6">
                    <div class="about-image text-center mt-50 wow fadeInRightBig" data-wow-duration="1s" data-wow-delay="0.5s">
                            <div class="line"></div>
                        <p>
		<?php if($txtTo) echo '<a class="main-btn" href="'.$target_file_to.'" download="Uebersetzung.txt">Text-Übersetzung zum Download</a>';/*nl2br($txtTo);*/ ?>
	</p>
                    </div> <!-- about image -->
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </section>


    <!--====== ABOUT PART START ======-->
    
    <section id="sprache" class="about-area pt-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="about-content mt-50 wow fadeInLeftBig" data-wow-duration="1s" data-wow-delay="0.5s">
                        <div class="section-title">
                            <div class="line"></div>
                            <h3 class="title">Sprachkonvertierung:</h3>
                        </div> <!-- section title -->
                        <img src="assets/images/modal.jpg" alt="about">
                      
                    </div> <!-- about content -->
                </div>
                <div class="col-lg-6">
                    <div class="about-image text-center mt-50 wow fadeInRightBig" data-wow-duration="1s" data-wow-delay="0.5s">
                            <div class="line"></div>
<?php if (isset($_POST['txt'])) { 

// $urlFrom = 'http://api.voicerss.org/?key=252f4f361f0740cfab5b5ffd9d1df649&hl='.$speech_arr[$from].'&src='.$txt;
// $urlTo = 'http://api.voicerss.org/?key=252f4f361f0740cfab5b5ffd9d1df649&hl='.$speech_arr[$to].'&src='.$txtTo;
$urlFrom = post_request('https://www.text-konvertierung.de/audio_download.php', array("hl"=>$speech_arr[$from], "src"=>strip_tags($txt)));
$urlTo = post_request('https://www.text-konvertierung.de/audio_download.php', array("hl"=>$speech_arr[$to], "src"=>strip_tags($txtTo)));
//$urlTo = 'audio_download.php?hl='.$speech_arr[$to].'&src='.strip_tags($txtTo);
//$urlFrom = 'https://text-konvertierung.bilke-projects.com/audio_download.php?hl='.$from.(isset($txtfile) ? '&srcfile=https://text-konvertierung.de/'.$txtfile : '&src='.strip_tags($txt));
//$urlTo = 'https://text-konvertierung.bilke-projects.com/audio_download.php?hl='.$to.(isset($txtTofile) ? '&srcfile=https://text-konvertierung.de/'.$txtTofile : '&src='.strip_tags($txtTo));

?>
Audio auf <?php echo $from ?>:<br>
<audio controls>
  <source src="<?php echo $urlFrom; ?>" type="audio/wav">
Your browser does not support the audio element.
</audio><br><br></p>
<p>
Audio auf <?php echo $to ?>:<br>
<audio controls>
  <source src="<?php echo $urlTo; ?>" type="audio/wav">
Your browser does not support the audio element.
</audio>
<?php 


?>
<br><br>
<a href="<?php echo $urlFrom ?>" class="main-btn" download="Audio_<?php echo $from ?>.wav">Download Audio auf <?php echo $from ?></a><br><br>
<a href="<?php echo $urlTo ?>" class="main-btn" download="Audio_<?php echo $to ?>.wav">Download Audio auf <?php echo $to ?></a><br><br>

<?php
} ?>

                    </div> <!-- about image -->
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
        <div class="about-shape-1">
            <img src="assets/images/about-shape-1.svg" alt="shape">
        </div>
    </section>
    
    <!--====== ABOUT PART ENDS ======-->

    
    <!--====== ABOUT PART ENDS ======-->
    
    <!--====== VIDEO COUNTER PART START ======-->
    
    <section id="impressum" class="video-counter pt-70">
        <div class="container">
                        <div class="section-title">
                            <div class="line"></div>
                            <h3 class="title">Impressum:</h3><br><br>
                        </div> <!-- section title -->
<h2>Anbieter:</h2>
<p> </p>
<p><span>Bilke Web- und Softwareentwicklung</span></p>
<p>Hanauer Landstraße 291 B, 60314 Frankfurt am Main, Deutschland</p>

<div>
 <p>Umsatzsteuer-Identifikationsnummer: <span>DE350967159</span></p>
</div>
<p> </p>
<p>Telefonnummer: <span>+49 174 849 3008</span></p>

<p>E-Mail-Adresse: <span>info@dominic-bilke.de</span></p>
<p> </p>

<div>
 <h2>EU-Streitschlichtung (OS-Plattform):</h2>
 <p> </p>
 <p>Die EU-Kommission hat eine Europäische OS-Plattform zur außergerichtlichen Online-Beilegung von Streitigkeiten zwischen Verbrauchern und Unternehmern eingerichtet. Diese ist erreichbar unter:</p>
 <p>https://ec.europa.eu/consumers/odr </p>
 <p><span>Der Anbieter nimmt an einem solchen Schlichtungsverfahren teil.</span>Die E-Mail-Adresse des Anbieters lautet <span>freelancer@dominic-bilke.de</span>.</p>
 <p> </p>
</div>


<div>
 <h2>Berufsrecht:</h2>
 <p> </p>
 <p>gesetzliche Berufsbezeichnung: <span>Freiberuflicher Ingenieur</span></p>
 <p>Staat, in dem diese verliehen wurde: <span>Deutschland, Sachsen</span></p>
 <p>berufsrechtliche Regelungen: <span>Sächsisches Ingenieurgesetz: https://www.revosax.sachsen.de/vorschrift/17148-SaechsIngG</span></p>
 <p> </p>
</div>

        </div> <!-- container -->
    </section>
    
    <!--====== VIDEO COUNTER PART ENDS ======-->
    
    <!--====== TEAM PART START ======-->
    
    <section id="datenschutz" class="team-area pt-120">
        <div class="container">
                        <div class="section-title">
                            <div class="line"></div>
                            <h3 class="title">Datenschutzerklärung:</h3><br><br>
                        </div> <!-- section title -->
<h1>Allgemein</h1>
<p>Als Betreiber dieser Webseite und als Unternehmen kommen wir mit Ihren personenbezogenen Daten in Kontakt. Gemeint sind alle Daten, die etwas über Sie aussagen und mit denen Sie identifiziert werden können. In dieser Datenschutzerklärung möchten wir Ihnen erläutern, in welcher Weise, zu welchem Zweck und auf welcher rechtlichen Grundlage wir Ihre Daten verarbeiten.</p>
<p>Für die Datenverarbeitung auf dieser Webseite und in unserem Unternehmen ist verantwortlich:</p>
<p><span>Bilke Web- und Softwareentwicklung</span></p>
<p>Hanauer Landstraße 291 B, 60314 Frankfurt am Main, Deutschland</p>

<p>Telefon: <span>+49 174 849 3008</span></p>
<p>E-Mail: <span>info@dominic-bilke.de</span></p>
<h2>Allgemeine Hinweise</h2>
<div>
 <h3>SSL- bzw. TLS-Verschlüsselung</h3>
 <p>Wenn Sie Ihre Daten auf Webseiten eingeben, Online-Bestellungen aufgeben oder E-Mails über das Internet verschicken, müssen Sie immer damit rechnen, dass unberechtigte Dritte auf Ihre Daten zugreifen. Einen vollständigen Schutz vor solchen Zugriffen gibt es nicht. Wir setzen jedoch alles daran, Ihre Daten bestmöglich zu schützen und die Sicherheitslücken zu schließen, soweit es uns möglich ist.</p>
 <p>Ein wichtiger Schutzmechanismus ist die SSL- bzw. TLS-Verschlüsselung unserer Webseite, die dafür sorgt, dass Daten, die Sie an uns übermitteln, nicht von Dritten mitgelesen werden können. Sie erkennen die Verschlüsselung an dem Schloss-Icon vor der eingegebenen Internetadresse in Ihrem Browser und daran, dass unsere Internetadresse mit https:// beginnt und nicht mit http://.</p>
</div>
<div>
 <h3>Verschlüsselter Zahlungsverkehr</h3>
 <p>Besonders schutzbedürftig sind Zahlungsdaten, wie z. B. die Konto- oder Kreditkartennummer. Deshalb erfolgt auch der Zahlungsverkehr mit den gängigen Zahlungsmitteln bei uns ausschließlich über eine verschlüsselte SSL- bzw. TLS-Verbindung.</p>
</div>
<h3>Wie lange speichern wir Ihre Daten?</h3>
<p>An manchen Stellen in dieser Datenschutzerklärung informieren wir Sie darüber, wie lange wir oder die Unternehmen, die Ihre Daten in unserem Auftrag verarbeiten, Ihre Daten speichern. Fehlt eine solche Angabe, speichern wir Ihre Daten, bis der Zweck der Datenverarbeitung entfällt, Sie der Datenverarbeitung widersprechen oder Sie Ihre Einwilligung in die Datenverarbeitung widerrufen.</p>
<p>Im Falle eines Widerspruchs oder Widerrufs dürfen wir Ihre Daten allerdings weiterverarbeiten, wenn mindestens eine der folgenden Voraussetzungen vorliegt:</p>
<ul>
 <li><p>Wir haben zwingende schutzwürdige Gründe für die Fortsetzung der Datenverarbeitung, die Ihre Interessen, Rechte und Freiheiten überwiegen (nur bei Widerspruch gegen die Datenverarbeitung; wenn sich der Widerspruch gegen Direktwerbung richtet, können wir keine schutzwürdigen Gründe vorbringen).</p></li>
 <li><p>Die Datenverarbeitung ist erforderlich, um Rechtsansprüche geltend zu machen, auszuüben oder zu verteidigen (gilt nicht, wenn sich Ihr Widerspruch gegen Direktwerbung richtet).</p></li>
 <li><p>Wir sind gesetzlich verpflichtet, Ihre Daten aufzubewahren.</p></li>
</ul>
<p>In diesem Fall löschen wir Ihre Daten, sobald die Voraussetzung(en) entfällt bzw. entfallen.</p>
<div>
 <h3>Datenweitergabe in die USA</h3>
 <p>Wir nutzen auf unserer Webseite auch Tools von Unternehmen, die Ihre Daten in die USA übermitteln und dort speichern und ggf. weiterverarbeiten. Für Sie ist das vor allem deshalb von Bedeutung, weil Ihre Daten in den USA nicht den gleichen Schutz genießen wie innerhalb der EU, wo die Datenschutzgrundverordnung (DSGVO) gilt. So sind US-Unternehmen z. B. dazu verpflichtet, personenbezogene Daten an Sicherheitsbehörden herauszugeben, ohne dass Sie als betroffene Person hiergegen gerichtlich vorgehen können. Es kann daher sein, dass US-Behörden (z. B. Geheimdienste) Ihre Daten auf US-amerikanischen Servern zu Überwachungszwecken verarbeiten, auswerten und dauerhaft speichern. Wir haben auf diese Verarbeitungstätigkeiten keinen Einfluss.</p>
</div>

<h2>Ihre Rechte</h2>
<h3>Widerspruch gegen die Datenverarbeitung</h3>
<p>WENN SIE IN DIESER DATENSCHUTZERKLÄRUNG LESEN, DASS WIR BERECHTIGTE INTERESSEN FÜR DIE VERARBEITUNG IHRER DATEN HABEN UND DIESE DESHALB AUF ART. 6 ABS. 1 SATZ 1 LIT. F) DSGVO STÜTZEN, HABEN SIE NACH ART. 21 DSGVO DAS RECHT, WIDERSPRUCH DAGEGEN EINZULEGEN. DAS GILT AUCH FÜR EIN PROFILING, DAS AUF GRUNDLAGE DER GENANNTEN VORSCHRIFT ERFOLGT. VORAUSSETZUNG IST, DASS SIE GRÜNDE FÜR DEN WIDERSPRUCH ANFÜHREN, DIE SICH AUS IHRER BESONDEREN SITUATION ERGEBEN. EINE BEGRÜNDUNG IST NICHT ERFORDERLICH, WENN SICH DER WIDERSPRUCH GEGEN DIE NUTZUNG IHRER DATEN ZUR DIREKTWERBUNG RICHTET.</p>
<p>FOLGE DES WIDERSPRUCHS IST, DASS WIR IHRE DATEN NICHT MEHR VERARBEITEN DÜRFEN. DAS GILT NUR DANN NICHT, WENN EINE DER FOLGENDEN VORAUSSETZUNGEN VORLIEGT:</p>
<ul>
 <li><p>WIR KÖNNEN ZWINGENDE SCHUTZWÜRDIGE GRÜNDE FÜR DIE VERARBEITUNG NACHWEISEN, DIE IHRE INTERESSEN, RECHTE UND FREIHEITEN ÜBERWIEGEN.</p></li>
</ul>
<ul>
 <li><p>DIE VERARBEITUNG DIENT DER GELTENDMACHUNG, AUSÜBUNG ODER VERTEIDIGUNG VON RECHTSANSPRÜCHEN.</p></li>
</ul>
<p>DIE AUSNAHMEN GELTEN NICHT, WENN SICH IHR WIDERSPRUCH GEGEN DIREKTWERBUNG RICHTET ODER GEGEN EIN PROFILING, DAS MIT DIESER IN VERBINDUNG STEHT.</p>
<h3>Weitere Rechte</h3>
<h4>Widerruf Ihrer Einwilligung zur Datenverarbeitung</h4>
<p>Viele Datenverarbeitungsvorgänge erfolgen auf der Grundlage Ihrer Einwilligung. Diese erteilen Sie z. B. dadurch, dass Sie bei Online-Formularen ein entsprechendes Häkchen setzen, bevor Sie das Formular versenden, oder indem Sie bestimmte Cookies zulassen, wenn Sie unsere Webseite besuchen. Sie können Ihre Einwilligung jederzeit ohne Angabe von Gründen widerrufen (Art. 7 Abs. 3 DSGVO). Ab dem Zeitpunkt des Widerrufs dürfen wir Ihre Daten dann nicht mehr verarbeiten. Einzige Ausnahme: Wir sind gesetzlich verpflichtet, die Daten eine bestimmte Zeit lang aufzubewahren. Solche Aufbewahrungsfristen gibt es insbesondere im Steuer- und Handelsrecht.</p>
<h4>Recht zur Beschwerde bei der zuständigen Aufsichtsbehörde</h4>
<p>Wenn Sie der Auffassung sind, dass wir gegen die Datenschutzgrundverordnung (DSGVO) verstoßen, haben Sie nach Art. 77 DSGVO das Recht, sich bei einer Aufsichtsbehörde zu beschweren. Sie können sich an eine Aufsichtsbehörde in dem Mitgliedstaat Ihres Aufenthaltsorts, Ihres Arbeitsplatzes oder des Ortes wenden, an dem der mutmaßliche Verstoß stattgefunden hat. Das Beschwerderecht besteht neben verwaltungsrechtlichen oder gerichtlichen Rechtsbehelfen.</p>
<h4>Recht auf Datenübertragbarkeit</h4>
<p>Daten, die wir auf Grundlage Ihrer Einwilligung oder in Erfüllung eines Vertrages automatisiert verarbeiten, müssen wir Ihnen oder einem Dritten in einem gängigen maschinenlesbaren Format aushändigen, wenn Sie das verlangen. An einen anderen Verantwortlichen können wir die Daten nur übertragen, soweit dies technisch möglich ist.</p>
<h4>Recht auf Datenauskunft, -löschung und -berichtigung</h4>
<p>Sie haben nach Art. 15 DSGVO das Recht, unentgeltlich Auskunft darüber zu erhalten, welche personenbezogenen Daten wir von Ihnen gespeichert haben, wo die Daten herkommen, an wen wir die Daten übermitteln und zu welchem Zweck sie gespeichert werden. Sollten die Daten falsch sein, haben Sie ein Recht auf Berichtigung (Art. 16 DSGVO), unter den Voraussetzungen des Art. 17 DSGVO dürfen Sie verlangen, dass wir die Daten löschen.</p>
<h4>Recht auf Einschränkung der Verarbeitung</h4>
<p>In bestimmten Situationen können Sie nach Art. 18 DSGVO von uns verlangen, dass wir die Verarbeitung Ihrer Daten einschränken. Die Daten dürfen dann – von der Speicherung abgesehen – nur noch wie folgt verarbeitet werden:</p>
<ul>
 <li><p>mit Ihrer Einwilligung</p></li>
</ul>
<ul>
 <li><p>zur Geltendmachung, Ausübung oder Verteidigung von Rechtsansprüchen</p></li>
</ul>
<ul>
 <li><p>zum Schutz der Rechte einer anderen natürlichen oder juristischen Person</p></li>
</ul>
<ul>
 <li><p>aus Gründen eines wichtigen öffentlichen Interesses der Europäischen Union oder eines Mitgliedstaates</p></li>
</ul>
<p>Das Recht auf Einschränkung der Verarbeitung besteht in den folgenden Situationen:</p>
<ul>
 <li><p>Sie haben die Richtigkeit Ihrer bei uns gespeicherten personenbezogenen Daten bestritten und wir benötigen Zeit, um dies zu überprüfen. Hier besteht das Recht für die Dauer der Prüfung.</p></li>
</ul>
<ul>
 <li><p>Die Verarbeitung Ihrer personenbezogenen Daten erfolgt zu Unrecht oder war in der Vergangenheit unrechtmäßig. Hier besteht das Recht alternativ zur Löschung der Daten.</p></li>
</ul>
<ul>
 <li><p>Wir benötigen Ihre personenbezogenen Daten nicht mehr, Sie benötigen sie jedoch zur Ausübung, Verteidigung oder Geltendmachung von Rechtsansprüchen. Hier besteht das Recht alternativ zur Löschung der Daten.</p></li>
</ul>
<ul>
 <li><p>Sie haben Widerspruch nach Art. 21 Abs. 1 DSGVO eingelegt und nun müssen Ihre und unsere Interessen gegeneinander abgewogen werden. Hier besteht das Recht, solange das Ergebnis der Abwägung noch nicht feststeht.</p></li>
</ul>
<div>
 <h1>Hosting und Content Delivery Networks (CDN)</h1>
</div>
<div>
 <h2>Externes Hosting</h2>
 <p>Unsere Website liegt auf einem Server des folgenden Anbieters für Internetdienste (Hosters):</p>
 
 <div>
  <p>ALL-INKL.COM - Neue Medien Münnich<br />Inh. René Münnich<br />Hauptstraße 68<br />02742 Friedersdorf</p>
 </div>
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 <h4></h4>
 <p></p>
 <h4>Wie verarbeiten wir Ihre Daten?</h4>
 <p>Der Hoster speichert alle Daten unserer Webseite. Dazu gehören auch alle personenbezogenen Daten, die automatisch oder durch Ihre Eingabe erfasst werden. Das können insbesondere sein: Ihre IP-Adresse, aufgerufene Seiten, Namen, Kontaktdaten und -anfragen sowie Meta- und Kommunikationsdaten. <span>Bei der Datenverarbeitung hält sich unser Hoster an unsere Weisungen und verarbeitet die Daten stets nur insoweit, als dies erforderlich ist, um die Leistungspflicht uns gegenüber zu erfüllen.</span></p>
 <h4>Auf welcher Rechtsgrundlage verarbeiten wir Ihre Daten?</h4>
 <p>Da wir über unsere Webseite potenzielle Kunden ansprechen und Kontakte zu bestehenden Kunden pflegen, dient die Datenverarbeitung durch unseren Hoster der Vertragsanbahnung und -erfüllung und beruht daher auf Art. 6 Abs. 1 lit. b) DSGVO. Darüber hinaus ist es unser berechtigtes Interesse als Unternehmen, ein professionelles Internetangebot bereitzustellen, das die nötigen Anforderungen an Sicherheit, Geschwindigkeit und Effizienz erfüllt. Insoweit verarbeiten wir Ihre Daten außerdem auf der Grundlage von Art. 6 Abs. 1 lit. f) DSGVO.</p>
</div>



<h1>Datenerfassung auf dieser Website</h1>
<h2>Verwendung von Cookies</h2>
<p>Unsere Webseite platziert Cookies auf Ihrem Gerät. Dabei handelt es sich um kleine Textdateien, mit denen unterschiedliche Zwecke verfolgt werden. Manche Cookies sind technisch notwendig, damit die Webseite überhaupt funktioniert (notwendige Cookies). Andere werden benötigt, um bestimmte Aktionen oder Funktionen auf der Site ausführen zu können (funktionale Cookies). So wäre es beispielsweise ohne Cookies nicht möglich, die Vorzüge eines Warenkorbs in einem Online-Shop zu nutzen. Wieder andere Cookies dienen dazu, das Nutzerverhalten zu analysieren oder Werbemaßnahmen zu optimieren. Wenn wir Dienstleistungen Dritter auf unserer Website nutzen, z. B. zur Abwicklung von Zahlungsvorgängen, können auch diese Unternehmen Cookies auf Ihrem Gerät hinterlassen, wenn Sie die Website aufrufen (sog. Third-Party-Cookies).</p>
<h4>Wie verarbeiten wir Ihre Daten?</h4>
<p>Session-Cookies werden nur für die Dauer einer Sitzung auf Ihrem Gerät gespeichert. Sobald Sie den Browser schließen, verschwinden sie also von selbst. Permanent-Cookies bleiben dagegen auf Ihrem Gerät, wenn Sie sie nicht selbst löschen. Das kann z.B. dazu führen, dass Ihr Nutzerverhalten dauerhaft analysiert wird. Sie können über die Einstellungen in Ihrem Browser Einfluss darauf nehmen, wie er mit Cookies umgeht:</p>
<ul>
 <li><p>Wollen Sie informiert werden, wenn Cookies gesetzt werden?</p></li>
</ul>
<ul>
 <li><p>Wollen Sie Cookies generell oder für bestimmte Fälle ausschließen?</p></li>
</ul>
<ul>
 <li><p>Wollen Sie, dass Cookies beim Schließen des Browsers automatisch gelöscht werden?</p></li>
</ul>
<p>Wenn Sie Cookies deaktivieren bzw. nicht zulassen, kann die Funktionalität der Webseite eingeschränkt sein.</p>
<p>Sofern wir Cookies von anderen Unternehmen oder zu Analysezwecken einsetzen, informieren wir Sie hierüber im Rahmen dieser Datenschutzerklärung. Auch fragen wir diesbezüglich Ihre Einwilligung ab, wenn Sie unsere Webseite aufrufen.</p>
<h4>Auf welcher Rechtsgrundlage verarbeiten wir Ihre Daten?</h4>
<p>Wir haben ein berechtigtes Interesse daran, dass unsere Online-Angebote ohne technische Probleme von den Besuchern genutzt werden können und ihnen alle gewünschten Funktionen zur Verfügung stehen. Die Speicherung notwendiger und funktionaler Cookies auf Ihrem Gerät erfolgt daher auf der Grundlage von Art. 6 Abs. 1 lit. f) DSGVO. Alle anderen Cookies setzen wir auf der Grundlage von Art. 6 Abs. 1 lit. a) DSGVO ein, sofern Sie uns eine entsprechende Einwilligung erteilen. Diese können Sie jederzeit mit Wirkung für die Zukunft widerrufen. Haben Sie bei der Abfrage der Einwilligung in die Platzierung notwendiger und funktionaler Cookies eingewilligt, erfolgt auch die Speicherung dieser Cookies ausschließlich auf der Grundlage Ihrer Einwilligung.</p>






<h2>Server-Log-Dateien</h2>
<p>Server-Log-Dateien protokollieren alle Anfragen und Zugriffe auf unsere Webseite und halten Fehlermeldungen fest. Sie umfassen auch personenbezogene Daten, insbesondere Ihre IP-Adresse. Diese wird allerdings schon nach kurzer Zeit vom Provider anonymisiert, sodass wir die Daten nicht Ihrer Person zuordnen können. Die Daten werden automatisch von Ihrem Browser an unseren Provider übermittelt.</p>
<h4>Wie verarbeiten wir Ihre Daten?</h4>
<p>Unser Provider speichert die Server-Log-Dateien, um die Aktivitäten auf unserer Webseite nachvollziehen zu können und Fehler ausfindig zu machen. Die Dateien enthalten die folgenden Daten:</p>
<ul>
 <li><p>Browsertyp und -version</p></li>
</ul>
<ul>
 <li><p>verwendetes Betriebssystem</p></li>
</ul>
<ul>
 <li><p>Referrer-URL</p></li>
</ul>
<ul>
 <li><p>Hostname des zugreifenden Rechners</p></li>
</ul>
<ul>
 <li><p>Uhrzeit der Serveranfrage</p></li>
</ul>
<ul>
 <li><p>IP-Adresse (ggf. anonymisiert)</p></li>
</ul>
<p>Wir führen diese Daten nicht mit anderen Daten zusammen, sondern nutzen sie lediglich für die statistische Auswertung und zur Verbesserung unserer Website.</p>
<h4>Auf welcher Rechtsgrundlage verarbeiten wir Ihre Daten?</h4>
<p>Wir haben ein berechtigtes Interesse daran, dass unsere Webseite fehlerfrei läuft. Auch ist es unser berechtigtes Interesse, einen anonymisierten Überblick über die Zugriffe auf unsere Webseite zu erhalten. Die Datenverarbeitung ist deshalb gemäß Art. 6 Abs. 1 lit. f) DSGVO rechtmäßig.</p>
<div>
 <h2>Kontaktformular</h2>
 <p>Sie können uns über das Kontaktformular auf dieser Webseite eine Nachricht zukommen lassen.</p>
 <h4>Wie verarbeiten wir Ihre Daten?</h4>
 <p>Wir speichern Ihre Nachricht und die Angaben aus dem Formular, um Ihre Anfrage inklusive Anschlussfragen bearbeiten zu können. Das betrifft auch die angegebenen Kontaktdaten. Ohne Ihre Einwilligung geben wir die Daten nicht an andere Personen weiter.</p>
 <h4>Wie lange speichern wir Ihre Daten?</h4>
 <p>Wir löschen Ihre Daten, sobald einer der folgenden Punkte eintritt:</p>
 <ul>
  <li><p>Ihre Anfrage wurde abschließend bearbeitet.</p></li>
 </ul>
 <ul>
  <li><p>Sie fordern uns zur Löschung der Daten auf.</p></li>
 </ul>
 <ul>
  <li><p>Sie widerrufen Ihre Einwilligung zur Speicherung.</p></li>
 </ul>
 <p>Das gilt nur dann nicht, wenn wir gesetzlich dazu verpflichtet sind, die Daten aufzubewahren.</p>
 <h4>Auf welcher Rechtsgrundlage verarbeiten wir Ihre Daten?</h4>
 <p>Sofern Ihre Anfrage mit unserer vertraglichen Beziehung in Zusammenhang steht oder der Durchführung vorvertraglicher Maßnahmen dient, verarbeiten wir Ihre Daten auf der Grundlage von Art. 6 Abs. 1 lit. b) DSGVO. In allen anderen Fällen ist es unser berechtigtes Interesse, an uns gerichtete Anfragen effektiv zu bearbeiten. Rechtsgrundlage der Datenverarbeitung ist somit Art. 6 Abs. 1 lit. f) DSGVO. Haben Sie in die Speicherung Ihrer Daten eingewilligt, ist Art. 6 Abs. 1 lit. a) DSGVO die Rechtsgrundlage. In diesem Fall können Sie Ihre Einwilligung jederzeit mit Wirkung für die Zukunft widerrufen.</p>
</div>







































































<div>
 <h1>eCommerce <span>und Zahlungsanbieter</span></h1>
</div>
<div>
 <h2>Kunden- und Vertragsdaten</h2>
 <h4>Wie verarbeiten wir Ihre Daten?</h4>
 <p>Wenn wir mit Ihnen einen Vertrag schließen, benötigen wir bestimmte personenbezogene Daten von Ihnen. Wir erheben, verarbeiten und nutzen diese Daten nur, soweit sie erforderlich sind, um unser Rechtsverhältnis zu begründen, es inhaltlich auszugestalten oder zu ändern. Können Sie unsere Dienste nur über unsere Webseite in Anspruch nehmen oder werden die Dienste über die Webseite abgerechnet, erfassen wir auch Nutzungsdaten, sofern diese erforderlich sind, um Ihnen die Inanspruchnahme unseres Angebotes zu ermöglichen oder die in Anspruch genommene Leistung abzurechnen.</p>
 <h4>Wie lange speichern wir Ihre Daten?</h4>
 <p>Wir speichern Ihre Daten, bis unser Rechtsverhältnis endet, es sei denn, wir sind gesetzlich dazu verpflichtet, die Daten länger aufzubewahren.</p>
 <h4>Auf welcher Rechtsgrundlage verarbeiten wir Ihre Daten?</h4>
 <p>Wir speichern Ihre Daten, um den Vertrag mit Ihnen zu erfüllen oder vorvertragliche Maßnahmen durchzuführen. Grundlage der Datenverarbeitung ist damit Art. 6 Abs. 1 lit. b) DSGVO.</p>
</div>




<div>
 <h2>Zahlungsdienste</h2>
 <p>Damit Sie Ihre Käufe auf unserer Webseite bequem bezahlen können, nutzen wir den Service von Zahlungsdiensten, also externen Unternehmen, welche die Zahlungen für uns abwickeln. Welche das konkret sind, können Sie der Liste am Ende dieses Abschnitts entnehmen.</p>
 <h4>Wie verarbeiten wir Ihre Daten?</h4>
 <p>Für den Bezahlvorgang müssen Sie bestimmte personenbezogene Daten angeben, z. B. Ihren Namen, Ihre Kontoverbindung oder Kreditkartennummer. Diese Daten geben wir an den jeweiligen Zahlungsdienst weiter. Für die Transaktion selbst gelten die jeweiligen Vertrags- und Datenschutzbestimmungen der jeweiligen Dienste.</p>
 <h4>Auf welcher Rechtsgrundlage verarbeiten wir Ihre Daten?</h4>
 <p>Wir geben Ihre Daten weiter, um den Vertrag zu erfüllen, den wir mit Ihnen geschlossen haben. Grundlage der Datenverarbeitung ist somit Art. 6 Abs. 1 lit. b) DSGVO. Zudem haben wir ein berechtigtes Interesse daran, Käufe möglichst schnell, komfortabel und sicher abzuwickeln. Rechtsgrundlage ist insofern auch Art. 6 Abs. 1 lit. f) DSGVO. Haben Sie in die Weitergabe Ihrer Daten eingewilligt, beruht die Datenverarbeitung auf Art. 6 Abs. 1 lit. a) DSGVO. Sie können Ihre Einwilligung jederzeit mit Wirkung für die Zukunft widerrufen.</p>
 <h4>Welche Zahlungsdienste nutzen wir?</h4>
</div>
<div>
 <h3>PayPal</h3>
 <h4>Was ist PayPal?</h4>
 <p>Online-Bezahldienst</p>
 <h4>Wer verarbeitet Ihre Daten?</h4>
 <p>PayPal (Europe) S.à.r.l. et Cie, S.C.A., 22-24 Boulevard Royal, 2449 Luxemburg, Luxemburg</p>
 <h4>Wo finden Sie weitere Informationen über den Datenschutz bei PayPal?</h4>
 <p>https://www.paypal.com/de/webapps/mpp/ua/privacy-full</p>
 <h4>Auf welcher Grundlage übertragen wir Ihre Daten in die USA?</h4>
 <p>PayPal hält sich an die Standardvertragsklauseln der Europäischen Kommission (vgl. https://www.paypal.com/de/webapps/mpp/ua/pocpsa-full)</p>
</div>

































        </div> <!-- container -->
    </section>
    
    <!--====== TEAM PART ENDS ======-->
    
    
    <!--====== FOOTER PART START ======-->
    
    <footer id="kontakt" class="footer-area pt-120">
        <div class="container">
          <div class="footer-widget pb-100">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-8">
                        <div class="footer-about mt-50 wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s">
                            <a class="logo" href="#">
                                <!--img src="assets/images/modal.jpg" alt="logo"-->
                            </a>
                            <p class="text">Mit diesem Tool können Sie Ihre Texte als Textübersetzung oder Text zu Sprache konvertieren.</p>
                            <!--ul class="social">
                                <li><a href="#"><i class="lni-facebook-filled"></i></a></li>
                                <li><a href="#"><i class="lni-twitter-filled"></i></a></li>
                                <li><a href="#"><i class="lni-instagram-filled"></i></a></li>
                                <li><a href="#"><i class="lni-linkedin-original"></i></a></li>
                            </ul-->
                        </div> <!-- footer about -->
                    </div>
                    <div class="col-lg-3 col-md-5 col-sm-5">
                        <div class="footer-contact mt-50 wow fadeIn" data-wow-duration="1s" data-wow-delay="0.8s">
                            <div class="footer-title">
                                <h4 class="title">Kontakt</h4>
                            </div>
                            <ul class="contact">
                                <li>Bilke Web- und Softwareentwicklung</li>
                                <li>+49 174 849 3008</li>
                                <li>info@dominic-bilke.de</li>
                                <li>www.web-software-entwicklung.de</li>
                            </ul>
                        </div> <!-- footer contact -->
                    </div>
                </div> <!-- row -->
            </div> <!-- footer widget -->
            <div class="footer-copyright">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="copyright d-sm-flex justify-content-between">
                            <div class="copyright-content">
                                <p class="text">Designed and Developed by <a href="https://uideck.com" rel="nofollow">UIdeck</a></p>
                            </div> <!-- copyright content -->
                        </div> <!-- copyright -->
                    </div>
                </div> <!-- row -->
            </div> <!-- footer copyright -->
        </div> <!-- container -->
        <div id="particles-2"></div>
    </footer>
    
    <!--====== FOOTER PART ENDS ======-->
    
    <!--====== BACK TOP TOP PART START ======-->

    <a href="#" class="back-to-top"><i class="lni-chevron-up"></i></a>

    <!--====== BACK TOP TOP PART ENDS ======-->   
    
    <!--====== PART START ======-->
    
<!--
    <section class="">
        <div class="container">
            <div class="row">
                <div class="col-lg-"></div>
            </div>
        </div>
    </section>
-->
    
    <!--====== PART ENDS ======-->




    <!--====== Jquery js ======-->
    <script src="assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="assets/js/vendor/modernizr-3.7.1.min.js"></script>
    
    <!--====== Bootstrap js ======-->
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    
    <!--====== Plugins js ======-->
    <script src="assets/js/plugins.js"></script>
    
    <!--====== Slick js ======-->
    <script src="assets/js/slick.min.js"></script>
    
    <!--====== Ajax Contact js ======-->
    <script src="assets/js/ajax-contact.js"></script>
    
    <!--====== Counter Up js ======-->
    <script src="assets/js/waypoints.min.js"></script>
    <script src="assets/js/jquery.counterup.min.js"></script>
    
    <!--====== Magnific Popup js ======-->
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    
    <!--====== Scrolling Nav js ======-->
    <script src="assets/js/jquery.easing.min.js"></script>
    <script src="assets/js/scrolling-nav.js"></script>
    
    <!--====== wow js ======-->
    <script src="assets/js/wow.min.js"></script>
    
    <!--====== Particles js ======-->
    <script src="assets/js/particles.min.js"></script>
    
    <!--====== Main js ======-->
    <script src="assets/js/main.js"></script>
    
</body>

</html>
