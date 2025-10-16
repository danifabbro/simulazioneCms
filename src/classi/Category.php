<?php
    Class Category{
        
        //------- PRENDERE CONNESIONE AL DATABASE

        protected $db;

        public function __construct(Database $db) // la classe Database viene riconosciuta grazie alla funzione autoloader di bootstrap.php
        {
            $this->db = $db; // il database che riceve la classe sarà nella proprietà oggetto della classe.
        } 

        //---- PRENDERE LE INFO SULLE CATEGORIE ESISTENTI NEL DATABASE---------

        public function get(int $id)
        {
            $sql = "SELECT id,nome,descrizione,navigazione FROM categoria WHERE id = :id;";
            return $this->db->runSQL($sql,[$id])->fetch();    
        }

        public function getAll(): array
        {
            $sql = "SELECT id, nome, navigazione FROM categoria;";
            return $this->db->runSQL($sql)->fetchAll();
        }

        public function count(): int
        {
            $sql = "SELECT COUNT(id) FROM categoria;";
            return $this->db->runSQL($sql)->fetchColumn();
        }


        //------ CREARE UNA CATEGORIA-------

        public function create(array $category): bool
        {
            try{
                $sql = "INSERT INTO categoria (nome, descrizione, navigazione) VALUES (:nome, :descrizione , :navigazione);";
                $this->db->runSQL($sql, $category);
                return true;
            }catch(PDOException $e){
                if($e->errorInfo[1] === 1062){
                    return false;
                }else{
                    throw $e;
                }
            }
        }


        //-----MODIFICARE UNA CATEGORIA-------

        

    }
?>