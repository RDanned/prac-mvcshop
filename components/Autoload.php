<?php

/*function __autoload($class_name) ///старый
{
    # List all the class directiories in the array
    $array_paths = array(
        '/models/',
        '/components/'
    );
    
    foreach ($array_paths as $path) {
        $path = ROOT . $path . $class_name . '.php';
        if(is_file($path)) {
            include_once $path;
        }
    }
}*/

spl_autoload_register(function($class_name){//новый
   $classPaths = [
       '/components/',
       '/models/'
   ];
    
   foreach ($classPaths as $path) {
       $path = ROOT . $path.$class_name . '.php';
       if(is_file($path)) {
        include_once $path;
        }
   }
    
}); 