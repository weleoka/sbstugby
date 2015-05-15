<?php


/**
 * CPriceList, class that represents pricelists.
 */


class CPriceList {

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
        $sql = "SELECT * FROM {$this->tn['priceLists']}";
        $this->db->execute($sql);
        // dump($results);
        return $this->db->fetchAll();
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
