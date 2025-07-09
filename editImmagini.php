<?php
    require "connessioneDb.php";
    require "funzioni.php";
    
    $sql = "SELECT id, file, alt FROM immagini;";
    $immagini = pdo($pdo,$sql)->fetchAll();
    
            
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
                <div class="col-md-12">
                    <table>
                        <tr style="border:0px;">                           
                            <th>File</th>
                            <th>Alt</th>
                            <th>Elimina</th>
                        </tr>
                        <?php foreach($immagini as $immagine){ ?>
                            <tr>                               
                                <td><img style="width:400px; height:300px;" class="mt-5" src="../simulazioneCms/img/<?= html_escape($immagine['file']) ?>"></td>
                                <td><?= html_escape($immagine['alt']) ?></td>
                                <td><a style="padding:1rem; text-decoration:none;" class="btn-danger" href ="cancella-immagine.php?id=<?= $immagine['id'] ?>">Cancella</a></td>
                            </tr>
                    <?php } ?>
                    </table>                                    
                </div>                
            </div>        
    <body>
</html>