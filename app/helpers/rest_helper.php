<?php

function wgerCall($category, $value = null)
{

    $wgerUrl = "https://wger.de/api/v2/" . $category . "/?limit=500&offset=0";
    if ($value != null) {
        $wgerUrl = $wgerUrl . $value . "/";
    }

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $wgerUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


    $resp = curl_exec($curl);

    if ($e = curl_error($curl)) {
        return $e;
    } else {
        $decoded = json_decode($resp);
        return $decoded;
    }

    curl_close($curl);
}

function error($msg) {
    $response = array('success' => false, 'message' => $msg);
    return json_encode($response);
}