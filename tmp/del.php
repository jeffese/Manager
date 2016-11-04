<?php
$f = $_GET['f'];
$c = 0;

do {
    $c++;
    sleep(1);
} while(!unlink("print-$f.html") && $c < 10);
