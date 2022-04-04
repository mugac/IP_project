<?php

require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Password change";
    protected function body(): string
    {
//        $stmt = $this -> pdo->prepare("SELECT login, password FROM employee WHERE id=id");
//        $stmt->bindParam(':id', $_POST["id"]);
//        $stmt->execute([]);
            $passss = password_hash(filter_input(INPUT_POST, "password"), PASSWORD_BCRYPT);
            $user = $_POST["id"];

            $stmt = $this -> pdo->prepare("UPDATE employee SET password =:passss WHERE employee_id=:id");

            $stmt->bindParam(':id', $user);
            $stmt->bindParam(':passss', $passss);

            $stmt->execute();
            header("location: ./");

        return "";
    }
}

(new CurrentPage())->render();
