<?php 
    define('DEV', true); // in sviluppo = true o online = false
    define("DOC_ROOT", 'C:\xampp\htdocs\simulazioneCms'); // percorso doc root del sito NB: in questo caso gli slash sono verso sinistra perchè in locale
    define("ROOT_FOLDER", 'simulazioneCms'); //nome della cartella root

    //impostazione database

    $type = 'mysql';
    $server = 'localhost';
    $db = 'case';
    $port = '3306';
    $charset = 'utf8mb4';
    $username = 'dani_fabbro';
    $password = 'q6g@HcZM8VPBt-n[';
    $dsn = "$type:host=$server;dbname=$db;port=$port;charset=$charset";

    //impostazione per gli upload dei file
    
    define('MEDIA_TYPES',['image/jpeg','image/png','image/gif',]);
    define('FILE_EXTENSIONS',['jpeg','jpg','png','gif',]);
    define('MAX_SIZE','5242880');
    define('UPLOADS', dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . ROOT_FOLDER . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR);
?>