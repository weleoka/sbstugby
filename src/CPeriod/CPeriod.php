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
    public function __construct($dbCredentials, $tableNames) {
        $this->db = new CDatabase($dbCredentials);
        $this->tableNames = $tableNames;
    }

    public function getAllWeeks() {
        $sql = "SELECT * FROM {$this->tableNames['calendarWeek']}";
        $results = $this->db->ExecuteSelectQueryAndFetchAll($sql);
        // dump($results);
        return $results;
    }

    public function insertNewPeriod($params) {
        $output = '';
        $sql = "INSERT INTO {$this->tableNames['bookingPeriod']} (Vecka_start, Vecka_slut)
                    VALUES (?, ?);";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $output = '... periodinformationen sparades.';
        } else {
            $output = '... periodinformationen sparades EJ.<br><pre>' . print_r($this->db->ErrorInfo(), 1) . '</pre>';
        }
        return $output;
    }

}