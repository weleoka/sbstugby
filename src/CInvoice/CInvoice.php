<?php


/**
 * CInvoice, class that represents billing.
 */


class CInvoice {

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
        $sql = "SELECT {$this->tn['invoices']}.id,  {$this->tn['person']}.namn
                    FROM {$this->tn['invoices']}, {$this->tn['person']}
                    WHERE {$this->tn['invoices']}.id = {$this->tn['person']}.id";
        $this->db->execute($sql);
        // dump($results);
        return $this->db->fetchAll();
     }


/*     public function insertNewInvoice($params) {
        $output = '';
        $sql = "INSERT INTO {$this->tn['bookings']} (Faktura_id, Kal_prislista_id, Kal_period_id, Bokning_typ_id)
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
