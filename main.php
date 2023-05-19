<?php

$re_tracking_nr = "/^[0-9]{18}$/";
$re_delivery_option = "/^[1-4]{1}$/";

#https://www.php.net/manual/en/function.empty.php
if(empty($_POST['tracking_nr']) || empty($_POST['delivery_option'])){
    echo "Ungültiger Request. Bitte versuchen Sie es erneut.";
    return;
} 

if(!preg_match($re_tracking_nr,$_POST['tracking_nr'])) {
    echo "Ungültige Tracking-Nummer im Request. Bitte versuchen Sie es erneut.";
    return;
}

if(!preg_match($re_delivery_option,$_POST['delivery_option'])) {
    echo "Ungültige Lieferoption im Request. Bitte versuchen Sie es erneut.";
    return;
}

$tracking_nr = $_POST['tracking_nr'];
$delivery_option = $_POST['delivery_option'];
echo "Tracking number: $tracking_nr <br>";
echo "Delivery option: $delivery_option <br>";


$con = mysqli_connect("localhost","root","","delivery");
if (!$con) { echo "Keine Verbindung zur Datenbank möglich."; return;}

$check_if_exists_query = "SELECT COUNT(*) AS `exists` FROM delivery.delivery WHERE tracking_nr = ?";
$stmt = mysqli_prepare($con,$check_if_exists_query);

mysqli_stmt_bind_param($stmt,'s',$tracking_nr);
mysqli_stmt_execute($stmt);
$exists_res = mysqli_stmt_get_result($stmt);

if ($exists_res){
    $exists_row = mysqli_fetch_assoc($exists_res);
    
    $cur_date = date('Y-m-d');
    #Case 1: Tracking number exists - update delivery option
    if ($exists_row['exists'] == 1){
        $update_option_query = "UPDATE `delivery` SET `delivery_option`= ?,`last_change_date` = ? WHERE tracking_nr = ?";
        $stmt_1 = mysqli_prepare($con,$update_option_query);
        mysqli_stmt_bind_param($stmt_1,'iss',$delivery_option,$cur_date,$tracking_nr);
        $res_1 = mysqli_stmt_execute($stmt_1);
        echo "Status res_1: $res_1 <br>";
    }

    #Case 2: Tracking number does not exist - insert new entry
    else {
        $insert_query = "INSERT INTO `delivery`(`tracking_nr`, `delivery_option`, `last_change_date`) VALUES (?,?,?)";
        $stmt_2 = mysqli_prepare($con,$insert_query);
        mysqli_stmt_bind_param($stmt_2,'sis',$tracking_nr,$delivery_option,$cur_date);
        $res_2 = mysqli_stmt_execute($stmt_2);
        echo "Status res_2: $res_2 <br>";
    }
}
?>