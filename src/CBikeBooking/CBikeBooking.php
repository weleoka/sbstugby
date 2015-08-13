<?php
/**
 * CBikeBooking, class that represents booking bikes.
 *
 */
class CBikeBooking {

    protected $table;
  /*
   * Constructor
   *
   */
    public function __construct($db, $tn) {
            $this->db = $db;
            $this->tn = $tn;
            $this->table = $this->tn['bikeBooking'];


            $this->person = new CPerson($db, $tn);
            $this->invoice = new CInvoice($db, $tn);
            $this->priceList = new CPriceList($db, $tn);
            $this->booking = new CBooking($db, $tn);
            $this->period = new CPeriod($db, $tn);
            $this->cottage = new CCottage($db, $tn);
            $this->bookingCategory = new CBookingCategory($db, $tn);
            $this->calendar = new CCalendar($db, $tn);
    }



    /*
     * Print form
     *
     * @return string with html to display content
     */
    public function getHTML () {
        return $this->form->getHTML();
    }


    /*
     * Make form.
     *
     * @params
     * @return void
     */
    public function makeForm ($criteria) {

        $invoices = $this->invoice->find($criteria);
        foreach ($invoices as $invoice) {
          $selectInvoices[ $invoice->id ] = $invoice->id . ": " . $invoice->Betalperson;
        }

        $priceLists = $this->priceList->getAll();
        foreach ($priceLists as $priceList) {
          $selectPriceLists[ $priceList->id ] = $priceList->Beskrivning;
        }

        $persons = $this->person->getAll();
        foreach ($persons as $person) {
          $selectPersons[ $person->id ] = $person->Namn;
        }

/*        $weeks = $this->calendar->getAllWeeks();
        foreach ($weeks as $week) {
          $selectWeeks[ $week->id ] = $week->id;
        }*/
        $sql = "
SELECT
    cykel.id AS id,
    cykel.storlek AS storlek,
    cykel_typ.beskrivning AS beskrivning
FROM
    cykel,
    cykel_typ
WHERE
    cykel.cykel_typ_id = cykel_typ.id;";

        $this->db->execute($sql);
        $bikes = $this->db->fetchAll();

        foreach ($bikes as $bike) {
          $bikeStr = "(" . $bike->id
                             . ") " . $bike->beskrivning
                             . " (" . $bike->storlek
                             . " tum).";
          $selectBikes[ $bike->id ] = $bikeStr;
        }

        $this->form = new \Mos\HTMLForm\CForm([],[
            'invoice' => [
                        'type'      => 'select',
                        'label'      => 'Välj Faktura: ',
                        'options'  => $selectInvoices,
            ],
            'priceList' => [
                        'type'      => 'select',
                        'label'      => 'Välj prislista: ',
                        'options'  => $selectPriceLists,
            ],
            'first_week' => [
                        'type'        => 'week',
                        'label' => 'Välj startvecka.',
            ],
            'last_week' => [
                        'type'      => 'week',
                        'label'      => 'Välj slutvecka: ',
            ],
            'person' => [
                        'type'      => 'select',
                        'label'      => 'Person: ',
                        'options'  => $selectPersons,
            ],
            'helmet' => [
                        'type'      => 'select',
                        'label'      => 'Välj hjälm: ',
                        'options'  => $selectHelmets,
            ],
            'bike' => [
                        'type'      => 'select',
                        'label'      => 'Välj cykel: ',
                        'options'  => $selectBikes,
            ],
            'submit' => [
                'type'      => 'submit',
                'class'     => 'bigButton',
                'callback'  => function($form) {

                    $sql = "START TRANSACTION;";
                    $this->db->execute($sql);

                    $periodParams = [
                        'Vecka_start'      => $form->Value('first_week'), // HTML 5 gives format like this: 2015-W51
                        'Vecka_slut'        => $form->Value('last_week'),   // Need to alter table to store that
                                                                                                // and write method to substr() it for price calcs.
                    ];
                    $this->db->insert($this->period->table(), $periodParams);
                    $res01 = $this->db->execute();

                    $bookingParams = [
                        'Bokning_faktura_id'=> $form->Value('invoice'),
                        'Kal_prislista_id'      => $form->Value('priceList'),
                        'Kal_period_id'         => $this->db->LastInsertId(),
                        'Bokning_typ_id'      => 2,
                    ];
                    $this->db->insert($this->booking->table(), $bookingParams);
                    $res02 = $this->db->execute();

                    $bikeBookingParams = [
                        'Bokning_id'     => $this->db->LastInsertId(),
                        'Person_id'       => $form->Value('person'),
                        'Cykel_hjälm_id'=> $form->Value('helmet'),
                        'Cykel_id'        => $form->Value('bike'),
                    ];
                    $this->db->insert($this->table, $cottageBookingParams);
                    $res03 = $this->db->execute();

                    if ($res01 && $res02 && $res03) {
                        $sql = "COMMIT;";
                        $res = $this->db->execute($sql);
                        return true;
                    } else {
                        $sql = "ROLLBACK;";
                        $res = $this->db->execute($sql);
                        print("First: " . $res01 . " Second: " . $res02 . " Third: " . $res03);
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
     * Method that returns table name.
     *
     * @return string $this->table.
     */
    public function table() {
        return $this->table;
    }

}