<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Výpis zaměstnanců";

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
                $stmt = $this->pdo->prepare("SELECT employee.name as name, employee.surname as surname, employee.job as job, employee.wage as wage, room.name as room FROM `employee` JOIN room ON employee.room = room.room_id ORDER BY `name`");

        $stmt->execute();

        return $this->m->render("employeeList", ["employeeDetail" => "employee.php", "employees" => $stmt, "op"=>$op]);
    }
}

(new CurrentPage())->render();
