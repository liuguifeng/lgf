<?php
require('./sphinxapi.php');
$sp=new SphinxClient();
$sp->SetServer('localhost',9312);
$rec=$sp->query('黑色');
//var_dump($rec);

