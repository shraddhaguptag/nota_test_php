<?php

/**
 * Class TableCreator
 * 
 * A class that creates and manages a database table with specific fields and 
 * functionality to fill it with random data and retrieve specific records.
 * 
 * This class cannot be instantiated or extended.
 */
final class TableCreator
{
    private $pdo;

    /**
     * TableCreator constructor.
     * 
     * Initializes the database connection, creates the table, and fills it with random data.
     * 
     * @throws PDOException if there is a database connection error.
     */
    public function __construct()
    {
        $this->pdo = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '');
        $this->create();
        $this->fill();
    }

    /**
     * Creates the Test table in the database.
     * 
     * This method is accessible only within the class.
     * 
     * @return void
     * @throws PDOException if there is an error executing the SQL statement.
     */
    private function create()
    {
        $sql = "CREATE TABLE IF NOT EXISTS Test (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    script_name VARCHAR(25) NOT NULL,
                    start_time DATETIME NOT NULL,
                    end_time DATETIME NOT NULL,
                    result ENUM('normal', 'illegal', 'failed', 'success') NOT NULL
                )";

        $this->pdo->exec($sql);
    }

    /**
     * Fills the Test table with random data.
     * 
     * This method is accessible only within the class.
     * 
     * @return void
     * @throws PDOException if there is an error executing the SQL statement.
     */
    private function fill()
    {
        $results = ['normal', 'illegal', 'failed', 'success'];
        
        for ($i = 0; $i < 10; $i++) {
            $scriptName = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 25);
            $startTime = date('Y-m-d H:i:s', strtotime('-' . rand(1, 10) . ' days'));
            $endTime = date('Y-m-d H:i:s', strtotime('+'. rand(1, 10) . ' days', strtotime($startTime)));
            $result = $results[array_rand($results)];

            $stmt = $this->pdo->prepare("INSERT INTO Test (script_name, start_time, end_time, result) VALUES (:script_name, :start_time, :end_time, :result)");
            $stmt->bindParam(':script_name', $scriptName);
            $stmt->bindParam(':start_time', $startTime);
            $stmt->bindParam(':end_time', $endTime);
            $stmt->bindParam(':result', $result);
            $stmt->execute();
        }
    }

    /**
     * Retrieves records from the Test table with result 'normal' or 'success'.
     * 
     * This method is accessible from outside the class.
     * 
     * @return array Array of records that match the criteria.
     * @throws PDOException if there is an error executing the SQL statement.
     */
    public function get()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Test WHERE result IN ('normal', 'success')");
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Usage example (not part of the class):
// $tableCreator = new TableCreator(); // This line will cause an error since the class cannot be instantiated.
// $data = $tableCreator->get(); // This will also not work since the object cannot be created.
