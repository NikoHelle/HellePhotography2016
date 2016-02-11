<?php

$config = (object) array();

$config->meta = (object) array();
$config->meta->siteUrl = "http://helle.photography";
$config->meta->title = "Helle Photography";
$config->meta->siteName = "Helle Photography ikuistaa rakkaasi";
$config->libPath = basename(__DIR__);

$config->db = (object) array();
/**/
$config->db->user = "root";
$config->db->password = "root";
$config->db->host = "localhost";
$config->db->database = "hellephotography2016";

/*
$config->db->user = "hellepho_user1";
$config->db->password = "f{b5E_7!TKLU";
$config->db->host = "localhost";
$config->db->database = "hellepho_images_2016";
*/
$config->flickr = (object) array();

$config->flickr->apiKey = "e9c2d11b4b0c9861766632f00320b128";
$config->flickr->secret = "25e5fcd971be9bb9";
$config->flickr->tokenKey= "72157631808364528-6b6d2e85e9679674";
$config->flickr->tokenSecret = "27c1b928d9306c59";

$config->sets = array();

$config->sets["etusivu"] = (object) array();
$config->sets["etusivu"]->setId = 0;
$config->sets["etusivu"]->id = "etusivu";
$config->sets["etusivu"]->metaImage = "/img/meta_images_etusivu.jpg";
$config->sets["etusivu"]->path = "";
$config->sets["etusivu"]->title = "Etusivu";
$config->sets["etusivu"]->metaDescription = "Helle Photography on lapsi-, koira- ja perhekuvauksiin keskittynyt valokuvaamo. Loistavat kuvat syntyvät rennolla asenteella, kärsivällisyydellä, taiteellisella silmällä ja teknisellä asiantuntemuksella. Studiollani on loistavat puitteet ottaa kaikenlaisia kuvia ja kuvaan myös missä vain muuallakin!";


$config->sets["lapset"] = (object) array();
$config->sets["lapset"]->setId = "72157663923690755";
$config->sets["lapset"]->id = "lapset";
$config->sets["lapset"]->path = "lapset";
$config->sets["lapset"]->metaImage = "/img/meta_images_lapset.jpg";
$config->sets["lapset"]->title = "Lapsikuvaus";
$config->sets["lapset"]->subtitle = "on kuvaajan parasta aikaa!";
$config->sets["lapset"]->description = "<p>Kuvaan paljon 1-vuotiaita, joita hyvin harvoin ujostuttaa ja aina löytyy jotain millä heidät saa hymyilemään. Viimeistään saippuakuplakone saa silmät loistamaan ja iloiset ilmeet jokaiseen kuvaan! <a href='https://facebook.com/hellephotography' target='_blank'>Facebook-sivultani</a> saat tietoa erityisistä <strong>lapsikuvauspäivistä</strong>, jolloin on reilu alennus kuvauksista. Kannattaa siis likettää sitä!</p>";
$config->sets["lapset"]->metaDescription = "META  paljon 1-vuotiaita, joita hyvin harvoin ujostuttaa ja aina löytyy jotain millä heidät saa hymyilemään. Viimeistään saippuakuplakone saa silmät loistamaan ja iloiset ilmeet jokaiseen kuvaan! <a href='https://facebook.com/hellephotography' target='_blank'>Facebook-sivultani</a> saat tietoa erityisistä <strong>lapsikuvauspäivistä</strong>, jolloin on reilu alennus kuvauksista. Kannattaa siis likettää sitä! ";
$config->sets["lapset"]->metaDescription .= $config->sets["etusivu"]->metaDescription;

$config->sets["koirat"] = (object) array();
$config->sets["koirat"]->setId = "72157649851892598";
$config->sets["koirat"]->id = "koirat";
$config->sets["koirat"]->path = "koirat";
$config->sets["koirat"]->metaImage = "/img/meta_images_koirat.jpg";
$config->sets["koirat"]->title = "Koirakuvaus";
$config->sets["koirat"]->subtitle = "on perheenjäsenen ikuistamista";
$config->sets["koirat"]->description = "<p>Kuvaan paljon 1-vuotiaita, joita hyvin harvoin ujostuttaa ja aina löytyy jotain millä heidät saa hymyilemään. Viimeistään saippuakuplakone saa silmät loistamaan ja iloiset ilmeet jokaiseen kuvaan!</p>";
$config->sets["koirat"]->metaDescription = "META  paljon 1-vuotiaita, joita hyvin harvoin ujostuttaa ja aina löytyy jotain millä heidät saa hymyilemään. Viimeistään saippuakuplakone saa silmät loistamaan ja iloiset ilmeet jokaiseen kuvaan!";
$config->sets["koirat"]->metaDescription .= $config->sets["etusivu"]->metaDescription;

$config->sets["potretit"] = (object) array();
$config->sets["potretit"]->setId = "72157663008697505";
$config->sets["potretit"]->id = "potretit";
$config->sets["potretit"]->path = "potretit";
$config->sets["potretit"]->metaImage = "/img/meta_images_potretit.jpg";
$config->sets["potretit"]->title = "Henkiökuvaus";
$config->sets["potretit"]->subtitle = "- ikuista itsesi";
$config->sets["potretit"]->description = "<p>Kuvaan paljon 1-vuotiaita, joita hyvin harvoin ujostuttaa ja aina löytyy jotain millä heidät saa hymyilemään. Viimeistään saippuakuplakone saa silmät loistamaan ja iloiset ilmeet jokaiseen kuvaan!</p>";
$config->sets["potretit"]->metaDescription = "META Kuvaan paljon 1-vuotiaita, joita hyvin harvoin ujostuttaa ja aina löytyy jotain millä heidät saa hymyilemään. Viimeistään saippuakuplakone saa silmät loistamaan ja iloiset ilmeet jokaiseen kuvaan!";
$config->sets["potretit"]->metaDescription .= $config->sets["etusivu"]->metaDescription;

$config->sets["maailma"] = (object) array();
$config->sets["maailma"]->setId = "72157664174695212";
$config->sets["maailma"]->id = "maailma";
$config->sets["maailma"]->path = "maailma";
$config->sets["maailma"]->metaImage = "/img/meta_images_maailma.jpg";
$config->sets["maailma"]->title = "Maailma";
$config->sets["maailma"]->subtitle = "on perheenjäsenen ikuistamista";
$config->sets["maailma"]->description = "<p>Kuvaan paljon 1-vuotiaita, joita hyvin harvoin ujostuttaa ja aina löytyy jotain millä heidät saa hymyilemään. Viimeistään saippuakuplakone saa silmät loistamaan ja iloiset ilmeet jokaiseen kuvaan!</p>";
$config->sets["maailma"]->metaDescription = "META  paljon 1-vuotiaita, joita hyvin harvoin ujostuttaa ja aina löytyy jotain millä heidät saa hymyilemään. Viimeistään saippuakuplakone saa silmät loistamaan ja iloiset ilmeet jokaiseen kuvaan!";
$config->sets["maailma"]->metaDescription .= $config->sets["etusivu"]->metaDescription;

$config->sets["haat"] = (object) array();
$config->sets["haat"]->setId = "72157647937571103";
$config->sets["haat"]->id = "haat";
$config->sets["haat"]->path = "haat";
$config->sets["haat"]->metaImage = "/img/meta_images_haat.jpg";
$config->sets["haat"]->title = "haat";
$config->sets["haat"]->subtitle = "on perheenjäsenen ikuistamista";
$config->sets["haat"]->description = "<p>Kuvaan paljon 1-vuotiaita, joita hyvin harvoin ujostuttaa ja aina löytyy jotain millä heidät saa hymyilemään. Viimeistään saippuakuplakone saa silmät loistamaan ja iloiset ilmeet jokaiseen kuvaan!</p>";
$config->sets["haat"]->metaDescription = "META Kuvaan paljon 1-vuotiaita, joita hyvin harvoin ujostuttaa ja aina löytyy jotain millä heidät saa hymyilemään. Viimeistään saippuakuplakone saa silmät loistamaan ja iloiset ilmeet jokaiseen kuvaan!";
$config->sets["haat"]->metaDescription .= $config->sets["etusivu"]->metaDescription;

$menu = array("lapset","koirat");


?>

