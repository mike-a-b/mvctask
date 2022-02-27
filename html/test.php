<?php
$parts = parse_url($_SERVER['REQUEST_URI']);
$path = $parts['path'];
var_dump($parts);

