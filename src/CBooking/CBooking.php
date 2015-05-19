<?php
/**
 * CBookings, class that represents bookings.
 *
 */
class CBooking {

    public $db;
    protected $table;

  /*
   * Constructor
   *
   */
    public function __construct($db, $tn) {
        $this->db = $db;
        $this->tn = $tn;
        $this->table = $this->tn['booking'];

        $this->bookingCategory = new CBookingCategory($db, $tn);
    }


    /*
     * Gets a booking with specific id
     *
     * @param integer $id the booking category id
     * @return resultset
     */
    public function getBooking($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $params = array($id);

        $this->db->execute($sql, $params);
        $result = $this->db->fetch();

        return $result;
     }


    /*
     * Get bookings under certain category.
     *
     * @return array with resultset
     */
    public function getBookings($category) {
        $params = array($category);
        $sql = "SELECT * FROM {$this->table} WHERE Bokning_typ_id = ?";

        $this->db->execute($sql, $params);
        $result = $this->db->fetchAll();

        return $result;
    }


    /*
     * Expand the booking giving full detals.
     *
     * @param integer $category of booking to fetch.
     * @return array with resultset
     */
    public function getJoinedBookings($category) {
        $params = array($category);
        $sql = "
SELECT
    Bokning.id,
    Kal_period.Vecka_start,
    Kal_period.Vecka_slut,
    Kal_prislista.Kal_prislistaStr AS Prislista,
    Person.Namn AS Betalperson
FROM
    Bokning,
    Bokning_faktura,
    Kal_prislista,
    Kal_period,
    Person
WHERE
    Bokning.Bokning_typ_id = ? AND
    Bokning.Bokning_faktura_id = Bokning_faktura.id AND
    Bokning.Kal_prislista_id = Kal_prislista.id AND
    Bokning.Kal_period_id = Kal_period.id AND
    Bokning_faktura.Betalperson_id = Person.id;";

        $this->db->execute($sql, $params);
        $result = $this->db->fetchAll();

        return $result;
    }


   /*
     * Get all bookings regardless of category.
     *
     * @return string with html to display content
     */
    public function getAllBookings() {
        $listHTML = "";

        for ($i = 1; $i <= 3; $i++) {
            $result = $this->getBookings($i);
            $categoryStr = $this->bookingCategory->getCategoryStr($i) . "ar"; // Append "ar" for plural.
            $listHTML .= "<h1>" . $categoryStr . "</h1>";
            $listHTML .= $this->listBookings($result);
        }

        return $listHTML;
    }


   /*
     * List resultset in HTML.
     *
     * @param $result the resultset from db query.
     * @return string with html to display content
     */
    public function listBookings($result) {
        $listHTML = "";

        foreach($result AS $key => $val) {
            $listHTML .= "<li>";
            $listHTML .= $val->id . " (id), ";
            $listHTML .= $val->Bokning_faktura_id . " (faktura), ";
            $listHTML .= $val->Kal_prislista_id . " (prislista), ";
            $listHTML .= $val->Kal_period_id . " (period), ";
            $listHTML .= $val->Bokning_typ_id . " (typ).";
            $listHTML .= " ( ";
            $listHTML .= "<a href=\"delete.php?id=$val->id\"> Ta bort</a> )";
            $listHTML .= "</li>";
        }

        return $listHTML;
    }


    /*
     * List joined resultset in HTML.
     *
     * @param $result the resultset from db query.
     * @return string with html to display content
     */
    public function listJoinedBookings($result) {
        $listHTML = "";

        foreach($result AS $key => $val) {
            $listHTML .= "<li>";
            $listHTML .= $val->id . ": ";
            $listHTML .= $val->Vecka_start . " (Startvecka), ";
            $listHTML .= $val->Vecka_slut . " (Slutvecka), ";
            $listHTML .= $val->Prislista . ", ";
            $listHTML .= $val->Betalperson . ".";
            $listHTML .= " ( ";
            $listHTML .= "<a href=\"delete.php?id=$val->id\"> Ta bort</a> )";
            $listHTML .= "</li>";
        }

        return $listHTML;
    }


    /*
     * Updates content
     *
     */
    public function updateContent($params) {
        $sql = "
                 UPDATE {$this->table} SET
                 title   = ?,
                 slug    = ?,
                 url     = ?,
                 data    = ?,
                 type    = ?,
                 filter  = ?,
                 published = ?,
                 updated = NOW()
                     WHERE id = ?";

      $res = $this->db->execute($sql, $params);

      if($res) {
        $output = 'Informationen sparades.';
      } else {
        $output = 'Informationen sparades EJ.<br><pre>' . print_r($this->db->ErrorInfo(), 1) . '</pre>';
      }

      return $output;
    }



    /*
     * Permanently Deletes content.
     *
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $params = array($id);
        $this->db->execute($sql, $params);
        $output = "Det raderades " . $this->db->RowCount() . " rader från databasen.";
        return $output;
    }



    /*
     * Creates new content in db
     *
     */
    public function insertNewBooking($params) {
        $this->db->insert($this->table, $params);
        //$sql = "INSERT INTO {$this->tn['bookings']} (Bokning_faktura_id, Kal_prislista_id, Kal_period_id, Bokning_typ_id)
          //          VALUES (?, ?, ?, ?);";
        return $this->db->execute();
    }


    /*
     * Method that returns table name.
     *
     * @return string $this->table.
     */
    public function table() {
        return $this->table;
    }


}