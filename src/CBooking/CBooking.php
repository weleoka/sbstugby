<?php
/**
 * CBookings, class that represents bookings.
 *
 */
class CBooking {

    public $db;
    protected $tableName;

  /*
   * Constructor that accepts $db credentials and creates CDatabase object
   *
   */
    public function __construct($db, $tableNames) {
        $this->db = $db; //new CDatabase($dbCredentials);
        $this->tn = $tableNames;
    }



    /*
     * Gets all categories
     *
     * @return string with html to display content
     */
     public function getAllCategories () {
        $sql = "SELECT Beskrivning FROM {$this->tn['bookingCategory']}";
        $this->db->execute($sql);

        return $this->db->fetchAll();
     }



    /*
     * Gets category under specific id
     *
     * @param id
     * @return string
     */
     public function getCategoryStr ($id) {
        $sql = "SELECT Beskrivning FROM {$this->tn['bookingCategory']} WHERE id = ?";

        $params = array($id);
        $this->db->execute($sql, $params);
        $result = $this->db->fetchAll();

        return $result[0]->Beskrivning;
     }


    /*
     * Get bookings under certain category.
     *
     * @return string with html to display content
     */
    public function getBookings($category) {
        $sql = "SELECT * FROM {$this->tn['bookings']} WHERE Bokning_typ_id = ?";
        $params = array($category);

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
            $categoryStr = $this->getCategoryStr($i) . "ar"; // Append "ar" for plural.
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
            $listHTML .= $val->id . "(id), ";
            $listHTML .= $val->Bokning_faktura_id . "(faktura), ";
            $listHTML .= $val->Kal_prislista_id . "(prislista), ";
            $listHTML .= $val->Kal_period_id . "(period), ";
            $listHTML .= $val->Bokning_typ_id . "(typ).";
            $listHTML .= " ( ";
            $listHTML .= "<a href=\"delete.php?id=$val->id\"> Ta bort</a> )";
            $listHTML .= "</li>";
        }

        return $listHTML;
    }


    /*
     * Gets a booking with specific id
     *
     * @param integer $id the booking category id
     * @return resultset
     */
    public function getBooking($id) {
        $sql = "SELECT * FROM {$this->tableName} WHERE id = ?";
        $params = array($id);

        $this->db->execute($sql, $params);
        $result = $this->db->fetchAll();

        return $result;
     }


    /*
     * Updates content
     *
     */
    public function updateContent($params) {
        $sql = "
                 UPDATE {$this->tableName} SET
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
        $sql = "DELETE FROM {$this->tableName} WHERE id = ?";
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
        $this->db->insert($this->tn['bookings'], $params);
        //$sql = "INSERT INTO {$this->tn['bookings']} (Bokning_faktura_id, Kal_prislista_id, Kal_period_id, Bokning_typ_id)
          //          VALUES (?, ?, ?, ?);";
        return $this->db->execute();
    }



    /*
     * Creates new content in db for a cottage booking
     *
     */
    public function insertNewCottageBooking($params) {
        $this->db->insert($this->tn['cottageBookings'], $params);
        // $sql = "INSERT INTO {$this->tn['cottageBookings']} (Bokning_id, Stuga_id, person01, person02, person03, person04, person05, person06, person07, person08, )
           //         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        return $this->db->execute();
    }

}