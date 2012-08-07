<?php

require 'src/instapaper_xauth.php';

$insta = new Instapaper_XAuth("CONSUMER_KEY", "CONSUMER_SECRET");
var_dump($insta->login("USERNAME", "PASSWORD"));
