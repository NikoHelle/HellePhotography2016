<?php
include_once "lib/session.php";
include_once "lib/config.php";
$url = $config->meta->siteUrl;
$title = $config->meta->title;
if(isset($_REQUEST["path"])){
    $path = $_REQUEST["path"];

}
else{
    $path = "etusivu";
}

$set = $config->sets[$path];
$url.= "/".$set->path;
$title.= " - ".$set->title;
$description.= $set->metaDescription;
$image = $config->meta->siteUrl.$set->metaImage;
?>
<!doctype html>
<html class="no-js" lang="fi">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
      <meta property="og:url" content="<?=$url;?>" />
      <meta property="og:type" content="article" />
      <meta property="og:title" content="<?=$title;?>" />
      <meta property="og:site_name" content="<?=$config->meta->siteName;?>" />
      <meta property="og:description" content="<?=$description;?>" />
      <meta property="og:image" content="<?=$image;?>" />
      <meta property="og:locale" content="fi_FI" />
      <title>Helle Photography</title>
    <link rel="stylesheet" href="stylesheets/app.css" />
      <script src="//use.typekit.net/xpy7ian.js"></script>
      <script>try{Typekit.load();}catch(e){}</script>
      <script>
          var hpx = "<?=$_SESSION["v2"]?>";

      </script>
      <!-- Google Analytics -->
      <script>
          window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
          ga('create', 'UA-73674720-1', 'auto');
          ga('send', 'pageview');
      </script>
      <script async src='//www.google-analytics.com/analytics.js'></script>
      <!-- End Google Analytics -->
  </head>
  <body class="page-<?=$set->id;?>">
   <header id="header">
       <nav class="main">
           <a href="maailma">Maailma</a>
           <a href="haat">Häät</a>
           <a href="potretit">Potretit</a>
           <a href="koirat">Koirat</a>
           <a href="lapset">Lapset</a>
       </nav>
       <nav class="contact">
           <a href="#prices">Hinnasto</a>
           <a href="#contact">Ota&nbsp;yhteyttä</a>
           <a href="http://facebook.com/hellephotography" target="_blank">Facebook</a>
           <a href="http://instagram.com/hellephotography" target="_blank">Instagram</a>
       </nav>
       <a class="home" href="/"></a>
   </header>
   <section class="info">
       <h1><?= $set->title;?><span><?= $set->subtitle;?></span></h1>
       <?= $set->description;?>
   </section>
   <?php
     if($path != "etusivu") {
         echo "<section class='images'>";
         echo file_get_contents("data/" . $set->id . ".html");
         echo "</section>";
     }
    echo file_get_contents("data/etusivu.html");
   ?>
   <footer>
       <div class="left" id="contact">
           <form action="sendForm.php" method="get" class="ok">
                <h3>Ota yhteyttä</h3>
                <label for="email">Sähköpostiosoite</label>
                <input type="email" id="email" name="email" value="" placeholder="...johon lähetän vastaukseni" />
                <label for="email">Viesti</label>
                <textarea name="message" placeholder="...jonka haluat kertoa"></textarea>
                <input type="hidden" name="v1" value="23oi4dslf"/>
                <input type="hidden" name="v2" value="2309lksdf"/>
                <input type="hidden" name="s1" value="sddfs"/>
                <input type="hidden" name="js1" value="--W--"/>
                <input type="hidden" name="ie1" value="sdfdxv!!!"/>
                <input type="hidden" name="ta1" value="22123d"/>
                <input type="hidden" name="c1" value="asda"/>
                <a href="/send/form" id="sendForm">Lähetä</a>
                <a href="#" class="overlay"></a>
               <p class="send-status status-invalid">Täytä kaikki kentät!</p>
               <p class="send-status status-sending">Lähetetään...</p>
               <p class="send-status status-sent">Viesti lähetetty!</p>
               <p class="send-status status-error">Viestin lähetys epäonnistui. Yritä uudelleen!</p>
           </form>

           <h3>Kuvaaja</h3>
           <div class="photographer">
               <p>Niko Helle</p>
               <a href="mailto:" class="parse-data" data-p1="#niko#" data-p2="#.helle#" data-p3="#@#" data-p4="#hellephotography#" data-p5="#.com#">hellephotography.com</a>
               <p class="parse-data" data-p1="#0#" data-p2="#50 " data-p3="#3606 #" data-p4="#274#" data-p5="#">+358XXXXXXXXX</p>
               <p>Studioni on Helsingissä, Kalasatamassa.<br/>Kuvaan toki myös pk-seudun ulkopuolella.</p>
           </div>
       </div>
       <div class="right" id="prices">
           <h3>Hinnasto</h3>
           <p>
               Studiokuvauksen hinta on <strong>129€</strong>, johon kuuluu puolitoista tuntia kuvausaikaa ja <strong>12</strong> finalisoitua kuvaa. Nämä kuvat saat itse valita studiossa otettujen joukosta, joita on tavallisesti <strong>150-300</strong> - riippuen mallin paikallaan pysymisestä. :)
               Kuvien finalisoinnissa säädetään värit ja valaistus sekä korjataan esimerkiksi kuolat, taustan epätasaisuudet ja muut asiat, jotka eivät kuulu kuvaan.
           </p>
           <p>Saat kuvat digitaalisina täydessä resoluutiossa toimitettuna sekä zippinä että galleriana, josta kuvat voi hakea milloin vain ja jakaakin.</p>
           <p>Kuvaan mielelläni myös studion ulkopuolella ja kalustoni on liikuteltavissa minne tahansa. Studio- ja miljöökuvauksissa on aina tyytyväisyystakuu.</p>
           <p>Hääkuvausten hinta vaihtelee kuvaustyyppien mukaan. Potrettien ottaminen on järjestelyiden ja jälkikäsittelyn kannalta aikaavievintä, joten ne ovat siksi kallein osuus. Koko päivän kuvaus (10h) on <strong>899€</strong> ja pelkkä kirkko + potretit ovat <strong>499€</strong>.</p>
           <p>Hinnat sisältävät arvonlisäveron 24%.</p>
           <p><strong>Ota yhteyttä ja varaa aika rakkaittesi ikuistamiseksi!</strong></p>
           <p>Terveisin</p>
           <a href="#header" class="logo"><img src="img/sprites/logo_text_bw.png" alt="Helle Photography"/></a>

       </div>
       <div class="divider"></div>
       <p class="copyright">Kaikki sivuston aineisto on tekijänoikeuden alaista ja sen julkaisu, osittainenkaan, ilman valokuvaajan lupaa on kielletty. Kaikki kuvat ja materiaalit &copy; Helle Photography.</p>

   </footer>
   <a href="#header" class="scroll-nav nav-up"><span>&nbsp;</span></a>
   <script data-main="js/init" src="js/vendor/require.min.js"></script>
  </body>
</html>
