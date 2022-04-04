<?php

require "includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Rozcestník";

    protected function body(): string
    {
        if(!isset($_SESSION["loggedin"])){
            header("location: login.php");
            exit;
        }
        return $this->m->render("main");
    }
}

(new CurrentPage())->render();