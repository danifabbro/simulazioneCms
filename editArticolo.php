<?php

    //------PARTE A PREDISPORRE I DATI

    declare(strict_types = 1); // indica che le variabili assumono il tipo di valore dichiarato dal tipo di dato.
    include 'connessioneDb.php';
    include 'funzioni.php';
    
    $uploads = dirname(__DIR__,1) . DIRECTORY_SEPARATOR . 'simulazioneCms'. DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR; // path immagini    

    $tipo_file = ['image/jpeg','image/png','image/gif',]; //tipi consentiti di file per le immagini
    $estensione_file = ['jpg','jpeg','png','gif',]; // estensioni accettate per i file di immagini
    $dimensione_max = 5242880; // dimensione massima consentita dal file uploadato.

    $id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);// ottengo l'id dalla query string e lo convalida
    $temp = $_FILES['image']['tmp_name'] ?? null; // immagine temporanea
    $destinazione = null; // dove salvare il file

    $articoli = [
        'id' => $id, 'titolo' => '',
        'sottotitolo' => '', 'contenuto' => '',
        'categoria_id' => 0, 'account_id' => 0,
        'immagine_id' => null, 'pubblicato' => false,
        'immagine_file' => '', 'immagine_alt' => '',
        'latitudine' => 0 , 'longitudine' => 0,
    ];

    $errori = [
        'avviso' => '', 'titolo' => '' , 'sottotitolo' => '', 'contenuto' => '', 'autore' => '', 'categoria' => '', 'immagine_file' => '', 'immagine_alt' => '','immagine_id'=> null,
        'latitudine' => '', 'longitudine' => '',
    ]; 

    if($id){
        $sql = "SELECT art.id, art.titolo, art.sottotitolo, art.contenuto, art.categoria_id, art.account_id, art.immagine_id, art.pubblicato,
                art.latitudine, art.longitudine, 
                imm.file AS immagine_file,
                imm.alt AS immagine_alt
                FROM articoli AS art
                LEFT JOIN immagini AS imm ON immagine_id = imm.id
                WHERE art.id = :id ;";
                
                
        $articoli = pdo($pdo,$sql,[$id])->fetch();
        if(!$articoli){
            redirect('menuPrincipale.php',['fallimento' => 'articolo non trovato']);
        }        
    }
     
    // var_dump($articoli); articoli funziona

    // leggere tutti gli iscritti e tutte le categorie

    // ACCOUNT/AUTORI
    $sql = "SELECT id, nome, cognome FROM account ;";   
    $autori = pdo($pdo,$sql)->fetchAll();        
    // CATEGORIE 

    $sql = "SELECT id, nome, descrizione FROM categoria ;";
    $categorie = pdo($pdo,$sql)->fetchAll();
   
            
    //---------PARTE B CONVALIDARE I DATI

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $errori['immagine_file'] = ($_FILES['image']['error'] === 1) ? 'file troppo pesante' : '' ;
        
        if($temp and $_FILES['image']['error'] === 0){    // Se il file è stato inviato nel modulo dal submit con una richiesta post
            $articoli['immagine_alt'] = $_POST['immagine_alt']; // allora legge l'alt del file.

            //------Convalida il file dell'immagine----

            //controllo tipo di file immagine...
            $errori['immagine_file'] .= in_array(mime_content_type($temp), $tipo_file) ? "" : "Il tipo di file non rientra in quelli consentiti";
            
            //controllo estensione immagine...
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $errori['immagine_file'] .= in_array($ext, $estensione_file) ? "" : "Il tipo di estensione non rientra in quelli consentiti";

            //controllo dimensione immagine...
            $errori['immagine_file'] .= ($_FILES['image']['size'] <= $dimensione_max) ? "" : "File troppo pesante";            

            //controllo testo alt immagine...
            $errori['immagine_alt']  = (is_text($articoli['immagine_alt'],1,254)) ? "" : "L'alt text non rientra tra i caratteri max di 254";            

            //Se il file dell'immagine non ha errori ed è valido......
            if($errori['immagine_file'] === "" and $errori['immagine_alt'] === ""){
                $articoli['immagine_file'] = create_filename($_FILES['image']['name'], $uploads);
                $destinazione = $uploads . $articoli['immagine_file'];
                            
                // 🔽 Codice per muovere il file nella destinazione
                if (!move_uploaded_file($temp, $destinazione)) {
                    $errori['immagine_file'] = "Errore nel salvataggio del file fisico.";
                }
            }
        }
        
        //------Convalida filtro dati articolo inserito----
        
        //---lettura dati articolo        
        $articoli['titolo'] = $_POST['titolo'];// nei post dev' essere presente il nome che si trova nei name degli input
        $articoli['sottotitolo'] = $_POST['sottotitolo'];
        $articoli['contenuto'] = $_POST['contenuto'];
        $articoli['account_id'] = $_POST['account_id'];
        $articoli['categoria_id'] = $_POST['categoria_id'];
        $articoli['latitudine'] = $_POST['latitudine'];
        $articoli['longitudine'] = $_POST['longitudine'];    
        $articoli['pubblicato'] = $articoli['pubblicato'] = isset($_POST['pubblicato']) ? 1 : 0; // se il post è pubblicato

        //---convalida dati dell'articolo nelle variabili errori con i messaggi
        $errori['titolo'] = is_text($articoli['titolo'],1,80) ? "" : "Il titolo dev'essere compreso tra gli 1 e gli 80 caratteri";
        $errori['sottotitolo'] = is_text($articoli['sottotitolo'],1,254) ? "" : "Il sottotitolo dev'essere compreso tra gli 1 e i 254 caratteri"; 
        $errori['contenuto'] = is_text($articoli['contenuto'],1,100000) ? "" : "Il sottotitolo dev'essere compreso tra gli 1 e i 100'000 caratteri";  
        $errori['autore'] = is_account_id($articoli['account_id'],$autori) ? "" : "Per favore seleziona un autore";
        $errori['categoria'] = is_categoria_id($articoli['categoria_id'],$categorie) ? "" : "Per favore seleziona una categoria";
        $errori['latitudine'] = ($articoli['latitudine'] <= 90 ) ? "" : "Inserisci una coordinata corretta";
        $errori['longitudine'] = ($articoli['longitudine'] <= 180) ? "" : "Inserisci una coordinata corretta";

        //uniamo gli errori
        $invalid = implode($errori);

        if($invalid)
        {
            $errori['avviso'] = "Per favore correggi gli errori";
        }else{
            $arguments = $articoli; // negli arguments del PDO mette i dati dell'articolo
            try{
                $pdo->beginTransaction();
                if($destinazione){
                    //creazione thumbnail con imagick se scaricata la libreria
                    /*$imagick = new \Imagick($temp); // creo un oggetto imagick
                    $imagick->cropThumbnailImage(1200,700);// richiamo dell'oggetto imagick il metodo cropThumnailImage
                    $imagick->writeImage($destinazione);// salvo l'immagine*/
                    
                    $sql = "INSERT INTO immagini (file,alt) VALUES (:file, :alt);";// inserimento nuova immagine  I VALORI :FILE E :ALT VENGONO PRESI DAL TEXTBOX

                    pdo($pdo,$sql,[$arguments['immagine_file'], $arguments['immagine_alt'],]);
                    $arguments['immagine_id'] = $pdo->lastInsertId(); // legge id nuova immagine
                }
                unset($arguments['immagine_file'], $arguments['immagine_alt']); // Taglia dati immagine
                
                if($id){
                    $sql = "UPDATE articoli SET titolo = :titolo, sottotitolo = :sottotitolo, contenuto = :contenuto, categoria_id = :categoria_id, account_id = :account_id,
                    immagine_id = :immagine_id, latitudine = :latitudine, longitudine = :longitudine, pubblicato = :pubblicato
                    WHERE id = :id;";
                }else{
                    unset($arguments['id']);
                    $sql = "INSERT INTO articoli (titolo, sottotitolo, contenuto, categoria_id, account_id, immagine_id, latitudine, longitudine, pubblicato) VALUES (:titolo, :sottotitolo, :contenuto, :categoria_id, :account_id, :immagine_id, :latitudine, :longitudine, :pubblicato);";
                }               

                pdo($pdo, $sql, $arguments); //sql per aggiungere l'articolo
                $pdo->commit(); //commit delle modifiche
                redirect('menuPrincipale.php', ['success' => 'Articolo salvato']);
            
            } catch(PDOException $e){
                $pdo->rollBack();
                
                if(file_exists($destinazione)){
                    unlink($destinazione);
                }
                
                if($e->errorInfo[1] === 1062){
                    $errori['avviso'] = "Titolo dell'articolo già usato";
                }else{
                    throw $e;
                }
            }
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
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />        
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    </head>        
        <body class="container-fluid">
            <div class="row">
                <?php include "header.php"; ?>                   
                <section class ="col-lg-12 flex-wrap p-5">
                    <form action = "editArticolo.php?id=<?= $id ?>" enctype="multipart/form-data" method ="post" class="narrow">
                        <div class="row justify-content-center mt-2">
                            <div class="col-lg-4">
                                <h2>Inserisci la casa</h2>
                            </div>
                            <?php if($errori['avviso']) { ?>
                                <div class="alert alert-danger"><?= $errori['avviso'] ?></div>
                            <?php } ?>                                                       
                        </div>
                        <div class="row justify-content-between mt-5">
                            <div class="col-lg-4">
                                <?php if(!$articoli['immagine_file']) { ?>
                                        <h6>Upload Immagine: </h6> <input type="file" name="image" class="form-control-file btn-resp" id="immagine" required>
                                        <span class="errors"><?= $errori['immagine_file'] ?></span>                                        
                                        <hr>
                                        <h6>Alt Testo: </h6> <input type="text" class="form-control" name="immagine_alt" value="<?= html_escape($articoli['immagine_alt']) ?>" required>
                                        <span class="errors"><?= $errori['immagine_alt'] ?></span><br>
                                        <div class="my-5">
                                            <div id="map" style="width: 600px; height: 400px;"></div>
                                            <div id="coordinates">Clicca sulla mappa per ottenere le coordinate</div>                                                                                
                                        </div>                        
                                <?php } else { ?>
                                    <h6>Immagine: </h6>                                
                                    <img src="../simulazioneCms/img/<?= html_escape($articoli['immagine_file'])?>" style="border-radius:10px 10px 10px 10px; box-shadow: width:500px!important; height:380px;" alt ="<?= html_escape($articoli['immagine_alt'])?>">
                                    <p class="alt">
                                        <strong>
                                            Alt img:
                                        </strong>
                                        <?= html_escape($articoli['immagine_alt']) ?>
                                    </p>
                                    <div class="my-5">
                                        <div id="map" style="width: 600px; height: 400px;"></div>
                                        <div id="coordinates">Clicca sulla mappa per ottenere le coordinate</div>                                                                                
                                    </div>                                      
                                    <a href=""></a><!--sezione alt img modifica da fare-->
                                    <a href=""></a><!--sezione cancella immagine da fare-->
                                <?php } ?>
                            </div>
                            <div class="col-lg-5 mt-5"> 
                                <div class="mb-3">
                                    <h6>Titolo:</h6>
                                    <input type="text" class="form-control" style="max-width: 400px;" name="titolo" value="<?= html_escape($articoli['titolo']) ?>" required>
                                    <span class="errors text-danger"><?= $errori['titolo']?></span>
                                </div>
                                
                                <div class="mb-3">
                                    <h6>Sottotitolo:</h6>
                                    <input type="text" class="form-control" style="max-width: 600px;" name="sottotitolo" value="<?= html_escape($articoli['sottotitolo']) ?>" required>
                                    <span class="errors text-danger"><?= $errori['sottotitolo']?></span>
                                </div>
                                
                                <div class="mb-3">
                                    <h6>Contenuto:</h6>
                                    <textarea class="form-control" style="max-width: 600px; height:400px;" name="contenuto" required><?= html_escape($articoli['contenuto']) ?></textarea>
                                    <span class="errors text-danger"><?= $errori['contenuto']?></span>
                                </div>
                                
                                <div class="mb-3">
                                    <h6>Latitudine:</h6>
                                    <input type="text" class="form-control" style="max-width: 400px;" name="latitudine" id="latitudine" value="<?= $articoli['latitudine'] ?>" required>
                                    <span class="errors text-danger"><?= $errori['latitudine']?></span>
                                </div>
                                
                                <div class="mb-3">
                                    <h6>Longitudine:</h6>
                                    <input type="text" class="form-control" style="max-width: 400px;" name="longitudine" id="longitudine" value="<?= $articoli['longitudine'] ?>" required>
                                    <span class="errors text-danger"><?= $errori['longitudine']?></span>
                                </div>
                            </div> 
                            <div class="col-lg-3">                                 
                                <div>
                                    Autore:<br>
                                    <select name="account_id" class="form-control">
                                        <?php foreach($autori as $autore) {?>
                                            <option value ="<?= $autore['id'] ?>"<?= ($articoli['account_id'] == $autore['id']) ? 'selected' : '' ?>>
                                                <?= html_escape($autore['nome']. ' ' .$autore['cognome'])?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <span class="errors"><?= $errori['autore']?></span>
                                </div>                                
                                <div class="mt-3">
                                    Categorie: <br>                                   
                                    <select name="categoria_id"  style="width:300px;" class="form-control">
                                        <?php foreach($categorie as $categoria) {?>
                                            <option value ="<?= $categoria['id'] ?>"<?= ($articoli['categoria_id'] == $categoria['id']) ? 'selected' : '' ?>>
                                                <?= html_escape(strtoupper($categoria['nome']). ': ' .$categoria['descrizione'])?>
                                            </option>
                                        <?php } ?>                                        
                                    </select>
                                    <span class="errors"><?= $errori['categoria']?></span>
                                    <br>
                                </div>
                                <div class="mt-3">
                                    <input type="checkbox" name="pubblicato" value= "1" <?= ($articoli['pubblicato'] == 1) ? 'checked' : '' ?>> Pubblicato
                                </div>                                                              
                                    <input type="submit" name="salva" value="Salva" class="mt-5 btn btn-primary" onclick="return alert('Articolo Salvato')" >                                                                         
                                    <a href ="cancellaArticolo.php?id=<?= $id ?>" class="btn btn-danger" style="position:relative; top:1.5rem;">Elimina</a>                                                                                                                                                                                                                          
                                </div>   
                            </div>                                                                               
                        </div>                                                                                                                              
                    </form>
                </section>                                     
            </div>  
            <script>
                var map = L.map('map').setView([43.148718, 12.376099],5);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
                
                map.on('click', function(e) {
                    var lat = e.latlng.lat;
                    var lng = e.latlng.lng;
                    document.getElementById('coordinates').innerHTML = 
                        'Coordinate: ' + lat.toFixed(6) + ', ' + lng.toFixed(6);
                });

                 // 📍 Aggiunta di un marker statico
                    const marker = L.marker([<?=$articoli['latitudine']?>,<?=$articoli['longitudine']?>]).addTo(map);
                    marker.bindPopup("<b><?= $articoli['titolo']?></b><br><?= $articoli['sottotitolo']?>").openPopup();

                    var geocoder = L.Control.geocoder({
                        defaultMarkGeocode: false,  // Non aggiunge marker automaticamente
                        placeholder: 'Cerca un indirizzo...',
                        errorMessage: 'Indirizzo non trovato'
                    }).addTo(map);

                    // Gestisci il risultato della ricerca
                    geocoder.on('markgeocode', function(e) {
                        var latlng = e.geocode.center;
                        var address = e.geocode.name;
                        
                        // Centra la mappa sul risultato
                        map.setView(latlng, 15);
                        
                        // Aggiungi marker (opzionale)
                        L.marker(latlng).addTo(map)
                            .bindPopup(address)
                            .openPopup();
                        
                        // Le coordinate sono disponibili in: latlng.lat e latlng.lng
                        console.log('Coordinate trovate:', latlng.lat, latlng.lng);
                    });
            </script>                      
        </body>
</html>
