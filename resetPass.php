<?php

require "./includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Password reset";

    protected function body(): string
    {
        $stmt = $this -> pdo->prepare("SELECT login, password FROM employee WHERE login=:login");
        $stmt->bindParam(':login', $_SESSION["user"]);
        $stmt->execute();

        $passs = $stmt->fetch()->password;

        if (password_verify($_POST["pass"],$passs)){
            $passss = password_hash(filter_input(INPUT_POST, "newPass"), PASSWORD_BCRYPT);
            $user = $_SESSION["user"];
            $stmt = $this -> pdo->prepare("UPDATE employee SET password =:passss WHERE login=:login");
            $stmt->bindParam(':login', $user);

            $stmt->bindParam(':passss', $passss);
            $stmt->execute();
            header("location: index.php");
        }
        else{
            return $this->m->render("passFail");
        }


        return "";
    }
}

(new CurrentPage())->render();
