<?php
namespace gui;
include_once "View.php";

class ViewInscription extends View
{
    public function __construct($layout)
    {
        parent::__construct($layout);

        $this->title = 'Inscription';
        $this->content = '
            <form method="post" action="/annonces/index.php/inscription">
                <label for="login">Login :</label>
                <input type="text" name="login" id="login" required><br>
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password" required><br>
                <label for="nom">Nom :</label>
                <input type="text" name="nom" id="nom" required><br>
                <label for="prenom">Prénom :</label>
                <input type="text" name="prenom" id="prenom" required><br>
                <input type="submit" value="Sinscrire">
            </form>';
    }
}
?>
