<?php
    require "funzioni.php";
    require "connessioneDb.php";

    $id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
    if(!$id){
        echo "id non trovato";
    }

    $sql = "SELECT nome, cognome, data_di_nascita, unito, fotoprofilo FROM account WHERE id = :id;";
    $account = pdo($pdo,$sql,[$id])->fetch();
    if(!$account)
    {
        echo "account non valido";
    }

    $sql = "SELECT art.id, art.titolo, art.sottotitolo, art.contenuto, art.account_id,
            CONCAT(acc.nome,' ',acc.cognome) AS autore,
            c.nome AS categoria,
            i.file AS immagine
            FROM articoli AS art
            LEFT JOIN account AS acc ON art.account_id = acc.id
            LEFT JOIN categoria AS c ON art.categoria_id = c.id
            LEFT JOIN immagini AS i ON art.immagine_id = i.id
            WHERE art.account_id = :id AND art.pubblicato = 1;"
        ; // condizionare la ricerca sull'id 
    
    $articoli = pdo($pdo,$sql,[$id])->fetchAll();// mettere sempre l'argomento nel pdo quando richiamo l'id dalla query string

    $sql = "SELECT id, nome FROM categoria WHERE navigazione = 1;";
    $navigazione = pdo($pdo,$sql)->fetchAll();   
    $sezione = ''; 
?>
<!DOCTYPE html>
<html lang = "it-IT">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">    <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Outfit:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">      
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://danielfabretti.hopto.org/simulazioneCms/stileApp.css" type="text/css">
    </head>        
        <body class="container-fluid">
            <?php include "header.php"; ?> 
                <div class="row">
                    <div class="col-md-3">                    
                    </div>
                    <div class="col-md-6 text-center">
                        <h1>
                            <?= html_escape($account['nome']); ?>
                            <?= html_escape($account['cognome']); ?>
                        </h1>
                        <h4>
                            Data di nascita: <?= html_escape($account['data_di_nascita']) ?>
                        </h4>                        
                    </div> 
                    <div class="col-md-3">                    
                    </div>    
                    <h3>Articoli scritti: </h3>                                                         
                    <?php foreach($articoli as $articolo){ ?>                                                                                                                  
                        <div class="col-md-3 bg-white m-4 box-articolo">
                            <img style="width:400px; height:300px;" class="mt-5 img-fluid" src="../simulazioneCms/img/<?= html_escape($articolo['immagine']) ?>"></br>                                                                                          
                            <h5 class="text-center mt-2">
                                <?= html_escape($articolo['titolo']); ?>
                            </h5>
                            <div class="row p-3">
                                <div class="col-md-4">                                    
                                </div>
                                <div class="col-md-4">                                    
                                    <a style="text-decoration:none;" class="btn-success p-2" href="articolo.php?id=<?= html_escape($articolo['id'])?>">Scopri di più</a>
                                </div>
                                <div class="col-md-4">                                    
                                </div>
                            </div>
                        </div>                           
                    <?php };?>
            </div>        
    <body>
</html>