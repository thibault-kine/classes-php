<?php
class User
{
    // ATTRIBUTS
    private $id;
    public $login;
    public $password;
    public $email;
    public $firstname;
    public $lastname;

    // METHODE CONSTRUCT (équivalent de __init__() en python)
    public function __construct($login, $password, $email, $firstname, $lastname)
    {
        $this -> login = $login;
        $this -> password = $password;
        $this -> email = $email;
        $this -> firstname = $firstname;
        $this -> lastname = $lastname;
    }

    // METHODES
    public function register($login, $password, $email, $firstname, $lastname)
    {
        // connexion a la base de données
        $db = mysqli_connect("localhost", "root", "", "classes");

        $selectQuery = "SELECT * FROM utilisateurs WHERE 
        login='$login' AND password='$password' AND email='$email' AND firstname='$firstname'
        AND lastname='$lastname'";
        $result = mysqli_query($db, $selectQuery);
        $fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // vérification : il y a t il un autre utilisateur correspondant ?
        if(empty($fetch))
        {
            $insertQuery = "INSERT INTO utilisateurs(login, password, email, firstname, lastname)
            VALUES ('$login', '$password', '$email', '$firstname', '$lastname')";
            mysqli_query($db, $insertQuery);
            $result = mysqli_query($db, $selectQuery);
            $fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);

            $this -> id = $fetch[0]["id"];

            return $fetch[0];
        }
        else
        {
            return 0;
        }
    }

    public function connect($login, $password)
    {
        // connexion a la base de données
        $db = mysqli_connect("localhost", "root", "", "classes");

        $selectQuery = "SELECT * FROM utilisateurs WHERE 
        login='$login' AND password='$password'";
        $result = mysqli_query($db, $selectQuery);
        $fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
        // vérification : il y a t il un autre utilisateur correspondant ?
        if(!empty($fetch))
        {
            // il y a t il déjà une session ouverte ?
            session_start();
            if(isset($_SESSION))
            {
                unset($_SESSION);
                session_destroy();
            }

            // initialise toutes les variables de session
            $_SESSION["login"] = $this -> login;
            $_SESSION["password"] = $this -> password;
            $_SESSION["email"] = $this -> email;
            $_SESSION["firstname"] = $this -> firstname;
            $_SESSION["lastname"] = $this -> lastname;
            
            $this -> id = $fetch[0]["id"];
            $_SESSION["id"] = $this -> id;
        }
        else
        {
            return 0;
        }
    }

    public function disconnect()
    {
        // il y a t il déjà une session ouverte ?
        session_start();
        if(isset($_SESSION))
        {
            unset($_SESSION);
            session_destroy();
        }
    }

    public function delete()
    {
        $id = $this -> id;

        $db = mysqli_connect("localhost", "root", "", "classes");
        $deleteQuery = "DELETE FROM utilisateurs WHERE id='$id'";
        mysqli_query($db, $deleteQuery);

        $selectQuery = "SELECT * FROM utilisateurs WHERE id='$id'";
        $result = mysqli_query($db, $selectQuery);
        $fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // si $fetch n'a rien trouvé, alors l'utilisateur n'existe plus : la session peut être supprimée
        if(empty($fetch))
        {
            // il y a t il déjà une session ouverte ?
            session_start();
            if(isset($_SESSION))
            {
                unset($_SESSION);
                session_destroy();
            }
        }
    }

    public function update($login, $password, $email, $firstname, $lastname)
    {
        $id = $this -> id;
        $db = mysqli_connect("localhost", "root", "", "classes");
        $selectQuery = "SELECT * FROM utilisateurs WHERE id='$id'";
        $result = mysqli_query($db, $selectQuery);
        $fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);

        if(!empty($fetch))
        {
            $updateQuery = "UPDATE utilisateurs SET
            login='$login', password='$password', email='$email'
            firstname='$firstname', lastname='$lastname' WHERE id='$id'";
            $result = mysqli_query($db, $updateQuery);
            $fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);

            // réassigne les attributs ET les variables de session
            // login
            $this -> login = $fetch[0]["login"];
            $_SESSION["login"] = $this -> login;
            // mot de passe
            $this -> password = $fetch[0]["password"];
            $_SESSION["password"] = $this -> password;
            // email
            $this -> email = $fetch[0]["email"];
            $_SESSION["email"] = $this -> email;
            // prénom
            $this -> firstname = $fetch[0]["firstname"];
            $_SESSION["firstname"] = $this -> firstname;
            // nom de famille
            $this -> lastname = $fetch[0]["lastname"];
            $_SESSION["lastname"] = $this -> lastname;
        }
        else
        {
            return 0;
        }
    }

    public function isConnected()
    {
        $id = $this -> id;

        if(isset($_SESSION))
        {
            if($_SESSION["id"] == $id)
            {
                return true;
            }
            else
            {
                return false;
            }
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