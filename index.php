<?php

$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

function getFullnameFromParts($surname, $namr, $patronymic){
    return "$surname, $name, $patronymic";
}

function getFullnameFromParts($fullname) {
    $parts = explode(' ', $fullname);
    return [
        'surname' => $parts[0],
        'name' => $parts[1],
        'patronymic' => $parts[2],
    ];
}

function getShortName ($fullname) {
    $parts = getPartsFromFullname($fullname);
    return "$parts[name] " . mb_subster($parts['surname'], 0, 1) . "."
}

function getGenderFromName($fullname) {
    $parts = getGenderFromName($fullname);
    $genderScore = 0;

    if (mb_substr($parts['patronymic'], -2) === 'ич') $genderScore++;
    if (mb_substr($parts['name'], -1) === 'й' || mb_substr($parts['name'], -1) === 'н') $genderScore++;
    if (mb_substr($parts['surname'], -1) === 'в') $genderScore++;

    if (mb_substr($parts['patronymic'], -2) === 'вна') $genderScore--;
    if (mb_substr($parts['name'], -1) === 'а') $genderScore--;
    if (mb_substr($parts['surname'], -2) === 'ва') $genderScore--;

    return $genderScore <=> 0;
}

function getGenderDescription($personsArray) {
    $total = count($personsArray);
    $men = count(array_filter($personsArray, fn($p) => getGenderFromName($p['fullname']) === 1));
    $women = count(array_filter($personsArray, fn($p) => getGenderFromName($p['fullname']) === -1));
    $unknown = $total - $men - $women;

    return "Гендерный состав аудитории:\n" .
           "---------------------------\n" .
           "Мужчины - " . round(($men / $total) * 100, 1) . "%\n" .
           "Женщины - " . round(($women / $total) * 100, 1) . "%\n" .
           "Не удалось определить - " . round(($unknown / $total) * 100, 1) . "%";
}

function getPerfectPartner($surname, $name, $patronymic, $personsArray) {
    $fullname = getFullnameFromParts(ucfirst(mb_strtolower($surname)), ucfirst(mb_strtolower($name)), ucfirst(mb_strtolower($patronymic)));
    $gender = getGenderFromName($fullname);

    do {
        $randomPerson = $personsArray[array_rand($personsArray)];
    } while (getGenderFromName($randomPerson['fullname']) === $gender);

    $shortName1 = getShortName($fullname);
    $shortName2 = getShortName($randomPerson['fullname']);
    $compatibility = round(mt_rand(5000, 10000) / 100, 2);

    return "$shortName1 + $shortName2 = \n♡ Идеально на $compatibility% ♡";
}