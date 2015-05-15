<?php


/**
 * CPeriod, class that represents time period
 *
 */
class CPeriod {

    public $db;
    protected $tableName;

  /*
   * Constructor that accepts $db credentials and creates CDatabase object
   *
   */
    public function __construct($db, $tableNames) {
        $this->db = $db; // new CDatabase($dbCredentials);
        $this->tn = $tableNames;
    }

    public function getAllWeeks() {
        $sql = "SELECT * FROM {$this->tn['calendarWeek']}";
        $this->db->execute($sql);
        // dump($results);
        return $this->db->fetchAll();
    }

    public function insertNewPeriod($params) {
        $this->db->insert($this->tn['bookingPeriod'], $params);
        // $sql = "INSERT INTO {$this->tn['bookingPeriod']} (Vecka_start, Vecka_slut)
           //          VALUES (?, ?);";
        return $this->db->execute();
    }

}