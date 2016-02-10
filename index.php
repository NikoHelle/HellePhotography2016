<?php
include_once "lib/session.php";
include_once "lib/config.php";

if(isset($_REQUEST["path"])){
    $path = $_REQUEST["path"];

}
else{
    $path = "etusivu";
}
$set = $config->sets[$path];
?>

<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
          ga('create', 'UA-35779743-1', 'auto');
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
           <a href="#contact">Ota yhteyttä</a>
           <a href="http://facebook.com/hellephotography">Facebook</a>
           <a href="http://instagram.com">Instagram</a>
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
                <input type="email" id="email" name="email" value="" placeholder="Sähköposti" />
                <textarea name="message" placeholder="Viestisi"></textarea>
                <input type="hidden" name="v1" value="23oi4dslf"/>
                <input type="hidden" name="v2" value="2309lksdf"/>
                <input type="hidden" name="s1" value="sddfs"/>
                <input type="hidden" name="js1" value="--W--"/>
                <input type="hidden" name="ie1" value="sdfdxv!!!"/>
                <input type="hidden" name="ta1" value="22123d"/>
                <input type="hidden" name="c1" value="asda"/>
                <a href="#" id="sendForm">Lähetä</a>
                <a href="#" class="overlay"></a>
               <p class="send-status status-sending">Lähetetään...</p>
               <p class="send-status status-sent">Viesti lähetetty!</p>
               <p class="send-status status-error">Viestin lähetys epäonnistui. Yritä uudelleen!</p>
           </form>

           <h3>Kuvaaja</h3>
           <div class="photographer">
               <p>Niko Helle</p>
               <p class="parse-data" data-p1="-niko-" data-p2="-helle-" data-p3="-@-" data-p4="-photography-" data-p5="-.com-">x@hellephotography.com</p>
               <p class="parse-data" data-p1="-0-" data-p2="-50-" data-p3="-3606-" data-p4="-274-" data-p5="--">+3585050515252</p>
           </div>
       </div>
       <div class="right" id="prices">
           <h3>Hinnasto</h3>
           <p>
               Studiokuvauksen hinta on <strong>150€</strong>, johon kuuluu puolitoista tuntia kuvausaikaa ja <strong>12</strong> finalisoitua kuvaa. Nämä kuvat saat itse valita studiossa otettujen joukosta, joita on tavallisesti <strong>150-300</strong> - riippuen mallin paikallaan pysymisestä. :)
               Kuvien finalisoinnissa säädetään värit ja valaistus sekä korjataan esimerkiksi kuolat, taustan epätasaisuudet ja muut asiat, jotka eivät kuulu kuvaan. RESOLUUTIO, DIGI
           </p>
           <p>
               Kuvaan mielelläni myös studion ulkopuolella ja kalustoni on liikuteltavissa minne tahansa.
           </p>
           <p>
               Hääkuvausten hinta vaihtelee kuvaustyyppien mukaan. Portrettien ottaminen on järjestelyiden ja jälkikäsittelyn kannalta aikaavievintä, joten ne ovat siksi kallein osuus. Koko päivän kuvaus (10h) on <strong>900€</strong> ja pelkkä kirkko + portretit ovat <strong>500€</strong>.
           </p>
           <p> Hinnat sisältävät arvonlisäveron 24%.</p>
           <p><strong>Ota yhteyttä ja varaa aika rakkaittesi ikuistamiseksi!</strong></p>
           RAHAT TAKAs
           <h1>Copyright</h1>

       </div>
       <div class="divider"></div>
       <a href="#header" class="logo"><img src="img/sprites/logo_text_bw.png" alt="Helle Photography"/></a>

   </footer>
   <a href="#header" class="scroll-nav nav-up">&#8679;</a>
   <script data-main="js/init" src="js/vendor/require.min.js"></script>
  </body>
</html>
