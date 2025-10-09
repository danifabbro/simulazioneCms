<?php
    // la classe CMS è composta inizialmente dalla funzione magica construtta che prende i dati del database e li immagazzianiamo per eseguirli nelle classi get
    class CMS
    {
        protected $db = null;
        protected $article = null, 
        protected $category = null;
        protected $member = null;
        
        
        public function __construct($dsn,$username,$password)
        {
            $this->db = new Database($dsn,$username,$password);
        }

        public function getArticle()
        {
            if($this->article === null){
                $this->article = new Article($this->db);
            }
            return $this->article;
        }

        public function getCategory()
        {
            if($this->category === null){
                $this->category = new Category($this->db)
            }
        }

        public function getMember()
        {
            if($this->member === null){
                $this->member = new Member($this->db)
            }
        }
    }
?>