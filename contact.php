<?php
class Contact
{
    private static $contactInstance;
    private $config;
    private $contacts = [];

    private function __construct($config){
        $this->config = $config;
     }

     public static function getContactBook($config){

        if(self::$contactInstance == null){
            self::$contactInstance = new Contact($config);
        }

        return self::$contactInstance;
     }

     public function add($number){
        self::$contactInstance->contacts[] = $number;
     }

     public function edit($previousNumber, $newNumber)
     {
         $key = array_search($previousNumber, self::$contactInstance->contacts);
 
         if ($key !== false) {
             $this->contacts[$key] = $newNumber;
             return true;
         }
 
         return false;
     }

     public function delete($number)
     {
         $key = array_search($number, self::$contactInstance->contacts);
 
         if ($key !== false) {
             unset($this->contacts[$key]);
             self::$contactInstance->contacts = array_values(self::$contactInstance->contacts);
             return true;
         }
 
         return false;
     }

     public function export($number, $csvFile)
     {
         if (in_array($number, self::$contactInstance->contacts)) {
             $csvData = file_get_contents($csvFile);
             $existingNumbers = explode(",", $csvData);
 
             if (in_array($number, $existingNumbers)) {
                 return 'Contact already present in the file';
             } else {
                 $existingNumbers[] = $number;
                 $newCsvData = implode(",", $existingNumbers);
                 file_put_contents($csvFile, $newCsvData);
                 return 'Contact added to the file and saved';
             }
         }
 
         return 'Number not found in the contact array';
     }
 
     public function import($number, $csvFile)
     {
         $csvData = file_get_contents($csvFile);
         $existingNumbers = explode(",", $csvData);
 
         if (!in_array($number, $existingNumbers)) {
             return 'Number does not exist in the file';
         }
 
         if (in_array($number, self::$contactInstance->contacts)) {
             return 'Number is already in your contact';
         }
 
         $this->add($number);
         return 'Number added to your contact';
     }

     public function search($searchNumber)
     {
         $matches = [];
 
         foreach (self::$contactInstance->contacts as $number) {
             if (strpos($number, $searchNumber) !== false) {
                 $matches[] = $number;
             }
         }
 
         return $matches;
     }

     public function getContacts()
     {
         return $this->contacts;
     }
}

$contact = Contact::getContactBook([]);
$contact->add('1111111');
$contact->add('2222222');
$contact->edit('2222222', '555555');
$contact->edit('1111111', '777777');
$contact->add('999999');
$editResult = $contact->edit('999999', '123456');
$contact->add('987654');
$contact->delete('987654');

$contact->export('777777', 'google.csv');
$contact->export('777777', 'google.csv');
$contact->import('420', 'iphone.csv');
$contact->export('123456', 'google.csv');
$contact->export('777777', 'iphone.csv');

$contact->add('01795093337');
$contact->add('01787093337');
$contact->add('01787983337');
$contact->add('01987983337');
$contact->add('01887983337');

$searchResult = $contact->search('017');

$contactsArray = $contact->getContacts();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact List</title>
</head>
<body>

<h2>Contact List</h2>
<ul>
    <?php
    foreach ($contactsArray as $contactNumber) {
        echo "<li>$contactNumber</li>";
    }
    ?>
</ul>

<h3>Search Results</h3>
<ul>
    <?php
    foreach ($searchResult as $result) {
        echo "<li>$result</li>";
    }
    ?>
</ul>
</body>
</html>