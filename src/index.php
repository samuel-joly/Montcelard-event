<?php
$uri_levels = explode("/", $_REQUEST["REQUEST_URI"]);
if (sizeof($uri_levels) != 0) {
    $region = $uri_levels[0];
    if (sizeof($uri_levels) > 1) {
        $region_department = $uri_levels[1];
    }
} else {
    $region = "/";
}

var_dump($uri_levels, $region, $region_department);
