<?php
$cake = 'me@lol.com';
$test=filter_var( $cake, FILTER_SANITIZE_EMAIL);
var_dump($test)
?>