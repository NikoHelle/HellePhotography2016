<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Helle Photography</title>
    <link rel="stylesheet" href="stylesheets/app.css" />
  <script src="//use.typekit.net/xpy7ian.js"></script>
  <script>try{Typekit.load();}catch(e){}</script>
  <script data-main="js/init.js?t=32942f3334d" src="js/vendor/require.js"></script>
  </head>
  <body>
   <header>
       <nav class="main">
           <a href="/koirat">Koirat
               <span></span>
               <i></i>
           </a>
           <a href="/lapset">Lapset
               <span></span>
               <i></i>
           </a>
           <a href="/haat">Häät
               <span></span>
               <i></i>
           </a>
           <a href="/fitness">Fitness
               <span></span>
               <i></i>
           </a>
           <a href="/fitness">Potretit
               <span></span>
               <i></i>
           </a>
           <a href="/muut">Muut
               <span></span>
               <i></i>
           </a>
       </nav>
       <nav class="contact">
           <a href="/hinnat">Hinnasto</a>
           <a href="/hinnat">Ota yhteyttä</a>

           <a href="/" class="logo">Helle Photography</a>
       </nav>
   </header>
   <section>
       <?php
       echo file_get_contents("http://localhost/hellephotography2/render.php");
       ?>
   <footer>
       <div class="left">
           <h1>Helle Photography</h1>
       </div>
       <div class="right">
           <p>
               Koirien kuvaaminen on mahtavaa puuhaa.
           </p>
           <p>
               Kiinnostuitko?
           </p>
       </div>

   </footer>

    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/foundation/js/foundation.min.js"></script>
    <script src="js/app.js"></script>
  </body>
</html>
