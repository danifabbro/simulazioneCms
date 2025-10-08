<?php
    define("APP_ROOT", dirname(__FILE__,2)); //salva nella costante APP_ROOT il percorso della cartella root dell'applicazione(due livelli sopra)
    require APP_ROOT . 'funzioni.php'; 
    require APP_ROOT . '/src/config.php';

    spl_autoload_register(function($class)){ // funzione di autoload cosi le classi vengono caricate solo se ce n'è bisogno
        $path = APP_ROOT . 'src/classi/'; // percorso delle classi
        require $path . $class . '.php';
    }

    if(DEV !== true){
        set_exception_handler('handle_exception'); // gestore delle eccezioni
        set_error_handler('handle_error'); // gestore degli errori
        register_shutdown_function('handle_shutdown'); // gestore del shutdown
    }

    $cms = new CMS($dsn, $username, $password); // nuovo oggetto CMS
    unset($dsn, $username, $password); // rimuove i dati di connessione al db

?>