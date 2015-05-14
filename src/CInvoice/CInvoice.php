<?php


/**
 * CInvoice, class that represents billing.
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
        $sql = "SELECT {$this->tableNames['invoices']}.id,  {$this->tableNames['person']}.namn
                    FROM {$this->tableNames['invoices']}, {$this->tableNames['person']}
                    WHERE {$this->tableNames['invoices']}.id = {$this->tableNames['person']}.id";
        $results = $this->db->ExecuteSelectQueryAndFetchAll($sql);

        // dump($results);
        return $results;
     }


/*     public function insertNewInvoice($params) {
        $output = '';
        $sql = "INSERT INTO {$this->tableNames['bookings']} (Faktura_id, Kal_prislista_id, Kal_period_id, Bokning_typ_id)
                    VALUES (?, ?, ?, $categoryCode);";
        $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
            $output = '... periodinformationen sparades.';
        } else {
            $output = '... periodinformationen sparades EJ.<br><pre>' . print_r($this->db->ErrorInfo(), 1) . '</pre>';
        }
        return $output;
    }*/
/*
    foreach ($tags as $tag) {
      $aaa[$tag->tag] = $tag->tag;
    }
*/

}
