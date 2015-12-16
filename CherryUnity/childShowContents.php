<?php session_start() ?>
<!doctype html>
<html>
    
<head>
    <?php include 'head.php' ?>
    <title>Content </title>
</head>

<body>
    <?php include 'nav.php' ?>
    <h1>Gestion de contenus de l'enfant</h1>
    
    <?php    
    $root = './';
    include "includes.php";
    
    $contentDao = new ContentDAO(DynamoDbClientBuilder::get());
    $childDao = new ChildDAO(DynamoDbClientBuilder::get());
    $email = $_SESSION['email'];
    $child = $childDao->get($email);
       
    // URL parameter
    // parameter 'type', possible values : {teacher, family, doctor}
    $type = $_GET['type'];
    
    if($type == 'teacher') {
        $contents = $child->getTeachingContent();
    } else if ($type == 'family') {
        $contents = $child->getFamilyContent();
    } else if ($type == 'doctor') {
        $contents = $child->getMedicalContent();
    }
        
    $length = count($contents);
    
    echo '<ul>';
    for ($i = 0; $i < $length; $i++) {
        $contentInfo = $contents[$i];
        $name = $contentInfo['M']['name']['S'];
        $owner = $contentInfo['M']['owner']['S'];
        $date = $contentInfo['M']['date']['S'];
        $content = $contentDao->get($name, $owner);
        if ($content != null) {
            echo '<li>'. '<a href=downloadFile.php?name='.$name.'>'.$name.'</a>' .'</li>';
        }
        /*
         * 
         * Considérer que l'enfant a lu les fichiers listés ici
         * passer notified à 1
         * 
         * 
         * Aussi, vérifier que la date a bien été dépassé avant
         * de proposer le contenu
         */
    }
    echo '</ul>';
    ?>
    
    <?php include 'footer.php' ?>

</body>
</html>