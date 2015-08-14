<?php


/**
 * CPriceList, class that represents pricelists.
 */


class CPriceList {

    protected $table;
  /*
   * Constructor
   *
   */
    public function __construct($db, $tn) {
        $this->db = $db; // new CDatabase($dbCredentials);
        $this->tn = $tn;
        $this->table = $this->tn['priceList'];
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
     * Gets active price lists.
     *
     * @return object
     */
     public function getActive () {
        $sql = "
SELECT
    *
FROM
    {$this->table}
WHERE
    Aktiv = true;";

        $this->db->execute($sql);

        return $this->db->fetchAll();
     }


    /*
     * Method that returns table name.
     *
     * @return string $this->table.
     */
    public function table() {
        return $this->table;
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
