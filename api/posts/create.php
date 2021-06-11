<?php
// Headers requis
// Accès depuis n'importe quel site ou appareil (*)
header("Access-Control-Allow-Origin: *");

// Format des données envoyées
header("Content-Type: application/json; charset=UTF-8");

// Méthode autorisée
header("Access-Control-Allow-Methods: POST");

// Durée de vie de la requête
header("Access-Control-Max-Age: 3600");

// Entêtes autorisées
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
     // La bonne méthode est utilisée
     // On inclut les fichiers de configuration et d'accès aux données
     include_once '../config/Database.php';
     include_once '../models/Posts.php';

     // On instancie la base de données
     $database = new Database();
     $db = $database->getConnection();

     // On instancie les posts
     $post = new Posts($db);

     // On récupère les données reçues
     $data = json_decode(file_get_contents("php://input"));

     // On vérifie qu'on a bien toutes les données
     if(!empty($data->content) && !empty($data->user_id) && !empty($data->topic_id)){

     }
     // On hydrate notre objet
     $post->content = $data->content;
     $post->user_id = $data->user_id;
     $post->topic_id = $data->topic_id;

     if($post->create()){
          // Ici la création a fonctionné
          // On envoie un code 201
          http_response_code(201);
          echo json_encode(["message" => "L'ajout a été effectué"]); 
     }else{
          // Ici la création n'a pas fonctionné
          // On envoie un code 503
          http_response_code(503);
          echo json_encode(["message" => "L'ajout n'a pas été effectué"]);         
     }

}else{
     // Mauvaise méthode, on gère l'erreur
     http_response_code(405);
     echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}