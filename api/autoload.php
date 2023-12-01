<?php

function autoload_entity($className)
{
    $filename = "entity/".$className.".php";
    if (is_readable($filename)) {
        require $filename;
    }
}

function autoload_core($className)
{
    $filename = "core/".$className.".php";
    if (is_readable($filename)) {
        require $filename;
    }
}
spl_autoload_register("autoload_entity");
spl_autoload_register("autoload_core");
