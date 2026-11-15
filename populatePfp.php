<?php

use Models\Pagination;

require_once("Models/UserDataSet.php");
require_once("Models/Pagination.php");
$userDataSet = new UserDataSet();
$pagination = new Pagination(1, 10, $userDataSet->getTotalUserCount());

foreach ($userDataSet->fetchAllUsers() as $userData) {
    // 🌐 1. Fetch a random photo URL from the API
    $jsonResponse = file_get_contents('https://randomuser.me/api/?results=' . $pagination->getLimit() . '&inc=picture');
    $data = JSON_decode($jsonResponse, true);

    if ($data['results'][0]['picture']['large']) { // 🖼️ 3. Grab the image URL from the nested array structure
        $pictureUrl = $data['results'][0]['picture']['large'];
        $userDataSet->updatePhoto($userData->getId(), $pictureUrl);
    }
}
