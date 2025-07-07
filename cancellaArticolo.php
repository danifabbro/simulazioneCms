<?php
    require "connessioneDb.php";
    require "funzioni.php";

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if(!$id)
    {
        redirect('editArticolo.php', ['failure' => 'articolo non trovato']); // controllo sull'id passato dell'articolo
    }
    //sezione di controllo se le info dell'articolo sono presenti:
    $articoli = false;

    $sql = "SELECT art.titolo, art.immagine_id, imm.file AS immagine_file FROM articoli AS art
            LEFT JOIN immagini AS imm ON art.immagine_id = imm.id 
            WHERE art.id = :id ;";
    $articoli = pdo($pdo, $sql, [$id])->fetch();
    if(!$articoli){
        redirect('editArticolo.php', ['failure' => 'articolo non trovato']); // controllo sulle info selectate nell'sql
    }


    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        try{
            $pdo->beginTransaction();
            if($immagine_id){
                    $sql = "UPDATE articoli SET immagine_id = null WHERE id = :articolo_id ;";
                    pdo($pdo, $sql, [$id]);

                    $sql = "DELETE FROM immagini WHERE id = :id ;";
                    pdo($pdo, $sql, [$articoli['immagine_id']]);

                    $path = '../img/' . $articoli['immagine_file'];
                    if(file_exists($path)){
                        $unlink = unlink($path);
                    }
                }

                $sql = "DELETE FROM articoli WHERE id = :id ;";
                pdo($pdo, $sql, [$id]);
                $pdo->commit();
                redirect('menuPrincipale.php', ['success' => 'articolo eliminato']);

        } catch(PDOException $e){
            $pdo->rollBack();
            throw $e;  
        }            
    }
            
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
            <div class="row">
                <?php include "header.php"; ?>
                <h2>Cancella articolo</h2>
                <form action="cancellaArticolo.php?id=<?= $id ?>" method="POST" class="narrow">
                    <p> Clicca per confermare la cancellazione dell'articolo:  <?= html_escape($articoli['titolo']); ?></p>
                    <input type = "submit" name="cancella" value="conferma" class="btn btn-primary">
                    <a href="editArticolo.php?id=<?= $id ?>" class="btn btn-danger"> Annulla </a>
                </form>
            </div>                                  
    <body>
</html>