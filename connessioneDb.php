<?php
    $type = "mysql";
    $server = "localhost";
    $db = "case";
    $port ="3306";
    $charset = "utf8mb4";
        
    $username = "dani_fabbro";
    $password = "q6g@HcZM8VPBt-n[";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $dsn = "$type:host=$server;dbname=$db;charset=$charset;port=$port";

    try {
        $pdo = new PDO($dsn, $username, $password, $options);
    }
    catch (PDOException $e){
        throw new PDOException($e->getMessage(), $e->getCode());
    }

?>