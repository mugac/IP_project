<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {

    const STATE_DELETE_REQUESTED = 4; 
    const STATE_PROCESSED = 3;

    const RESULT_SUCCESS = 1;
    const RESULT_FAIL = 2;

    private int $state;
    private int $result = 0;

    public function __construct()
    {
        parent::__construct();
        $this->title = "New employee";
    }


    protected function setUp(): void
    {
        parent::setUp();

        $this->state = $this->getState();

        if ($this->state == self::STATE_PROCESSED) {
            //reportuju

        } elseif ($this->state == self::STATE_DELETE_REQUESTED){
            //přišla data
            //načíst

            $employeeId = filter_input(INPUT_POST, "employee_id");

            //validovat

            //když jsou validní
                if(EmployeeModel::deleteById($employeeId)){
                    //přesměruj, ohlas úspěch
                    $this->redirect(self::RESULT_SUCCESS);
                } else {
                    //přesměruj, ohlas chybu
                    $this->redirect(self::RESULT_FAIL);
                }
            
        }

    }


    protected function body(): string
    {
        if(!isset($_SESSION["loggedin"])){
            header("location: ../login.php");
            exit;
        }
            if ($this->state == self::STATE_PROCESSED){

            if ($this->result == self::RESULT_SUCCESS) {
                return $this->m->render("employeeSuccess", ['message' => "Employee deleted successfully."]);
            } else {
                return $this->m->render("employeeFail", ['message' => "Employee deletion failed."]);
            }
        }
        return"";
    }

    protected function getState() : int
    {
        //když mám result -> zpracováno
        $result = filter_input(INPUT_GET, 'result', FILTER_VALIDATE_INT);

        if($result){
            if ($result == self::RESULT_SUCCESS) {
                $this->result = self::RESULT_SUCCESS;
                return self::STATE_PROCESSED;
            } elseif($result == self::RESULT_FAIL) {
                $this->result = self::RESULT_FAIL;
                return self::STATE_PROCESSED;
            }

            return self::STATE_PROCESSED;
        } else{
            return self::STATE_DELETE_REQUESTED;
        }
    }

    private function redirect(int $result) : void {
        $location = strtok($_SERVER['REQUEST_URI'], '?');
        header("Location: {$location}?result={$result}");
        exit;
    }

}

(new CurrentPage())->render();
