<?php


/**
 * CBookings, class that represents bookings
 *
 */
class CBookings {

    public $db;
    protected $tableName;

  /*
   * Constructor that accepts $db credentials and creates CDatabase object
   *
   */
    public function __construct($dbCredentials, $tableNames) {
        $this->db = new CDatabase($dbCredentials);
        $this->tableNames = $tableNames;
    }


    /*
     * Gets all categories
     *
     * @return string with html to display content
     */
     public function getAllCategories () {
        $sql = "SELECT Beskrivning FROM {$this->tableNames['bookingCategory']}";
        $result = $this->db->ExecuteSelectQueryAndFetchAll($sql);

        return $result;
     }



    /*
     * Gets category under specific id
     *
     * @param id
     * @return string
     */
     public function getCategoryStr ($id) {
        $sql = "SELECT Beskrivning FROM {$this->tableNames['bookingCategory']} WHERE id = ?";

        $params = array($id);
        $result = $this->db->ExecuteSelectQueryAndFetchAll($sql, $params);

        return $result[0]->Beskrivning;
     }



    /*
     * Gets all bookings ( under certain category )
     *
     * @return string with html to display content
     */
	public function getAllBookings($category) {
        $sql = "SELECT * FROM {$this->tableName} WHERE Bokning_typ_id = ?";

        $params = array($category);
        $result = $this->db->ExecuteSelectQueryAndFetchAll($sql, $params);
        $listHTML = "";

        foreach($result AS $key => $val) {
            $listHTML .= "<li>";
            $listHTML .= $val->id;
            $listHTML .= $val->Faktura_id;
            $listHTML .= $val->Kal_prislista_id;
            $listHTML .= $val->Kal_period_id;
            $listHTML .= $val->Bokning_typ_id;
            $listHTML .= " ( ";
            $listHTML .= "<a href=\"delete.php?id=$val->id\"> Ta bort</a> )";
            $listHTML .= "</li>";
        }

        return $listHTML;
    }
/*
  `id` INT NOT NULL AUTO_INCREMENT ,
  `Faktura_id` INT NULL ,
  `Kal_prislista_id` INT NOT NULL ,
  `Kal_period_id` INT NOT NULL ,
  `Bokning_typ_id` VARCHAR(255) NOT NULL */
    /*
     * Gets a booking with specific id
     *
     * @param
     * @return object for the specified id
     */
    public function getBooking($id) {

        $sql = "SELECT * FROM {$this->tableName} WHERE id = ?";
        $params = array($id);

        $result = $this->db->ExecuteSelectQueryAndFetchAll($sql, $params);

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

      $res = $this->db->ExecuteQuery($sql, $params);

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
	  function delete($id) {
    		$sql = "DELETE FROM {$this->tableName} WHERE id = ?";
   		$params = array($id);
    		$this->db->ExecuteQuery($sql, $params);
    		$output="Det raderades " . $this->db->RowCount() . " rader från databasen.";
    		return $output;
		}


    /*
     * Creates new content in db
     *
     */
    public function insertContent($params) {
    $sql = "
                        INSERT INTO {$this->tableName} (title, slug, url, data, type, filter, published, created)
                        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";


      $res = $this->db->ExecuteQuery($sql, $params);
        if($res) {
        $output = 'Informationen sparades.';
      } else {
        $output = 'Informationen sparades EJ.<br><pre>' . print_r($this->db->ErrorInfo(), 1) . '</pre>';
      }

      return $output;
    }


}