
SB Semesterbyar Hem


A web-based interface for managing the booking system of a holliday business.

Based on MySQL using PHP PDO.

Built by Kai Weeks



### Notes and code issues
LastInsertID does not return correct. Read up about PDO methods.
PDO and database transactions.

PDO
0: Stores the SQLSTATE code as defined in the SQL standard
1: Stores the database driver–specific error code
2: Stores the database driver–specific error message

Last insert id has to be taken from within the instance of CDatabase that made the INSERT query.

### License


This software is free software and carries a MIT license.

Copyright (c) 2015 Kai Weeks



Classfile 'CLydia' does not exists.

#0 [internal function]: myAutoloader('CLydia')
#1 [internal function]: spl_autoload_call('CLydia')
#2 /apache2/htdocs/dataDB/vendor/mos/cform/src/HTMLForm/CFormElement.php(37): is_callable('CLydia::Instanc...')
#3 /apache2/htdocs/dataDB/vendor/mos/cform/src/HTMLForm/CFormElementSelect.php(21): Mos\HTMLForm\CFormElement->__construct('invoice', Array)
#4 /apache2/htdocs/dataDB/vendor/mos/cform/src/HTMLForm/CFormElement.php(144): Mos\HTMLForm\CFormElementSelect->__construct('invoice', Array)
#5 /apache2/htdocs/dataDB/vendor/mos/cform/src/HTMLForm/CForm.php(57): Mos\HTMLForm\CFormElement::create('invoice', Array)
#6 /apache2/htdocs/dataDB/src/CBooking_cottage/CBooking_cottage.php(193): Mos\HTMLForm\CForm->create(Array, Array)
#7 /apache2/htdocs/dataDB/webroot/booking.php(39): CBooking_cottage->makeForm(1)
#8 {main}
