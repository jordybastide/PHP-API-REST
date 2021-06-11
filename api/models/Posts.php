<?php

class Posts{
     // Connexion
     private $connexion;
     private $table = "post"; // Table dans la base de données

     // Propriétés
     public $id;
     public $post_date;
     public $content;
     public $user_id;
     public $topic_id;

     /**
      * Constructeur avec $db pour la connexion à la base de données
      *
      * @param $db
      */
     public function __construct($db){
          $this->connexion = $db;
     }

     /**
      * Lecture des posts
      *
      * @return void
      */
     public function read(){
          // On écrit la requête
          $sql = "SELECT t.id as topic_id, p.id, p.post_date, p.content, p.user_id FROM " . $this->table . " p LEFT JOIN topic t ON p.topic_id = t.id ORDER BY p.post_date DESC";
     
          // On prépare la requête
          $query = $this->connexion->prepare($sql);
     
          // On exécute la requête
          $query->execute();
     
          // On retourne le résultat
          return $query;
     }

     /**
      * Lire un post
      *
      * @return void
      */
     public function readOne(){
          // On écrit la requête
          $sql = "SELECT t.id as topic_id, p.id, p.post_date, p.content, p.user_id FROM " . $this->table . " p LEFT JOIN topic t ON p.topic_id = t.id WHERE p.id = ? LIMIT 0,1";
     
          // On prépare la requête
          $query = $this->connexion->prepare( $sql );
     
          // On attache l'id
          $query->bindParam(1, $this->id);
     
          // On exécute la requête
          $query->execute();
     
          // on récupère la ligne
          $row = $query->fetch(PDO::FETCH_ASSOC);
     
          // On hydrate l'objet
          $this->id = $row['id'];
          $this->post_date = $row['post_date'];
          $this->content = $row['content'];
          $this->user_id = $row['user_id'];
          $this->topic_id = $row['topic_id'];
     }

     /**
      * Créer un post
      *
      * @return void
      */
     public function create(){

          // Ecriture de la requête SQL en y insérant le nom de la table
          $sql = "INSERT INTO " . $this->table . " SET content=:content, user_id=:user_id, topic_id=:topic_id";
     
          // Préparation de la requête
          $query = $this->connexion->prepare($sql);
     
          // Protection contre les injections
          $this->content=htmlspecialchars(strip_tags($this->content));
          $this->user_id=htmlspecialchars(strip_tags($this->user_id));
          $this->topic_id=htmlspecialchars(strip_tags($this->topic_id));
     
          // Ajout des données protégées
          $query->bindParam(":content", $this->content);
          $query->bindParam(":user_id", $this->user_id);
          $query->bindParam(":topic_id", $this->topic_id);
     
          // Exécution de la requête
          if($query->execute()){
               return true;
          }
          return false;
     }

     /**
      * Mettre à jour un post
      *
      * @return void
      */
     public function update(){
          // On écrit la requête
          $sql = "UPDATE " . $this->table . " SET content=:content, user_id=:user_id, topic_id=:topic_id WHERE id = :id";
          
          // On prépare la requête
          $query = $this->connexion->prepare($sql);
          
          // On sécurise les données
          $this->content=htmlspecialchars(strip_tags($this->content));
          $this->user_id=htmlspecialchars(strip_tags($this->user_id));
          $this->topic_id=htmlspecialchars(strip_tags($this->topic_id));
          $this->id=htmlspecialchars(strip_tags($this->id));
          
          // On attache les variables
          $query->bindParam(":content", $this->content);
          $query->bindParam(":user_id", $this->user_id);
          $query->bindParam(":topic_id", $this->topic_id);
          $query->bindParam(':id', $this->id);
          
          // On exécute
          if($query->execute()){
               return true;
          }
          
          return false;
     }

     /**
      * Supprimer un post
      *
      * @return void
      */
     public function delete(){
          // On écrit la requête
          $sql = "DELETE FROM " . $this->table . " WHERE id = ?";
     
          // On prépare la requête
          $query = $this->connexion->prepare( $sql );
     
          // On sécurise les données
          $this->id=htmlspecialchars(strip_tags($this->id));
     
          // On attache l'id
          $query->bindParam(1, $this->id);
     
          // On exécute la requête
          if($query->execute()){
          return true;
          }
          
          return false;
     }
}