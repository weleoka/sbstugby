<?php


/**
 * CPerson, class that represents person.
 */
class CPerson {

    protected $table;
  /*
   * Constructor
   *
   */
    public function __construct($db, $tn) {
        $this->db = $db; // new CDatabase($dbCredentials);
        $this->tn = $tn;
        $this->table = $this->tn['person'];
    }


    /*
     * Gets all
     *
     * @return object
     */
     public function getAll () {
        $sql = "SELECT * FROM {$this->table}";
        $this->db->execute($sql);
        // dump($results);
        return $this->db->fetchAll();
     }
/*
    foreach ($tags as $tag) {
      $aaa[$tag->tag] = $tag->tag;
    }
*/
    /*
     * Method that returns table name.
     *
     * @return string $this->table.
     */
    public function table() {
        return $this->table;
    }

}
