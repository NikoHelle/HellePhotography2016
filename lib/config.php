<?php

$config = (object) array();

$config->db = (object) array();
$config->db->user = "root";
$config->db->password = "root";
$config->db->host = "localhost";
$config->db->database = "hellephotography2016";

$config->flickr = (object) array();

$config->flickr->apiKey = "e9c2d11b4b0c9861766632f00320b128";
$config->flickr->secret = "25e5fcd971be9bb9";
$config->flickr->tokenKey= "72157631808364528-6b6d2e85e9679674";
$config->flickr->tokenSecret = "27c1b928d9306c59";

$config->sets = array();
$config->sets["children"] = (object) array();
$config->sets["children"]->setId = "72157663923690755";
$config->sets["children"]->id = "children";
$config->sets["children"]->path = "lapset";
$config->sets["children"]->title = "Lapsikuvaus";
$config->sets["children"]->subtitle = "on kuvaajan parasta aikaa!";
$config->sets["children"]->description = "<p>Kuvaan paljon 1-vuotiaita, joita hyvin harvoin ujostuttaa ja aina löytyy jotain millä heidät saa hymyilemään. Viimeistään saippuakuplakone saa silmät loistamaan ja iloiset ilmeet jokaiseen kuvaan!</p>";

$config->sets["dogs"] = (object) array();
$config->sets["dogs"]->setId = "72157649851892598";
$config->sets["dogs"]->id = "dogs";
$config->sets["dogs"]->path = "koirat";
$config->sets["dogs"]->title = "Koirakuvaus";
$config->sets["dogs"]->subtitle = "on perheenjäsenen ikuistamista";
$config->sets["dogs"]->description = "<p>Kuvaan paljon 1-vuotiaita, joita hyvin harvoin ujostuttaa ja aina löytyy jotain millä heidät saa hymyilemään. Viimeistään saippuakuplakone saa silmät loistamaan ja iloiset ilmeet jokaiseen kuvaan!</p>";

$config->paths = array();
$config->paths["lapset"] = $config->sets["children"];
$config->paths["koirat"] = $config->sets["dogs"];

$menu = array("lapset","koirat");


?>

