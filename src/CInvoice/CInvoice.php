<?php


/**
 * CInvoice, class that represents billing.
 */


class CInvoice {

    protected $table;

  /*
   * Constructor
   */
    public function __construct($db, $tn) {
        $this->db = $db;
        $this->tn = $tn;
        $this->table = $this->tn['invoice'];

        $this->person = new CPerson($db, $tn);
    }


    /*
     * Gets all
     *
     * @return object
     */
     public function getAll () {
        $sql = "SELECT {$this->table}.id,  {$this->tn['person']}.namn
                    FROM {$this->table}, {$this->tn['person']}
                    WHERE {$this->table}.id = {$this->tn['person']}.id";
        $this->db->execute($sql);
        // dump($results);
        return $this->db->fetchAll();
     }

    /*
     * Search for invoices using criteria.
     *
     * @param object $criteria.
     * @return array with resultset
     */
    public function find($criteria) {
        $params = [];
        $i = 0;
        foreach($criteria AS $searchItem ) {
            if (!is_null($searchItem)) {
                $params[$i] = $searchItem;
            }
            $i++;
        }

        $sql = "
SELECT
    Bokning_faktura.id AS id,
    Person.Namn AS Betalperson
FROM
    Bokning_faktura,
    Person
WHERE
    Bokning_faktura.Betalperson_id = Person.id";

        if (!is_null($criteria['id'])) {
            $sql .= "\nAND Bokning.id = ?";
        }
        if (!is_null($criteria['paid'])) {
            $sql .= "\nAND Bokning_faktura.betald != NULL";
        }
        if (!is_null($criteria['customer'])) {
            $sql .= "\nAND Bokning_faktura.Betalperson_id = ?";
        }

        $this->db->execute($sql, $params);
        $result = $this->db->fetchAll();

        return $result;
    }


    /*
     * Make form.
     *
     * @params
     * @return void
     */
    public function makeForm () {

        $persons = $this->person->getAll();
        foreach ($persons as $person) {
          $selectPersons[ $person->id ] = $person->Namn;
        }

        $this->form = new \Mos\HTMLForm\CForm([],[
            'person01' => [
                        'type'      => 'select',
                        'label'      => 'Person 01: ',
                        'options'  => $selectPersons,
            ],
            'submit' => [
                'type'      => 'submit',
                'class'     => 'bigButton',
                'callback'  => function($form) {

                    $sql = "START TRANSACTION;";
                    $this->db->execute($sql);

                    $invoiceParams = [
                        'Person01'       => $form->Value('person01'),
                    ];
                    $this->db->insert($this->table, $invoiceParams);
                    $res01 = $this->db->execute();

                    if ($res01) {
                        $sql = "COMMIT;";
                        $res = $this->db->execute($sql);
                        return true;
                    } else {
                        $sql = "ROLLBACK;";
                        $res = $this->db->execute($sql);
                        print("First: " . $res01);
                        return false;
                    }
                }
            ],
        ]);

        // Check the status of the form.
        $status = $this->form->check();

        if ($status === true) {
            print('Bokningen genomfördes');

        } else if ($status === false) {
            print('Din bokning kunde inte genomföras.');
        }
    }


    /*
     * Get the HTML for the form.
     *
     * @return string with html to display content
     */
    public function getHTML () {
        return $this->form->getHTML();
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
