<?php


/**
 * CPerson, class that represents person.
 */
class CPerson {

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
        $sql = "SELECT * FROM {$this->tn['person']}";
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
