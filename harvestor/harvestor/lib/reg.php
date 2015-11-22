<?php

$out = file_get_contents("http://www.google.com/nonexist");
if ($out === false) echo "Out";
