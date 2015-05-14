<?php


/**
 * CCottage, class that represents a cottage.
 */
class CCottage {

  /*
   * Constructor that accepts $db credentials and creates CDatabase object
   *
   */
    public function __construct($dbCredentials, $tableNames) {
        $this->db = new CDatabase($dbCredentials);
        $this->tableNames = $tableNames;
    }


    /*
     * Gets all
     *
     * @return object
     */
     public function getAll () {
        $sql = "SELECT * FROM {$this->tableNames['cottages']}";
        $results = $this->db->ExecuteSelectQueryAndFetchAll($sql);
        // dump($results);
        return $results;
     }
/*
    foreach ($tags as $tag) {
      $aaa[$tag->tag] = $tag->tag;
    }
*/

}
