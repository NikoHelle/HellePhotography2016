<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Helle Photography</title>
    <link rel="stylesheet" href="stylesheets/app.css" />
    <script src="bower_components/modernizr/modernizr.js"></script>
      <script src="//use.typekit.net/xpy7ian.js"></script>
      <script>try{Typekit.load();}catch(e){}</script>
  </head>
  <body>
   <header>
       <nav class="main">
           <a href="/muut">Muut</a>
           <a href="/haat">Häät</a>
           <a href="/fitness">Fitness</a>
           <a href="/fitness">Potretit</a>
           <a href="/lapset">Lapset</a>
           <a href="/koirat">Koirat</a>
       </nav>
       <nav class="contact">
           <a href="/hinnat">Hinnasto</a>
           <a href="/hinnat">Ota yhteyttä</a>
           <a href="/hinnat">Facebook</a>
           <a href="/hinnat">Instagram</a>
           <a href="/hinnat">Pinterest</a>
       </nav>
   </header>
   <section>
       <h1>Lapset</h1><p>Lapsikuvaus on kuvaajan parasta aikaa! Kuvaan paljon 1-vuotiaita, joita hyvin harvoin ujostuttaa ja aina löytyy jotain millä heidät saa hymyilemään. Viimeistään saippuakuplakone saa silmät loistamaan ja iloiset ilmeet jokaiseen kuvaan!</p>
   </section>
   <section>
       <?

       echo file_get_contents("data/test.html");
       ?>
   </section>
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
