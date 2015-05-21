<?php
/**
 * CCottage, class that represents a cottage.
 */
class CCottage {

    protected $table;

  /*
   * Constructor
   *
   */
    public function __construct($db, $tn) {
        $this->db = $db; // new CDatabase($dbCredentials);
        $this->tn = $tn;
        $this->table = $this->tn['cottage'];
    }


    /*
     * Gets all
     *
     * @return resultset
     */
     public function getAll () {
        $sql = "SELECT * FROM {$this->table}";
        $this->db->execute($sql);

        return $this->db->fetchAll();
     }


    /*
     * Gets all
     *
     * @return resultset
     */
     public function getJoinedAll () {
        $sql = "
SELECT
    *
FROM
    Stuga,
    Stuga_Utrustning,
    Stuga_Köksstandard
WHERE
    Stuga.id = 1 AND
    Stuga.Stuga_utrustning_id = Stuga_utrustning.id AND
    Stuga.Stuga_köksstandard_id = Stuga_köksstandard.id;";
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

}
