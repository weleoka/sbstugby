<?php
/**
 * CCottage, class that represents a cottage.
 */
class CCottage {

  /*
   * Constructor that accepts $db credentials and creates CDatabase object
   *
   */
    public function __construct($db, $tableNames) {
        $this->db = $db; // new CDatabase($dbCredentials);
        $this->tn = $tableNames;
    }


    /*
     * Gets all
     *
     * @return object
     */
     public function getAll () {
        $sql = "SELECT * FROM {$this->tn['cottages']}";
        $this->db->execute($sql);
        // dump($results);
        return $this->db->fetchAll();
     }
/*
    foreach ($tags as $tag) {
      $aaa[$tag->tag] = $tag->tag;
    }
*/

}
