<?php
    require "funzioni.php";
    require "connessioneDb.php";

    $sql = "SELECT art.id,acc.id AS idAutore, art.titolo, art.sottotitolo, art.contenuto, 
            CONCAT(acc.nome,' ',acc.cognome) AS autore,
            c.nome AS categoria,
            i.file AS immagine
            FROM articoli AS art
            LEFT JOIN account AS acc ON art.account_id = acc.id
            LEFT JOIN categoria AS c ON art.categoria_id = c.id
            LEFT JOIN immagini AS i ON art.immagine_id = i.id;"
        ;
    
    $articoli = pdo($pdo,$sql)->fetchAll();

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
        <div class="row justify-content-around">
            <?php include "header.php"; ?>   
            <?php foreach($articoli as $articolo){ 
                if($articolo['immagine'] == false){                    
                    continue ;
                }                
                ?>                   
                <div class="col-lg-4 mt-5 text-center bg-white p-5 mb-3 box-articolo">                        
                    <h2 class="mb-5">
                        <?= html_escape($articolo['titolo']);?>
                    </h2>
                    <h6 class="mb-5">
                        <?= html_escape($articolo['sottotitolo']);?>
                    </h6>
                    <p>
                        <a style="text-decoration:none; padding:0.5rem;" class="btn-resp" href="articolo.php?id=<?= $articolo['id'] ?>">Scopri di più</a>
                    <p>                        
                    <img style="border-radius: 10px 10px 10px 10px; height:300px; width:400px;" class="mt-5 img-fluid img-articolo" src="../simulazioneCms/img/<?= /*problema grave*/ html_escape($articolo['immagine']) ?>">                                                                  
                    <div class="row mt-5">                           
                        <div class="col-3">
                            <p>
                                <a style="text-decoration:none; padding:0.5rem;" class="btn-resp" href ="editArticolo.php?id=<?= $articolo['id'] ?>">
                                    Modifica                                      
                                </a>
                            </p>
                        </div>
                        <div class="col-2">
                            <p>Autore: </p>
                        </div>
                        <div class="col-7">
                            <p>
                                <a style="text-decoration:none; padding:0.5rem;" class="btn-resp" href ="membro.php?id=<?= $articolo['idAutore'] ?>">
                                    <?= html_escape($articolo['autore']); ?>
                                </a>
                            </p>
                        </div> 
                    </div>                
                </div>
            <?php };?>
        </div>      
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>                                                                                                               
    <body>
</html>