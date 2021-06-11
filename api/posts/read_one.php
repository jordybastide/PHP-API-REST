<?php
// Headers requis
// Accès depuis n'importe quel site ou appareil (*)
header("Access-Control-Allow-Origin: *");

// Format des données envoyées
header("Content-Type: application/json; charset=UTF-8");

// Méthode autorisée
header("Access-Control-Allow-Methods: GET");

// Durée de vie de la requête
header("Access-Control-Max-Age: 3600");

// Entêtes autorisées
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == 'GET'){
     // La bonne méthode est utilisée
     // On inclut les fichiers de configuration et d'accès aux données
     include_once '../config/Database.php';
     include_once '../models/Posts.php';

     // On instancie la base de données
     $database = new Database();
     $db = $database->getConnection();

     // On instancie les posts
     $post = new Posts($db);

     $data = json_decode(file_get_contents("php://input"));

     if (!empty($data->id)) {
          $post->id = $data->id;
          // On récupère les données
          $post->readOne();
     
          // On vérifie si le post existe
          if($post->id != null){
               $postData = [
                    "id" => $post->id,
                    "post_date" => $post->post_date,
                    "content" => $post->content,
                    "user_id" => $post->user_id,
                    "topic_id" => $post->topic_id
               ];
               // On envoie le code réponse 200 OK
               http_response_code(200);
     
               // On encode en json et on envoie
               echo json_encode($postData);
          }else{
               // Mauvaise méthode, on gère l'erreur
               http_response_code(404);
               echo json_encode(["message" => "Le post n'existe pas"]);
          }
     }

}else{
     // Mauvaise méthode, on gère l'erreur
     http_response_code(405);
     echo json_encode(["message" => "Lea méthode n'est pas autorisée"]);
}