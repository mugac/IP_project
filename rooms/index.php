<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Výpis místností";

    protected function body(): string
    {
        if(!isset($_SESSION["loggedin"])){
            header("location: ../login.php");
            exit;
        }
        if(isset($_SESSION["admin"])){
            $op = true;
        }
        else{
            $op = null;
        }
        $stmt = $this->pdo->prepare("SELECT * FROM `room` ORDER BY `name`");
        $stmt->execute();

        return $this->m->render("roomList", ["roomDetail" => "room.php", "rooms" => $stmt, "op"=>$op]);
    }
}

(new CurrentPage())->render();
