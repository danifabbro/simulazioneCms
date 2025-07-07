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
    <header>
        <div class="row">
            <div>

            </div>            
            <div class="col-lg-3 text-center mt-3 mb-5">
                <h1> <a href="https://danielfabretti.hopto.org/simulazioneCms/menuPrincipale.php" class="h1-menu-gr "> EASY HOUSE </a></h1>
            </div>            
            <div class="col-lg-9 mt-3 mb-5">
                <nav>
                    <ul style="display:flex; margin:2rem;">
                        <?php foreach($navigazione as $link){ ?>
                            <li style="list-style-type:none; margin-right:2rem;" class="list-nav">
                                <a class="btn-resp" style="padding:0.5rem; text-decoration:none;" href="categoria.php?id=<?= $link['id']?>"
                                    <?= ($sezione == $link['id'] ) ? 'class="on" aria-current="page" ' : '' ?>>
                                    <?= html_escape($link['nome']) ?>
                                </a>
                            </li>                                
                        <?php } ?>
                        <li style = "list-style-type:none;">
                            <a href ="editArticolo.php" class="btn-resp" style="margin-left:3rem; padding:0.5rem; text-decoration:none;">Nuova casa</a>
                        </li>                        
                        <li style = "list-style-type:none;">
                            <a href ="editCategorie.php" class="btn-resp" style="margin-left:3rem; padding:0.5rem; text-decoration:none;">Nuova categoria</a>
                        </li>                        
                        <li style = "list-style-type:none;">
                            <a href ="editImmagini.php" class=" btn-resp" style="margin-left:3rem; padding:0.5rem; text-decoration:none;">Galleria immagini</a>
                        </li>
                       &nbsp
                       &nbsp
                       &nbsp
                       &nbsp
                        <div>
                            <li style="list-style-type:none;" class="cerca-btn">                          
                                <a href = "cerca.php" style="color:#000;">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </a>
                            </li>
                        </div>                                         
                    </ul>
                <nav>                
            </div>            
        </div>        
    </header>
</html>    
