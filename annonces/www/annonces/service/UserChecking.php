<?php

namespace service;

class UserChecking
{
    //verifie si l'utilisateur est dans la base de donnees
    public function authenticate($login, $password, $data)
    {
        return ($data->getUser($login, $password) != null);
    }

    //verifie si le login est deja pris
    public function checkLogin($login, $data)
    {
        return ($data->getUser($login, null) != null);
    }

    //verifie si le mot de passe est valide
    public function checkPassword($password)
    {
        return (strlen($password) >= 6);
    }
    


}