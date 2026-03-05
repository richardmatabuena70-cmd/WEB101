<?php
class Student {
    private $conn;
    private $table = "student_tbl";

    public $id;
    public $name;
    public $age;
    public $dep;

    // Constructor to initialize database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE: Add a new student
    public function create() {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table . " (stud_name, stud_age, stud_dep) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $this->name, $this->age, $this->dep);
        return $stmt->execute();
    }

    // READ: Get all students
    public function read() {
        return $this->conn->query("SELECT * FROM " . $this->table);
    }

    // READ SINGLE: Get one student for editing
    public function readOne() {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE stud_id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // UPDATE: Modify existing student
    public function update() {
        $stmt = $this->conn->prepare("UPDATE " . $this->table . " SET stud_name=?, stud_age=?, stud_dep=? WHERE stud_id=?");
        $stmt->bind_param("sisi", $this->name, $this->age, $this->dep, $this->id);
        return $stmt->execute();
    }

    // DELETE: Remove a student
    public function delete() {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE stud_id = ?");
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }
}
?>