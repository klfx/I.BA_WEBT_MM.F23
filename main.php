<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script src="js/script.js"></script>
    <title>WEBT Post Bestätigung</title>
</head>
<body>
<header class="w3-container w3-black">
        <nav class="w3-bar w3-black">
            <a class="w3-button" href="index.html"><- Home</a>
        </nav>
    </header>
<?php

$re_tracking_nr = "/^[0-9]{18}$/";
$re_delivery_option = "/^[1-4]{1}$/";

$delivery_option_arr = [
    1 => "Deponie beim Hauseingang",
    2 => "Deponie auf Etage",
    3 => "Zustellung an Nachbarn",
    4 => "Abholung Filiale",
];

function printError($error_msg){
    echo "<div class='w3-panel w3-pale-red w3-card-2' id='confirmation_error'>
        <h3>Fehler bei der Anfrage</h3>
        <p>$error_msg</p>
        </div>";
} 

function printSuccess($tracking_nr,$delivery_option){
    global $delivery_option_arr;
    echo "<div class='w3-panel w3-pale-green w3-card-2' id='confirmation_success'>
        <h3>Zustelloption geändert!</h3>
        <p>Die Zustelloption für <b>$tracking_nr</b> wurde erfolgreich auf <b>$delivery_option_arr[$delivery_option]</b> geändert</p>
        </div>";
}

# POST Request Server-Side Validation
if(empty($_POST['tracking_nr']) || empty($_POST['delivery_option'])){
    printError("Ungültiger Request. Bitte versuchen Sie es erneut.");
    return;
} 

if(!preg_match($re_tracking_nr,$_POST['tracking_nr'])) {
    printError("Ungültige Tracking-Nummer im Request. Bitte versuchen Sie es erneut.");
    return;
}

if(!preg_match($re_delivery_option,$_POST['delivery_option'])) {
    printError("Ungültige Lieferoption im Request. Bitte versuchen Sie es erneut.");
    return;
}

$tracking_nr = $_POST['tracking_nr'];
$delivery_option = $_POST['delivery_option'];

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
        if ($res_1 == 1){
            printSuccess($tracking_nr,$delivery_option);

            if (isset($_COOKIE['changes_count'])){
                $changes_count = $_COOKIE['changes_count'];
                $changes_count++;
                setcookie("changes_count",$changes_count,['expires' => time()+3600, 'samesite' => 'strict']);
            }
            else {
                setcookie("changes_count",1,['expires' => time()+3600, 'samesite' => 'strict']);
            }
        }
        else {
            printError("Interner Fehler bei der Verarbeitung. Bitte versuchen Sie es erneut.");
        }
    }

    #Case 2: Tracking number does not exist or access denied - print error
    else {
        printError("Anfrage fehlgeschlagen. Bitte überprüfen Sie die Tracking-Nummer und stellen Sie sicher, dass Sie auf diese Sendung berechtigt sind.");
    }
}

#Print Summary over all Deliveries (not prepared b.c. static query without parameters)
$summary_query = "SELECT * FROM delivery.delivery";
$res_summary = mysqli_query($con,$summary_query);
echo "<div class='w3-panel w3-pale-blue w3-card-2' id='summary'>
<h3>Ihre Sendungen</h3>";
if (isset($_COOKIE['changes_count'])){
    $changes_count = $_COOKIE['changes_count'];
    echo "<p class='changes_count_label'><i>Sie haben in der letzten Stunde bereits $changes_count Zustelloptionen geändert!</i></p>";
}
if ($res_summary){
    while ($row = mysqli_fetch_assoc($res_summary)){
        #echo "<p>Tracking-Nummer: <b>$row['tracking_nr']</b> | Zustelloption: <b>$delivery_option_arr[$row['delivery_option']]</b> | Letzte Änderung: <b>$row['last_change_date']";
        echo "<p>Nr. ".$row['tracking_nr']." | (<b>".$delivery_option_arr[$row['delivery_option']]."</b>, letzte Änderung: ".$row['last_change_date'].")</p>";
    }
}
echo "</div>";

?>

</body>
</html>