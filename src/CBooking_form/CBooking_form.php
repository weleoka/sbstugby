<?php


/**
 * CBooking_form, class that represents booking forms
 *
 */
class CBooking_form {

  /*
   * Constructor that accepts $db credentials and creates CDatabase object
   *
   */
    public function __construct($dbCredentials, $tableNames) {
            $this->db = new CDatabase($dbCredentials);
            $this->tableNames = $tableNames;
            $this->form = new \Mos\HTMLForm\CForm();
            $this->person = new CPerson($dbCredentials, $tableNames);
            $this->invoice = new CInvoice($dbCredentials, $tableNames);
    }

    /*
     * Print form
     *
     * @return string with html to display content
     */
    public function getHTML () {
        $this->form->getHTML();
    }


    /*
     * Make form according to what category it is.
     *
     * @params integer.
     * @return void
     */
    public function makeForm ($category) {
        if ($category = 1) {
            $formFields = $this->makeCottageFormFields(1);
        } else if ($category = 2) {
            $formFields = $this->makeBikeFormFields(2);
        } else if ($category = 3) {
            $formFields = $this->makeSkiiFormFields(3);
        }

        $form = $this->form->create([], $formFields);

        // Check the status of the form.
        $status = $form->check();

        if ($status === true) {
            print('Bokningen genomfördes');

        } else if ($status === false) {
            print('Din bokning kunde inte genomföras.');
        }
    }


    /*
     * Make the cottage form fields.
     *
     * @return array
     */
    public function makeCottageFormFields ($categoryCode) {
        $form = $this->form;

        $invoices = $this->invoice->getAll();
        foreach ($invoices as $invoice) {
          $selectInvoices[ $invoice->id ] = $invoice->Betalperson_id;
        }

        $persons = $this->person->getAll();
        foreach ($persons as $person) {
          $selectPersons[ $person->id ] = $person->Namn;
        }

        // $selectPersons = array($persons => $person);
        // $selectInvoices = array($invoices => $invoice);

        return [
            'invoice' => [
                        'type'      => 'select',
                        'label'      => 'Välj Faktura: ',
                        'options'  => $selectInvoices,
            ],
            'first_week' => [
                'type'        => 'textarea',
                'label'       => '',
                'placeholder' => 'Skriv ett svar',
                'validation'  => ['not_empty'],
            ],
            'last_week' => [
                'type'        => 'textarea',
                'label'       => '',
                'placeholder' => 'Skriv ett svar',
                'validation'  => ['not_empty'],
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
                'callback'  => function($form) use ($categoryCode){

                    $sql = "
                        START TRANSACTION;
                        INSERT INTO {$this->tableNames['bookings']} (Faktura_id, Kal_prislista_id, Kal_period_id, Bokning_typ_id)
                        VALUES (?, ?, ?, $categoryCode);
                        INSERT INTO {$this->tableNames['cottageBookings']} (Bokning_id, Stuga_id, person01, person02, person03, person04, person05, person06, person07, person08, )
                        VALUES (?, ?, ?, $categoryCode);
                        COMMIT;";

                    $params = array ([
                    /* `Faktura_id` INT NULL ,
                      `Kal_prislista_id` INT NOT NULL ,
                      `Kal_period_id` INT NOT NULL ,
                      `Bokning_typ_id` VARCHAR(255) NOT NULL
                      isset( $tag ) ? ''          : 'välj kategori: ', */

                        'invoice'           => $form->Value('invoice'),
                        'priceList'         => $form->Value('priceList'),
                        'period'            => $form->Value('period'),
                        // 'timestamp'     => getTime(),

                     /* `Bokning_id` INT NOT NULL ,
                      `Stuga_id` INT NULL ,
                      `Person_01` INT NULL DEFAULT NULL */
                        'last_insert_id'  => $this->db->getLastID(),
                        'cottage'          => $form->Value('cottage'),
                        'person01'       => $form->Value('person01'),
                        'person02'       => $form->Value('person02'),
                        'person03'       => $form->Value('person03'),
                        'person04'       => $form->Value('person04'),
                        'person05'       => $form->Value('person05'),
                        'person06'       => $form->Value('person06'),
                        'person07'       => $form->Value('person07'),
                        'person08'       => $form->Value('person08'),
                    ]);

                    $res = $this->db->ExecuteQuery($sql, $params);
                    if($res) {
                        $output = 'Informationen sparades.';
                    } else {
                        $output = 'Informationen sparades EJ.<br><pre>' . print_r($this->db->ErrorInfo(), 1) . '</pre>';
                    }
                    return $output;
                }
            ],
        ];
    }







    /*
     * Make the bike form fields.
     *
     * @return array
     */
    public function makeBikeFormFields () {
        return [
            'content' => [
                'type'        => 'textarea',
                'label'       => '',
                'placeholder' => 'Skriv ett svar',
                'validation'  => ['not_empty'],
            ],
            'submit' => [
                'type'      => 'submit',
                'class'     => 'bigButton',
                'callback'  => function($form) use ($question){


                $this->answers->save([
                        'userID'            => $user->id,
                        'parentID'      => $question->id,
                        'parentTitle'   => $question->title,
                'name'          => $user->name,
                'content'       => $form->Value('content'),
                'email'         => $user->email,
                'timestamp'     => getTime(),
                ]);

                return true;
                }
            ],
        ];
    }

    /*
     * Make the skii form fields.
     *
     * @return array
     */
    public function makeSkiiFormFields () {
        return [
            'content' => [
                'type'        => 'textarea',
                'label'       => '',
                'placeholder' => 'Skriv ett svar',
                'validation'  => ['not_empty'],
            ],
            'submit' => [
                'type'      => 'submit',
                'class'     => 'bigButton',
                'callback'  => function($form) use ($question){


                $this->answers->save([
                        'userID'            => $user->id,
                        'parentID'      => $question->id,
                        'parentTitle'   => $question->title,
                'name'          => $user->name,
                'content'       => $form->Value('content'),
                'email'         => $user->email,
                'timestamp'     => getTime(),
                ]);

                return true;
                }
            ],
        ];
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