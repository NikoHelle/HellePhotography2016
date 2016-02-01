<?php
include_once "lib/config.php";

$path = $_REQUEST["path"];

$set = $config->paths[$path];

?>

<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Helle Photography</title>
    <link rel="stylesheet" href="stylesheets/app.css" />
    <script src="js/vendor/modernizr.custom.js"></script>
    <script src="js/vendor/jquery-min.js"></script>
      <script src="//use.typekit.net/xpy7ian.js"></script>
      <script>try{Typekit.load();}catch(e){}</script>
  </head>
  <body>
   <header id="header">
       <nav class="main">
           <a href="/muut">Muut</a>
           <a href="/haat">Häät</a>
           <a href="/fitness">Fitness</a>
           <a href="/fitness">Potretit</a>
           <a href="/lapset">Lapset</a>
           <a href="/koirat">Koirat</a>
       </nav>
       <nav class="contact">
           <a href="#prices">Hinnasto</a>
           <a href="#contact">Ota yhteyttä</a>
           <a href="/hinnat">Facebook</a>
           <a href="/hinnat">Instagram</a>
           <a href="/hinnat">Pinterest</a>
       </nav>
   </header>
   <section>
       <h1><?= $set->title;?><span><?= $set->subtitle;?></span></h1>
       <?= $set->description;?>
   </section>
   <section>
       <?
       echo file_get_contents("data/".$set->id.".html");
       ?>
   </section>
   <footer>
       <div class="left" id="contact">
           <form>
            <h3>Ota yhteyttä</h3>
            <input type="email" name="email" value="" placeholder="Sähköposti" />
            <textarea name="message" placeholder="Viestisi"></textarea>
            <input type="hidden" name="verification" value="23oi4dslf"/>
            <a href="#" id="sendForm">Lähetä</a>
           </form>
           <h3>Kuvaaja</h3>
           <div class="photographer">
               <p>Niko Helle</p>
               <p>niko@hellephotography.com</p>
               <p>050-3606274</p>
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

           <h1>Copyright</h1>

       </div>
       <div class="divider"></div>
       <a href="#header" class="logo"><img src="img/sprites/logo_text_bw.png" alt="Helle Photography"/></a>

   </footer>
  </body>
</html>
