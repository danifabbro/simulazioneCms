<?php
    class Article
    {
        protected $db

        public function __construct(Database $db) // la classe Database viene riconosciuta grazie alla funzione autoloader di bootstrap.php
        {
            $this->db = $db; // il database che riceve la classe sarà nella proprietà oggetto della classe.
        }

        public function get(int $id, bool $published = true)
        {
            $sql = "SELECT art.id, art.titolo, art.sottotitolo, art.contenuto, art.categoria_id, 
                            art.account_id, art.pubblicato,
                            c.nome AS categoria,
                            CONCAT(a.nome,' ',a.cognome) AS autore,
                            i.id AS immagine_id,
                            i.file AS immagine_file,
                            i.alt AS immagine_alt
                    FROM articoli AS art
                    JOIN categoria AS c ON art.categoria_id = c.id
                    JOIN account AS a ON art.account_id = a.id
                    LEFT JOIN immagini AS i ON art.immagine_id = i.id;";
        }
            if($published){
                $sql .= "AND pubblicato = 1;";
            }
            return $this->db->runSQL();
    }
?>