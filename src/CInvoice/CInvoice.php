<?php


/**
 * CBilling, class that represents billing.
 */


class CInvoice {

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
        $sql = "SELECT * FROM {$this->tableNames['invoices']}";
        $result = $this->db->ExecuteSelectQueryAndFetchAll($sql);
        dump($result);
        return $result;
     }
/*
    foreach ($tags as $tag) {
      $aaa[$tag->tag] = $tag->tag;
    }
*/

}
