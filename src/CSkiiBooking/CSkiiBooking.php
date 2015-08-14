<?php
/**
 * CSkiiBooking, class that represents booking skiis.
 *
 */
class CSkiiBooking {

    protected $table;
  /*
   * Constructor
   *
   */
    public function __construct($db, $tn) {
            $this->db = $db;
            $this->tn = $tn;
            $this->table = $this->tn['skiiBooking'];


            $this->person = new CPerson($db, $tn);
            $this->invoice = new CInvoice($db, $tn);
            $this->priceList = new CPriceList($db, $tn);
            $this->booking = new CBooking($db, $tn);
            $this->period = new CPeriod($db, $tn);

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

        $priceLists = $this->priceList->getActive();
        foreach ($priceLists as $priceList) {
          $selectPriceLists[ $priceList->id ] = $priceList->Beskrivning;
        }

        $persons = $this->person->getAll();
        foreach ($persons as $person) {
          $selectPersons[ $person->id ] = $person->Namn;
        }

        $weeks = $this->calendar->getAllWeeks();
        foreach ($weeks as $week) {
          $selectWeeks[ $week->id ] = $week->id;
        }

        $sql = "
SELECT
    skidor.id AS id,
    skidor.Storlek AS storlek,
    skid_typ.Beskrivning AS beskrivning
FROM
    skidor,
    skid_typ
WHERE
    skidor.skid_typ_id = skid_typ.id;";
        $this->db->execute($sql);
        $skiisets = $this->db->fetchAll();

        foreach ($skiisets as $skiiset) {
          $skiisetStr = "(" . $skiiset->id
                             . ") " . $skiiset->beskrivning
                             . " (" . $skiiset->storlek
                             . " tum).";
          $selectSkiisets[ $skiiset->id ] = $skiisetStr;
        }


        $sql = "
SELECT
    skid_hjälm.id AS id,
    skid_hjälm.storlek AS storlek
FROM
    skid_hjälm;";
        $this->db->execute($sql);
        $helmets = $this->db->fetchAll();
        foreach ($helmets as $helmet) {
          $helmetStr = "(" . $helmet->id
                             . ") " . $helmet->storlek
                             . " tum.";
          $selectHelmets[ $helmet->id ] = $helmetStr;
        }

        $sql = "
SELECT
    skid_stav.id AS id,
    skid_stav.storlek AS storlek
FROM
    skid_stav;";
        $this->db->execute($sql);
        $skiipoles = $this->db->fetchAll();
        foreach ($skiipoles as $skiipole) {
          $skiipoleStr = "(" . $skiipole->id
                             . ") " . $skiipole->storlek
                             . " tum.";
          $selectSkiipoles[ $skiipole->id ] = $skiipoleStr;
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
            'start_week' => [
                        'type'      => 'select',
                        'label'      => 'Välj startvecka.',
                        'options'   => $selectWeeks,
            ],
            'end_week' => [
                        'type'      => 'select',
                        'label'      => 'Välj slutvecka: ',
                        'options'   => $selectWeeks,
            ],
            'skiiset' => [
                        'type'      => 'select',
                        'label'      => 'Välj skidor: ',
                        'options'  => $selectSkiisets,
            ],
            'helmet' => [
                        'type'      => 'select',
                        'label'      => 'Välj hjälm: ',
                        'options'  => $selectHelmets,
            ],
            'skiipole' => [
                        'type'      => 'select',
                        'label'      => 'Välj skidstavar: ',
                        'options'  => $selectSkiipoles,
            ],
            'person' => [
                        'type'      => 'select',
                        'label'      => 'Person: ',
                        'options'  => $selectPersons,
            ],
            'submit' => [
                'type'      => 'submit',
                'class'     => 'bigButton',
                'callback'  => function($form) {

                    // PDO::beginTransaction()
                    // 'timestamp'     => getTime(),

                    $sql = "START TRANSACTION;";
                    $this->db->execute($sql);

                    $periodParams = [
                        'Vecka_start'      => $form->Value('start_week'), // HTML 5 gives format like this: 2015-W51
                        'Vecka_slut'        => $form->Value('end_week'),   // Need to alter table to store that
                                                                                                // and write method to substr() it for price calcs.
                    ];
                    $this->db->insert($this->period->table(), $periodParams);
                    $res01 = $this->db->execute();

                    $bookingParams = [
                        'Bokning_faktura_id'=> $form->Value('invoice'),
                        'Kal_prislista_id'      => $form->Value('priceList'),
                        'Kal_period_id'         => $this->db->LastInsertId(),
                        'Bokning_typ_id'      => 3, // The category code of skii-bookings is 3.
                    ];
                    $this->db->insert($this->booking->table(), $bookingParams);
                    $res02 = $this->db->execute();

                    $skiiBookingParams = [
                        'Bokning_id'     => $this->db->LastInsertId(),
                        'Skidor_id'        => $form->Value('skiiset'),
                        'Person_id'            => $form->Value('person'),
                        'Skid_stav_id'    => $form->Value('skiipole'),
                        'Skid_hjälm_id'  => $form->Value('helmet'),
                    ];
                    $this->db->insert($this->table, $skiiBookingParams);
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