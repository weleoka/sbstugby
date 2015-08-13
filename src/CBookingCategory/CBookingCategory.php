<?php


/**
 * CBookingCategory, class that represents billing.
 */


class CBookingCategory {

    protected $table;

  /*
   * Constructor
   *
   */
    public function __construct($db, $tn) {
        $this->db = $db;
        $this->tn = $tn;
        $this->table = $this->tn['bookingCategory'];
    }

    /*
     * Gets category under specific id
     *
     * @param id, int.
     * @return string
     */
     public function getCategoryStr ($id) {
        $sql = "SELECT Beskrivning FROM {$this->table} WHERE id = ?";

        $params = array($id);
        $this->db->execute($sql, $params);
        $result = $this->db->fetchAll();

        return $result[0]->Beskrivning;
     }


    /*
     * Method that returns table name.
     *
     * @return string $this->table.
     */
    public function table() {
        return $this->table;
    }

    /*
     * Gets all
     *
     * @return object
     */
/*     public function getAll () {
        $sql = "SELECT {$this->tn['invoices']}.id,  {$this->tn['person']}.namn
                    FROM {$this->tn['invoices']}, {$this->tn['person']}
                    WHERE {$this->tn['invoices']}.id = {$this->tn['person']}.id";
        $this->db->execute($sql);
        // dump($results);
        return $this->db->fetchAll();
     }*/


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
