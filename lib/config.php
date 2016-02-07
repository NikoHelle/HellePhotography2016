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
$config->sets["lapset"] = (object) array();
$config->sets["lapset"]->setId = "72157663923690755";
$config->sets["lapset"]->id = "lapset";
$config->sets["lapset"]->path = "lapset";
$config->sets["lapset"]->title = "Lapsikuvaus";
$config->sets["lapset"]->subtitle = "on kuvaajan parasta aikaa!";
$config->sets["lapset"]->description = "<p>Kuvaan paljon 1-vuotiaita, joita hyvin harvoin ujostuttaa ja aina löytyy jotain millä heidät saa hymyilemään. Viimeistään saippuakuplakone saa silmät loistamaan ja iloiset ilmeet jokaiseen kuvaan!</p>";

$config->sets["koirat"] = (object) array();
$config->sets["koirat"]->setId = "72157649851892598";
$config->sets["koirat"]->id = "koirat";
$config->sets["koirat"]->path = "koirat";
$config->sets["koirat"]->title = "Koirakuvaus";
$config->sets["koirat"]->subtitle = "on perheenjäsenen ikuistamista";
$config->sets["koirat"]->description = "<p>Kuvaan paljon 1-vuotiaita, joita hyvin harvoin ujostuttaa ja aina löytyy jotain millä heidät saa hymyilemään. Viimeistään saippuakuplakone saa silmät loistamaan ja iloiset ilmeet jokaiseen kuvaan!</p>";

$config->sets["maailma"] = (object) array();
$config->sets["maailma"]->setId = "72157664174695212";
$config->sets["maailma"]->id = "maailma";
$config->sets["maailma"]->path = "maailma";
$config->sets["maailma"]->title = "Maailma";
$config->sets["maailma"]->subtitle = "on perheenjäsenen ikuistamista";
$config->sets["maailma"]->description = "<p>Kuvaan paljon 1-vuotiaita, joita hyvin harvoin ujostuttaa ja aina löytyy jotain millä heidät saa hymyilemään. Viimeistään saippuakuplakone saa silmät loistamaan ja iloiset ilmeet jokaiseen kuvaan!</p>";


$menu = array("lapset","koirat","maailma");


?>

