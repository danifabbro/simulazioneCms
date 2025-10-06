<?php
    require "funzioni.php";
    require "connessioneDb.php";


    //ARTICOLO IN BASE ALL'ID
    $id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
    if(!$id)
    {
        echo "id non valido";
    }

    $sql = "SELECT art.titolo, art.longitudine, art.latitudine, art.sottotitolo, art.contenuto, art.categoria_id,
            art.numTel AS numero1,
            art.numTel_2 AS numero2,
            CONCAT(acc.nome,' ',acc.cognome) AS autore,
            c.nome AS categoria,
            i.file AS immagine
            FROM articoli AS art
            LEFT JOIN account AS acc ON art.account_id = acc.id
            LEFT JOIN categoria AS c ON art.categoria_id = c.id
            LEFT JOIN immagini AS i ON art.immagine_id = i.id
            WHERE art.id = :id;"
        ;    
    $articoli = pdo($pdo,$sql,[$id])->fetch();

    //NAVIGAZIONE 
    $sql = "SELECT id, nome FROM categoria WHERE navigazione = 1;";
    $navigazione = pdo($pdo,$sql)->fetchAll();    

    $sezione = $articoli['categoria_id'];
    $titolo = $articoli['titolo'];
    $descrizione = $articoli['sottotitolo'];
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
        <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
        <link rel="stylesheet" href="https://danielfabretti.hopto.org/simulazioneCms/stileApp.css" type="text/css">
    </head>        
        <body class="container-fluid">
            <div class="row">
                <?php include "header.php"; ?>                           
                <div class="col-md-12">                                        
                    <h2>
                        <?= html_escape($articoli['titolo']); ?>
                    </h2>
                    <p>
                        <?= html_escape($articoli['contenuto']); ?>
                    </p>
                    
                    <div class="mt-5 row">
                        <h2>Dove si trova</h2>
                        <div class="col-lg-6" id ="map">                        
                        </div>
                        <div class="col-lg-2">                        
                            <a href ="https://www.google.it/maps/dir//<?= $articoli['latitudine'] ?>,<?= $articoli['longitudine']?>?hl=it" class=" btn-resp" style="margin-left:3rem; padding:0.5rem; text-decoration:none;">Vai al navigatore</a><br><br>
                            <a href ="tel:+39<?= $articoli['numero1'] ?>" class=" btn-resp" style="margin-left:3rem; padding:0.5rem; text-decoration:none;" >Telefona al numero 1</a><br><br>
                            <a href ="tel:+39<?= $articoli['numero2'] ?>" class=" btn-resp" style="margin-left:3rem; padding:0.5rem; text-decoration:none;">Telefona al numero 2</a>
                        </div>                      
                    </div>                                        
                </div>
            </div>     
                    
            <script>
                window.addEventListener("load", function () {
                    const map = L.map('map').setView([<?= $articoli['latitudine'] ?>, <?= $articoli['longitudine']?>], 20); // Roma

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    // 📍 Aggiunta di un marker statico
                    const marker = L.marker([<?= $articoli['latitudine'] ?>, <?= $articoli['longitudine']?>]).addTo(map);
                    marker.bindPopup("<b><?= $articoli['titolo']?></b><br><?= $articoli['sottotitolo']?>").openPopup();
                });

            </script>     
    </body>
</html>