<?php


/**
 * CPeriod, class that represents time period
 *
 */
class CPeriod {

    public $db;
    protected $table;

  /*
   * Constructor
   *
   */
    public function __construct($db, $tn) {
        $this->db = $db; // new CDatabase($dbCredentials);
        $this->tn = $tn;
        $this->table = $this->tn['period'];
    }

    public function getAllWeeks() {
        $sql = "SELECT * FROM {$this->table}";
        $this->db->execute($sql);
        // dump($results);
        return $this->db->fetchAll();
    }

    public function insertNewPeriod($params) {
        // $this->db->insert($this->table, $params);
        $sql = "INSERT INTO {$this->table} (Vecka_start, Vecka_slut)
                  VALUES (?, ?);";
        return $this->db->execute($sql, $params);
    }

    /*
     * Method that returns table name.
     *
     * @return string $this->table.
     */
    public function table() {
        return $this->table;
    }
}