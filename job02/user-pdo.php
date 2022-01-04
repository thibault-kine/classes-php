<?php
class UserPDO
{
    // ATTRIBUTS
    private $id;
    public $login;
    public $password;
    public $email;
    public $firstname;
    public $lastname;

    // METHODE CONSTRUCT (équivalent de __init__() en python)
    public function __construct()
    {
        $db = "mysql:dbname=classes;host=localhost;";

        try 
        {
            $this->bdd = new PDO($db, "root", "");
            $this->bdd->exec("SET NAMES utf8");
            $this->bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,  PDO::FETCH_ASSOC);
        } 
        catch (PDOException $e) 
        {
            die("Erreur de connexion a la base:".$e->getMessage());
        }
        return $this->bdd;
    }

    // METHODES
    public function register($login, $password, $email, $firstname, $lastname)
    {
        $selectQuery = "SELECT * FROM utilisateurs WHERE login='$login'";

        $prepQuery = $this->bdd->prepare($selectQuery);

        //ON INJECTE LES VALEURS AVEC LA FONCTION "bindValue"
        //PDO::PARAM_STR Pour dire que notre paramètre est une chaine de caractères
        $prepQuery->bindValue(":login", $login, PDO::PARAM_STR);
        $prepQuery->execute();

        $select = $prepQuery->fetchAll();

        if (count($select) > 0) 
        {
            return "Ce login existe déjà, choisissez-en un autre.";
        } 
        else 
        {
            $insertQuery = "INSERT INTO `utilisateurs`(`login`, `password`, `email`, `firstname`, `lastname`) VALUES ('$login', '$password', '$email', '$firstname', '$lastname')";

            //ON PREPARE LA REQUETE
            $prepQuery = $this->bdd->prepare($insertQuery);

            //ON INJECTE LES VALEURS AVEC LA FONCTION "bindValue"
            //PDO::PARAM_STR Pour dire que notre paramètre est une chaine de caractères
            $prepQuery->bindValue(":login", $login, PDO::PARAM_STR);
            $prepQuery->bindValue(":password", $password, PDO::PARAM_STR);
            $prepQuery->bindValue(":email", $email, PDO::PARAM_STR);
            $prepQuery->bindValue(":firstname", $firstname, PDO::PARAM_STR);
            $prepQuery->bindValue(":lastname", $lastname, PDO::PARAM_STR);

            //ON EXECUTE LA REQUETE
            $prepQuery->execute();
        }
    }

    public function connect($login, $password)
    {
        $selectQuery = "SELECT * FROM `utilisateurs` WHERE login='$login' AND password='$password'";

        //ON PREPARE LA REQUETE
        $requete = $this->bdd->prepare($selectQuery);

        //ON INJECTE LES VALEURS AVEC LA FONCTION "bindValue"
        //PDO::PARAM_STR Pour dire que notre paramètre est une chaine de caractères
        $requete->bindValue(":login1", $login, PDO::PARAM_STR);
        $requete->bindValue(":password1", $login, PDO::PARAM_STR);

        //ON EXECUTE LA REQUETE
        $requete->execute();

        $utilisateur = $requete->fetch();

        if (count($utilisateur) > 0) {

            $_SESSION['user_connect'] = [
                "id" => $utilisateur["id"],
                "login" => $utilisateur["login"],
                "password" => $utilisateur["password"],
                "email" => $utilisateur["email"],
                "firstname" => $utilisateur["firstname"],
                "lastname" => $utilisateur["lastname"]

            ];

            $this->id = $utilisateur["id"];
            $this->login = $login;
            $this->email = $utilisateur["email"];
            $this->firstname = $utilisateur["firstname"];
            $this->lastname = $utilisateur["lastname"];
        }
        else 
        {
            echo 'Le login ou le mot de passe est incorrect';
        }
    }


    public function disconnect()
    {
        unset($_SESSION['user_connect']);
    }

    public function delete()
    {
        $deleteQuery = "DELETE FROM `utilisateurs` WHERE id='$this->id'";

        //ON PREPARE LA REQUETE
        $requete = $this->bdd->prepare($deleteQuery);

        //ON INJECTE LES VALEURS AVEC LA FONCTION "bindValue"
        //PDO::PARAM_STR Pour dire que notre paramètre est une chaine de carractère
        $requete->bindValue(":id", $this->id, PDO::PARAM_STR);

        //ON EXECUTE LA REQUETE
        $requete->execute();

        unset($_SESSION['user_connect']);
    }

    public function update($login, $password, $email, $firstname, $lastname)
    {
        $updateQuery = "UPDATE `utilisateurs` SET `login`='$login',`password`='$password',`email`='$email',`firstname`='$firstname',`lastname`='$lastname'";

        //ON PREPARE LA REQUETE
        $requete = $this->bdd->prepare($updateQuery);

        //ON INJECTE LES VALEURS AVEC LA FONCTION "bindValue"
        //PDO::PARAM_STR Pour dire que notre paramètre est une chaine de carractère
        $requete->bindValue(":login", $login, PDO::PARAM_STR);
        $requete->bindValue(":password", $password, PDO::PARAM_STR);
        $requete->bindValue(":email", $email, PDO::PARAM_STR);
        $requete->bindValue(":firstname", $firstname, PDO::PARAM_STR);
        $requete->bindValue(":lastname", $lastname, PDO::PARAM_STR);

        //ON EXECUTE LA REQUETE
        $requete->execute();

        $this->login = $login;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;

        $_SESSION['user_connect']['login'] = $login;
        $_SESSION['user_connect']['password'] = $password;
        $_SESSION['user_connect']['email'] = $email;
        $_SESSION['user_connect']['firstname'] = $firstname;
        $_SESSION['user_connect']['lastname'] = $lastname;
    }

    public function isConnected()
    {
        if (!empty($_SESSION['user_connect'])) 
        {
            return true;
        } 
        else 
        {
            return false;
        }
    }

    // GETTERS
    public function getAllInfos()
    {
        $userInfo = [
            "id" => $this -> id,
            "login" => $this -> login,
            "password" => $this -> password,
            "email" => $this -> email,
            "firstname" => $this -> firstname,
            "lastname" => $this -> lastname
        ];

        return $userInfo;
    }
    
    public function getLogin()
    {
        return $this -> login;
    }

    public function getEmail()
    {
        return $this -> email;
    }

    public function getFirstname()
    {
        return $this -> firstname;
    }

    public function getLastname()
    {
        return $this -> lastname;
    }
}
?>