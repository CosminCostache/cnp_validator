<?php

$_controlKey = [2, 7, 9, 1, 4, 6, 3, 5, 8, 2, 7, 9];
$_countyCode = [
    '01' => 'Alba',
    '02' => 'Arad',
    '03' => 'Arges',
    '04' => 'Bacau',
    '05' => 'Bihor',
    '06' => 'Bistrita-Nasaud',
    '07' => 'Botosani',
    '08' => 'Brasov',
    '09' => 'Braila',
    '10' => 'Buzau',
    '11' => 'Caras-Severin',
    '12' => 'Cluj',
    '13' => 'Constanta',
    '14' => 'Covasna',
    '15' => 'Dambovita',
    '16' => 'Dolj',
    '17' => 'Galati',
    '18' => 'Gorj',
    '19' => 'Harghita',
    '20' => 'Hunedoara',
    '21' => 'Ialomita',
    '22' => 'Iasi',
    '23' => 'Ilfov',
    '24' => 'Maramures',
    '25' => 'Mehedinti',
    '26' => 'Mures',
    '27' => 'Neamt',
    '28' => 'Olt',
    '29' => 'Prahova',
    '30' => 'Satu Mare',
    '31' => 'Salaj',
    '32' => 'Sibiu',
    '33' => 'Suceava',
    '34' => 'Teleorman',
    '35' => 'Timis',
    '36' => 'Tulcea',
    '37' => 'Vaslui',
    '38' => 'Valcea',
    '39' => 'Vrancea',
    '40' => 'Bucuresti',
    '41' => 'Bucuresti S.1',
    '42' => 'Bucuresti S.2',
    '43' => 'Bucuresti S.3',
    '44' => 'Bucuresti S.4',
    '45' => 'Bucuresti S.5',
    '46' => 'Bucuresti S.6',
    '51' => 'Calarasi',
    '52' => 'Giurgiu'
];

$cnp = $_POST['cnp'];
$_cnp = str_split($cnp);
$fullYear = null;
$isRomanian = null;
$birthDate = null;
$birthCounty = null;
$moduleResult = null;
$controlSum = 0;
$errors = [];
$data = [];

//Check length
if (count($_cnp) != 13) {
    $errors['length'] = "The CNP is required a length of 13 characters.";
}

//Check format
foreach ($_cnp as $digit) {
    if (!is_numeric($digit)) {
        $errors['format'] = "The CNP must be digit format.";
        break;
    }
}

//Check sex and set full year
$sex = $_cnp[0];
$year = $_cnp[1] . $_cnp[2];
$month = $_cnp[3] . $_cnp[4];
$day = $_cnp[5] . $_cnp[6];

if (!in_array($sex, range(1, 9))) {
    $errors['sex'] = "The first digit of CNP must be between 1-9";
}

switch ($sex) {
    case 1:
    case 2:
        $fullYear = 1900 + $year;
        $isRomanian = true;
        break;
    case 3:
    case 4:
        $fullYear = 1800 + $year;
        $isRomanian = true;
        break;
    case 5:
    case 6:
        $fullYear = 2000 + $year;
        $isRomanian = true;
        break;
    default:
        $isRomanian = false;
}

//Check and set date
if ($fullYear) {
    if (checkdate($month, $day, $fullYear) == false) {
        $errors['date'] = "The data submitted is not a valid date.";
    }

    $unixYear = strtotime($fullYear . "-" . $month . "-" . $day);
    $birthDate = date('Y-m-d', $unixYear);
}

//Check county
$county = $_cnp[7] . $_cnp[8];
$countyCode = array_slice($_cnp, 9, 11);

if (!array_key_exists($county, $_countyCode)) {
    $errors['county'] = "The county submitted is not a valid county.";
} else {
    $birthCounty = $_countyCode[$county];
}

//Calculate control key
$controlKey = end($_cnp);

array_pop($_cnp);

foreach ($_cnp as $key => $value) {
    $controlSum += $value * $_controlKey[$key];
}

if ($controlSum % 11 == 10) {
    $moduleResult = 1;
} else {
    $moduleResult = $controlSum % 11;
}

if ($moduleResult != $controlKey) {
    $errors['controlKey'] = "Not a valid control key.";
} else {
    $data['message'] = "Success! The CNP is valid.";
}

if (!empty($errors)) {
    $data['success'] = false;
    $data['errors'] = $errors;
} else {
    $data['success'] = true;
    $data['message'];
}

echo json_encode($data);