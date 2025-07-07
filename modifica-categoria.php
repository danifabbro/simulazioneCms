<?php
    require "funzioni.php";
    require "connessioneDb.php";
         
    $id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
    
    $categoria = [
        'id'=> $id,
        'nome'=> '',
        'descrizione'=>'',
        'navigazione'=>false,
    ];

    $errori = [
        'avviso' => '',
        'nome' => '',
        'descrizione' => ''
    ];

    if($id){
        $sql = "SELECT id, nome, descrizione, navigazione FROM categoria WHERE id = :id ;";
        $categorie = pdo($pdo,$sql,[$id])->fetch();
        if(!$categorie)
        {
            redirect('editCategorie.php', ['failure' => 'Categoria non trovata']);
        }
        $categoria['nome'] = $categorie['nome'];
        $categoria['descrizione'] = $categorie['descrizione'];
    }
      
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $categoria['nome'] = $_POST['nome'];
            $categoria['descrizione'] = $_POST['descrizione'];
            $categoria['navigazione'] = (isset($_POST['navigazione']) and ($_POST['navigazione'] == 1 )) ? 1 : 0;
        
        $errori['nome'] = (is_text($categoria['nome'], 1,24)) ? '' : 'Il nome deve essere compreso tra gli 1 e i 24 caratteri.';
        $errori['descrizione'] = (is_text($categoria['descrizione'],1,254)) ? '' : 'La descrizione deve essere compresa tra gli 1 e i 254 caratteri';

        $invalido = implode($errori);

        if($invalido)
        {
            $errori['avviso'] = 'Per favore correggi gli errori';
        }else{
            $arguments = $categoria; // imposta nell'array arguments del PDO i valori della variabile categoria
            if($id) // se c'è un id
            {
                $sql = "UPDATE categoria SET nome = :nome, descrizione = :descrizione, navigazione = :navigazione WHERE id = :id;";
            }else{
                unset($arguments['id']);
                $sql = "INSERT INTO categoria (nome, descrizione, navigazione) VALUES (:nome, :descrizione, :navigazione);";
            }
        
            try{
                pdo($pdo,$sql,$arguments);
                redirect('editCategorie.php',['success'=>'Categoria salvata con successo!']);
            }catch(PDOException $e){
                if($e->errorInfo[1] === 1062){
                    $errori['avviso'] = 'Nome della categoria già in utilizzo';
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
    </head>        
        <body class="container-fluid">
            <div class="row">
                <?php include "header.php"; ?> 
                <section class="col-md-2">
                </section>   
                <section class ="col-md-8">
                    <form action = "modifica-categoria.php?id=<?= $id ?>" method ="post" class="narrow">
                        <h2>Modifica o aggiungi categoria</h2>
                        
                        <?php if($errori['avviso']) { ?>
                            <div class="alert alert-danger">
                                <?= $errori['avviso'] ?>
                            </div>
                        <?php } ?>

                        <div class = "form-group">
                            <label for="nome">Nome: </label>
                            <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($categoria['nome'])?>" class="form-control">
                            <span><?= $errori['nome']?></span>
                        </div>

                        <div class="form-group">
                            <label for="descrizione">Descrizione: </label>
                            <textarea style ="height:30vh;" name="descrizione"  class="form-control" id="descrizione"><?= htmlspecialchars($categoria['descrizione'])?></textarea>
                            <span><?= $errori['descrizione']?></span>
                        </div>

                        <div class="form-check">
                            <input type ="checkbox" name="navigazione" id="navigazione" value ="1" class="form-check-input" 
                            <?= ($categoria['navigazione']  === 1 )   ? 'checked' : ''?>>
                            <label class="form-check-label" for = "navigazione">Immetti nel menu la categoria</label>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4">
                                <input type ="submit" value="salva" class="btn btn-primary btn-save">
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>                        
                    </form>
                </section>
                <section class="col-md-2">
                </section>                       
            </div>                        
        </body>
</html>