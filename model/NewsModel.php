<?php

function getAllNewsHomePage(PDO $connect): array|string
{
    $sql = "SELECT n.title, n.slug, SUBSTRING(n.content, 1, 280) AS content, n.date_published, 
                   u.login, u.thename, 
            GROUP_CONCAT(c.title SEPARATOR ' | ') AS categ_title, GROUP_CONCAT(c.slug SEPARATOR ' | ') AS categ_slug
            
	FROM news n
	LEFT JOIN user u
		ON n.user_iduser = u.iduser
    LEFT JOIN news_has_category nhc
        ON nhc.news_idnews = n.idnews
    LEFT JOIN category c
        ON nhc.category_idcategory = c.idcategory
       

-- Condition de récupération
    WHERE n.is_published = 1
-- on groupe par la clef de la table du FROM (news)
    GROUP BY n.idnews
    ORDER BY n.date_published DESC

        ";

    try{
    
    $query = $connect->query($sql);

    // si pas de résultats () : string
    if(!$query->rowCount()) return "Pas encore de message";
    
    $result = $query->fetchAll();

    $query->closeCursor();

    return $result; // : array

    }catch(Exception $e){
        return $e->getMessage(); // : string
    }

}

function cutTheText(string $text, int $nbCharacter=200, bool $cutWord= false): string
{
    // on coupe à la longueure indiquée
    $output = substr($text,0,$nbCharacter);
    // si on ne souhaite pas couper dans un mot 
    if($cutWord===false){
        // on prend la position du dernier espace dans le texte(chaine)  
        $lastSpace = stripos(" ", $output);
        // on recoupe la chaine pour l'envoi
        $output = substr($output,0,$lastSpace);

    }
    return $output;
}