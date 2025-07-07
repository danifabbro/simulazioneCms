<?php
    require "connessioneDb.php";
    require "funzioni.php";

    $term = filter_input(INPUT_GET,'term');    // legge il termine ricercato
    $show = filter_input(INPUT_GET,'show',FILTER_VALIDATE_INT) ?? 4; // Limite per pagine
    $from = filter_input(INPUT_GET,'from',FILTER_VALIDATE_INT) ?? 0; // da quale elemento della ricerca nell'array deve partire per mostrarlo in un impaginazione

    $count = 0; // imposto il contatore dei risultati a 0
    $articoli = []; // imposto l'array che ospita gli articoli


    if($term){// se esiste il termine

            $arguments['term1'] = "%$term%";
            $arguments['term2'] = "%$term%";
            $arguments['term3'] = "%$term%";            


            $sql = "SELECT COUNT(titolo) FROM articoli
                    WHERE titolo LIKE :term1
                    OR sottotitolo LIKE :term2
                    OR contenuto LIKE :term3                    
                    AND pubblicato = 1 ;";
            
            $count = pdo($pdo,$sql,$arguments)->fetchColumn();

            if($count > 0)
            {
                $arguments['show'] = $show;
                $arguments['from'] = $from;
                $sql = "SELECT art.id, art.titolo, art.sottotitolo,art.contenuto, art.categoria_id, art.account_id,
                                c.nome AS categoria,
                                CONCAT(acc.nome,' ',acc.cognome) AS autore,
                                acc.id AS idAutore,
                                i.file AS immagine,
                                i.alt AS immagine_alt
                                FROM articoli AS art                            
                                LEFT JOIN categoria AS c ON art.categoria_id = c.id
                                LEFT JOIN account AS acc ON art.account_id = acc.id
                                LEFT JOIN immagini AS i ON art.immagine_id = i.id
                                WHERE (art.titolo LIKE :term1
                                OR art.sottotitolo LIKE :term2
                                OR art.contenuto LIKE :term3) 
                                AND art.pubblicato = 1
                                ORDER BY art.id DESC
                                LIMIT :show
                                OFFSET :from
                                ;";   // sono stati cambiati i nomi da from a da per sicurezza
                $articoli = pdo($pdo,$sql,$arguments)->fetchAll();
            }
        }

        if($count > $show){
            $pagine_totali = ceil($count / $show); // totale impaginazioni
            $pagina_corrente = ceil($from / $show) + 1; // in quale ci si trova dell'impaginazione
        }

        $sql = "SELECT id, nome FROM categoria WHERE navigazione = 1;";
        $navigazione = pdo($pdo,$sql)->fetchAll();

        $sezione = '';
        $titolo = 'ricerca per il risultato di'.$term;
        $descrizione = $titolo . 'sulle notizie di Daniel';

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
        <div class="row justify-content-center">   
            <div class="col-md-4">
            </div>
            <div class="col-md-4 text-center">
                <sezione>   
                    <form action="cerca.php" method="get">
                        <label></label>
                        <input type="text" name="term" value="<?= htmlspecialchars($term) ?>"  placeholder = "ricerca articolo per: "/>
                        <input type="submit" value="Cerca" class="btn-success"/>
                    </form>  
                    <?php if($term){?> <b>Elementi trovati: </b> <?= $count ?> <?php } ?>   
                </sezione>
            </div>                
            <div class="col-md-4">
            </div>
        </div>
        <div class="row justify-content-center">   
                <div class="col-md-4">
                </div>
                <div class="col-md-4 text-center row">
                    <?php if($count > $show){ ?>
                        <nav role="navigation">
                            <ul>
                                <?php for($i = 1; $i <= $pagine_totali; $i++){ ?>
                                    <li style ="list-style-type:none">
                                        <a style="text-decoration:none; padding:0.3rem;" href="?term=<?= $term ?>&show=<?= $show ?>&from=<?= (($i - 1)* $show) ?>"
                                        class="btn-success<?= ($i == $pagina_corrente) ? 'active" aria-current="true' : '' ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </nav>                
                    <?php }?>            
                </div>                
                <div class="col-md-4">
                </div>
            </div> 
        <sezione>            
            <div class="row">
                <?php foreach($articoli as $articolo) {?>
                    <div class="col-md-3 text-center bg-white p-5 m-4 box-articolo">                        
                            <h2 class="mb-5">
                                <?= html_escape($articolo['titolo']);?>
                            </h2>
                            <h6 class="mb-5">
                                <?= html_escape($articolo['sottotitolo']);?>
                            </h6>
                            <p>
                                <a style="text-decoration:none; padding:0.5rem;" class="btn-success" href="articolo.php?id=<?= $articolo['id'] ?>">Scopri di più</a>
                            <p>                        
                            <img style="max-width:300px; max-height:300px; border-radius: 10px 10px 10px 10px" class="mt-5" src="../simulazioneCms/img/<?= html_escape($articolo['immagine']) ?>">                                                                  
                            <div class="row mt-5">                           
                                <div class="col-2">
                                </div>
                                <div class="col-3">
                                    <p>Autore: </p>
                                </div>
                                <div class="col-7">
                                    <p>
                                        <a style="text-decoration:none; padding:0.5rem;" class="btn-primary" href ="membro.php?id=<?= $articolo['idAutore'] ?>">
                                            <?= html_escape($articolo['autore']); ?>
                                        </a>
                                    </p>
                                </div> 
                            </div>                                                  
                        </div>
                <?php } ?>   
            </sezione>                                       
    </body>
</html>