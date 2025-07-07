<?php
    require "connessioneDb.php";
    require "funzioni.php";

    $id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
    if(!$id){
        echo "id non trovato";
    }

    $sql = "SELECT id, nome, descrizione FROM categoria WHERE id = :id;";
    $categoria = pdo($pdo,$sql, [$id])->fetch();

    if(!$categoria){
        echo "categoria errata";
    }

    //visualizzazione dei dati nella pagina attraverso una select
    $sql = "SELECT art.id, art.titolo,art.sottotitolo,art.categoria_id, art.contenuto,art.account_id, CONCAT(acc.nome,' ',acc.cognome) AS autore,
                   c.nome, c.descrizione, imm.file
            FROM articoli AS art
            LEFT JOIN account AS acc ON art.account_id = acc.id
            LEFT JOIN categoria AS c ON art.categoria_id = c.id
            LEFT JOIN immagini  AS imm ON art.immagine_id = imm.id
            WHERE art.categoria_id = :id AND art.pubblicato = 1 
            ORDER BY art.id DESC;"; // cerco per categoria id cosi da includere più articoli dello stesso tipo. 
    $articoli = pdo($pdo,$sql,[$id])->fetchAll();


    // collegamento con le informazione del menu header
    $sql = "SELECT id,nome FROM categoria WHERE navigazione = 1;"; // si usa un numero sotto navigazione per richiamare l'id nella variabile categoria cosi da collegarla alla header in sezione
    $navigazione = pdo($pdo,$sql)->fetchAll();

    //variabili valorizzate per l'header col richiamo dei dati della categoria
    $sezione = $categoria['id'];
    $title = $categoria['nome'];
    $descrizione = $categoria['descrizione'];
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
                <div class="col-md-3 bg-white">
                    <h1>
                        <?= html_escape($categoria['nome']); ?>
                    </h1>
                </div>                             
                <?php foreach($articoli as $articolo){ ?>                                                                                             
                    <div class="col-md-3 bg-white m-3 p-3">                                                                
                        <h2>
                            <?= html_escape($articolo['titolo']); ?>
                        <h2>             
                        <h6>
                            <?= html_escape($articolo['sottotitolo']); ?>                                                                                 
                        </h6>        
                        <a style = "text-decoration:none;" class = "btn-success p-1" href = "articolo.php?id=<?= html_escape($articolo['id'])?>">Scopri di più</a>                
                    </div>                    
                <?php } ?> 
            </div>                             
        <body>
</html>
