<?php
/**
 * CCottageBooking, class that represents booking cottages.
 *
 */
class CCottageBooking {

    protected $table;
  /*
   * Constructor that accepts $db credentials and creates CDatabase object
   *
   */
    public function __construct($db, $tn) {
            $this->db = $db; // new CDatabase($dbCredentials);
            $this->tn = $tn;
            $this->table = $this->tn['cottageBooking'];


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
     * @params integer $categoryCode The code to insert into the booking.
     * @return void
     */
    public function makeForm ($categoryCode) {

        $invoices = $this->invoice->getAll();
        foreach ($invoices as $invoice) {
          $selectInvoices[ $invoice->id ] = $invoice->namn;
        }

        $priceLists = $this->priceList->getAll();
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

        $cottages = $this->cottage->getAll();
        foreach ($cottages as $cottage) {
          $selectCottages[ $cottage->id ] = $cottage->id;
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
                        'type'      => 'select',
                        'label'      => 'Välj startvecka: ',
                        'options'  => $selectWeeks,
            ],
            'last_week' => [
                        'type'      => 'select',
                        'label'      => 'Välj slutvecka: ',
                        'options'  => $selectWeeks,
            ],
            'cottage' => [
                        'type'      => 'select',
                        'label'      => 'Välj stuga: ',
                        'options'  => $selectCottages,
            ],
            'person01' => [
                        'type'      => 'select',
                        'label'      => 'Person 01: ',
                        'options'  => $selectPersons,
            ],
            'person02' => [
                        'type'      => 'select',
                        'label'      => 'Person 02: ',
                        'options'  => $selectPersons,
            ],
            'person03' => [
                        'type'      => 'select',
                        'label'      => 'Person 03: ',
                        'options'  => $selectPersons,
            ],
            'person04' => [
                        'type'      => 'select',
                        'label'      => 'Person 04: ',
                        'options'  => $selectPersons,
            ],
            'person05' => [
                        'type'      => 'select',
                        'label'      => 'Person 05: ',
                        'options'  => $selectPersons,
            ],
            'person06' => [
                        'type'      => 'select',
                        'label'      => 'Person 06: ',
                        'options'  => $selectPersons,
            ],
            'person07' => [
                        'type'      => 'select',
                        'label'      => 'Person 07: ',
                        'options'  => $selectPersons,
            ],
            'person08' => [
                        'type'      => 'select',
                        'label'      => 'Person 08: ',
                        'options'  => $selectPersons,
            ],
            'submit' => [
                'type'      => 'submit',
                'class'     => 'bigButton',
                'callback'  => function($form) use ($categoryCode) {

                    // PDO::beginTransaction()
                    // 'timestamp'     => getTime(),

                    $sql = "START TRANSACTION;";
                    $this->db->execute($sql);
                    $output = 'Started transaction: ';

                    $periodParams = [
                        'Vecka_start'      => $form->Value('first_week'),
                        'Vecka_slut'        => $form->Value('last_week'),
                    ];
                    $res01 = $this->period->insertNewPeriod($periodParams);

                    $bookingParams = [
                        'Bokning_faktura_id'=> $form->Value('invoice'),
                        'Kal_prislista_id'      => $form->Value('priceList'),
                        'Kal_period_id'         => $this->db->LastInsertId(),
                        'Bokning_typ_id'      => $categoryCode,

                    ];
                    $res02 = $this->booking->insertNewBooking($bookingParams);

                    $cottageBookingParams = [
                        'Bokning_id'       => $this->db->LastInsertId(),
                        'Stuga_id'          => $form->Value('cottage'),
                        'Person01'       => $form->Value('person01'),
                        'Person02'       => $form->Value('person02'),
                        'Person03'       => $form->Value('person03'),
                        'Person04'       => $form->Value('person04'),
                        'Person05'       => $form->Value('person05'),
                        'Person06'       => $form->Value('person06'),
                        'Person07'       => $form->Value('person07'),
                        'Person08'       => $form->Value('person08'),
                    ];
                    $res03 = $this->insertNewCottageBooking($cottageBookingParams);

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
     * Creates new content in db for a cottage booking
     *
     */
    public function insertNewCottageBooking($params) {
        $this->db->insert($this->table, $params);
        // $sql = "INSERT INTO {$this->tn['cottageBookings']} (Bokning_id, Stuga_id, person01, person02, person03, person04, person05, person06, person07, person08, )
           //         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
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

  /*

   <form method=post>
      <fieldset>
            <legend>Uppdatera innehåll</legend>
                <p><label>Titel:<br/><input type='text' name='title' value='{$title}'/></label></p>
                <p><label>Text:<br/><textarea name='data'>{$data}</textarea></label></p>
                <p><label>Filter:<br/><input type='text' name='filter' value='{$filter}'/></label></p>
                <p><label>Publiseringsdatum:<br/><input type='text' name='published' value='{$published}'/></label></p>
                <p class=buttons>
                    <input type='submit' name='save' value='Spara'/>
                    <input type='submit' name='delete' value='Radera'/>
                    <input type='reset' value='Återställ'/></p>

                <a href='view.php'>Visa alla</a></p>
                <output>{$output}</output>
        </fieldset>
    </form>

    */