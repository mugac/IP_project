<?php

require "./includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {

    protected string $title = "Login";

    protected function body(): string
    {
        if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
            header("location: index.php");
            exit;
        }
        return $this->m->render("login");
    }
}

(new CurrentPage())->render();
