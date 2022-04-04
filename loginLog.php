<?php
require "./includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Login";

    protected function body(): string
    {
        $stmt = $this -> pdo->prepare("SELECT * FROM employee WHERE login=:login");
        $stmt->bindParam(':login', $_POST["login"]);
        $stmt->execute();

        $fetch = $stmt->fetch();
        $passs = $fetch->password;

        if (password_verify($_POST["pass"],$passs)){
            $_SESSION["loggedin"] = true;
            $_SESSION["user"] = $_POST["login"];

            if ($fetch->admin){
                $_SESSION["admin"] = true;
            }
//            else{
//                $_SESSION["admin"] = false;
//            }
            header("location: index.php");
        }
        else{
            header("location: login.php");

        }


        return "";
    }
}

(new CurrentPage())->render();
