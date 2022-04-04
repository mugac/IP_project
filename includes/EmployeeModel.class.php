<?php

final class EmployeeModel
{
    public ?int $employee_id;
    public string $name;
    public string $surname;
    public string $job;
    public ?int $wage;
    public ?int $room;
    public string $password;
    public login $login;
    public bool $admin;

    private array $validationErrors = [];
    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }

    public function __construct(array $employeeData = [])
    {
        $id = $employeeData['employee_id'] ?? null;
        if (is_string($id))
            $id = filter_var($id, FILTER_VALIDATE_INT);

        $wage = $employeeData['wage'] ?? null;
        if (is_string($wage))
            $wage = filter_var($wage, FILTER_VALIDATE_INT);

        $this->employee_id = $id;
        $this->name = $employeeData['name'] ?? "";
        $this->surname = $employeeData['surname'] ?? "";
        $this->job = $employeeData['job'] ?? "";
        $this->wage = $wage;
        $this->room = $employeeData['room'] ?? null;
    }

    public function validate() : bool
    {
        $isOk = true;

        if (!$this->name) {
            $isOk = false;
            $this->validationErrors['name'] = "Name cannot be empty";
        }
        if (!$this->surname) {
            $isOk = false;
            $this->validationErrors['surname'] = "Surname cannot be empty";
        }
        if (!$this->job) {
            $isOk = false;
            $this->validationErrors['job'] = "Job cannot be empty";
        }
        if (!$this->wage){
            $this->wage = 0;
        }
        if (!$this->room){
            $this->room = null;
        }

        return $isOk;
    }

    public function insert() : bool
    {
        $query = "INSERT INTO employee (name, surname, job, wage, room) VALUES (:name, :surname, :job, :wage, :room)";

        $stmt = DB::getConnection()->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':surname', $this->surname);
        $stmt->bindParam(':job', $this->job);
        $stmt->bindParam(':wage', $this->wage);
        $stmt->bindParam(':room', $this->room);

        if (!$stmt->execute())
            return false;

        $this->employee_id = DB::getConnection()->lastInsertId();
        return true;
    }

    public function update() : bool
    {
        $query = "UPDATE employee SET name=:name, surname=:surname, job=:job, wage=:wage, room=:room WHERE employee_id=:employeeId";

        $stmt = DB::getConnection()->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':surname', $this->surname);
        $stmt->bindParam(':job', $this->job);
        $stmt->bindParam(':wage', $this->wage);
        $stmt->bindParam(':room', $this->room);
        $stmt->bindParam(':employeeId', $this->employee_id);

        return $stmt->execute();
    }

    public function delete() : bool
    {
        return self::deleteById($this->employee_id);
    }

    public static function deleteById(int $employee_id) : bool {

        try {
            $query = "DELETE FROM `key` WHERE employee=:employeeId";

            $stmt = DB::getConnection()->prepare($query);
            $stmt->bindParam(':employeeId', $employee_id);

            $stmt->execute();

            $query = "DELETE FROM employee WHERE employee_id=:employeeId";

            $stmt = DB::getConnection()->prepare($query);
            $stmt->bindParam(':employeeId', $employee_id);
            $stmt->execute();

            return true;
        }
        catch (\mysql_xdevapi\Exception $exception){
            $exception->getMessage();
            return false;
        }
    }

    public static function findById(int $employee_id) : ?EmployeeModel
    {
        $query = "SELECT * FROM employee WHERE employee_id=:employeeId";

        $stmt = DB::getConnection()->prepare($query);
        $stmt->bindParam(':employeeId', $employee_id);

        $stmt->execute();

        $dbData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dbData)
            return null;

        return new self($dbData);
    }

    public static function readPostData() : EmployeeModel
    {
        return new self($_POST); //není úplně košer, nefiltruju
    }
}