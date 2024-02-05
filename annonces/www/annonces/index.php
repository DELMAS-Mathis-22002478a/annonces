<?php

// charge et initialise les bibliothèques globales
include_once 'data/DataAccess.php';
include_once 'control/Controllers.php';
include_once 'control/Presenter.php';
include_once 'service/AnnoncesChecking.php';

include_once 'gui/Layout.php';
include_once 'gui/ViewLogin.php';
include_once 'gui/ViewAnnonces.php';
include_once 'gui/ViewPost.php';

use control\{Controllers, Presenter};
use data\DataAccess;
use gui\{Layout, ViewAnnonces, ViewLogin, ViewPost};
use service\AnnoncesChecking;


$data = null;
try {
    // construction du modèle
    $data = new DataAccess( new PDO('mysql:host=mysql-archi-reseau.alwaysdata.net; dbname=archi-reseau_bd', '344794_mathis', 'TD-2024') );

} catch (PDOException $e) {
    print "Erreur de connexion !: " . $e->getMessage() . "<br/>";
    die();
}

// initialisation du controller
$controller = new Controllers();

// intialisation du cas d'utilisation AnnoncesChecking
$annoncesCheck = new AnnoncesChecking() ;

// intialisation du presenter avec accès aux données de AnnoncesCheking
$presenter = new Presenter($annoncesCheck);

// chemin de l'URL demandée au navigateur
// (p.ex. /annonces/index.php)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// route la requête en interne
// i.e. lance le bon contrôleur en focntion de la requête effectuée
if ( '/annonces/' == $uri || '/annonces/index.php' == $uri) {

    $layout = new Layout("gui/layout.html" );
    $vueLogin = new ViewLogin( $layout );

    $vueLogin->display();
}
elseif ( '/annonces/index.php/annonces' == $uri
    && isset($_POST['login']) && isset($_POST['password']) ){

    $controller->annoncesAction($_POST['login'], $_POST['password'], $data, $annoncesCheck);

    $layout = new Layout("gui/layout.html" );
    $vueAnnonces= new ViewAnnonces( $layout, $_POST['login'], $presenter);

    $vueAnnonces->display();
}
elseif ( '/annonces/index.php/post' == $uri
    && isset($_GET['id'])) {

    $controller->postAction($_GET['id'], $data, $annoncesCheck);

    $layout = new Layout("gui/layout.html" );
    $vuePost= new ViewPost( $layout, $presenter );

    $vuePost->display();
}
// Ajout d'une condition pour gérer l'URL de la page d'inscription
elseif ('/annonces/index.php/inscription' == $uri) {
    // Si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données du formulaire
        $login = $_POST['login'];
        $password = $_POST['password'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];

        // Vérifier si les informations sont valides
        // Vérification supplémentaire peut être ajoutée pour la robustesse
        if (!empty($login) && !empty($password) && !empty($nom) && !empty($prenom)) {
            // Enregistrer l'utilisateur dans la base de données
            // Assure-toi d'utiliser des requêtes préparées pour éviter les injections SQL
            $stmt = $data->prepare("INSERT INTO Users (login, password, nom, prenom, date_creation) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$login, $password, $nom, $prenom]);
            // Rediriger vers une page de confirmation ou autre page appropriée
        } else {
            // Rediriger vers la page d'inscription avec un message d'erreur
            header("Location: /annonces/index.php/inscription?error=1");
            exit();
        }
    } else {
        // Afficher la vue d'inscription
        $layout = new Layout("gui/layout.html");
        $vueInscription = new ViewInscription($layout);
        $vueInscription->display();
    }
}

else {
    header('Status: 404 Not Found');
    echo '<html><body><h1>My Page NotFound</h1></body></html>';
}

?>
