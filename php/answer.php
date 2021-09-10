<?php

require_once("json_encode.php");

session_start();

if (!isset($_SESSION['requests']) || !is_array($_SESSION['requests'])) {
    $_SESSION['requests'] = [];
}

function validateX($xVal)
{
    $xVal_array = array("-2", "-1.5", "-1", "-0.5", "0", "0.5", "1", "1.5", "2");
    if (!isset($xVal)) {

        return false;
}
    return in_array($xVal, $xVal_array);
}

function validateY($yVal)
{
    if (!isset($yVal))
        return false;

    $numY = str_replace(',', '.', $yVal);
    return is_numeric($numY) && $numY > -5 && $numY < 3;
}

function validateR($rVal)
{
    $rVal_array = array("1", "1.5", "2", "2.5", "3");
    if (!isset($rVal)) {
        return false;
    }
    return in_array($rVal, $rVal_array);
}

function validateForm($xVal, $yVal, $rVal)
{
    return validateX($xVal) && validateY($yVal) && validateR($rVal);
}

function checkHit($xVal, $yVal, $rVal)
{
    $count = 0;
    if ($xVal <= 0 && $yVal >= 0) {
        if ($xVal >= -$rVal && $yVal <= $rVal / 2) {
            $count = $count + 1;
        }
    }
    if ($xVal >= 0 && $yVal >= 0) {
        if ($xVal * 2 + $yVal <= $rVal) {
            $count = $count + 1;
        }
    }
    if ($xVal >= 0 && $yVal <= 0) {
        if ($xVal * $xVal + $yVal * $yVal <= $rVal * $rVal) {
            $count = $count + 1;
        }
    }

    return !($count == 0);
}


$xVal = $_GET['x'];
$yVal = $_GET['y'];
$rVal = $_GET['r'];
$timezone = $_GET['timezone'];
$results = array();

$isValid = validateForm($xVal, $yVal, $rVal);
$isHit = checkHit($xVal, $yVal, $rVal);
$currentTime = date('H:i:s', time() - $timezone * 60);
$executionTime = round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 7);

array_push($results, array(
    "validate" => $isValid,
    "x" => $xVal,
    "y" => $yVal,
    "r" => $rVal,
    "currentTime" => $currentTime,
    "execTime" => $executionTime,
    "isHit" => $isHit
));

echo toJSON($results);

?>