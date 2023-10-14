<?php

include 'database.php';

$sql = "SELECT DISTINCT id_customer FROM pmng_tracking";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $customer_id = $row["id_customer"];
        
        // Stampa il nome del cliente
        echo "Cliente: $customer_id\n";

        // Query per ottenere le attività dei dipendenti per il cliente corrente
        $sql = "SELECT id_user, SUM(TIME_TO_SEC(TIMEDIFF(h_to, h_from))) AS total_seconds 
                FROM pmng_tracking 
                WHERE id_customer = $customer_id 
                GROUP BY id_user";
        $activities_result = $conn->query($sql);

        if ($activities_result->num_rows > 0) {
            while ($activity_row = $activities_result->fetch_assoc()) {
                $id_user = $activity_row["id_user"];
                $total_seconds = $activity_row["total_seconds"];

                // Calcola le ore e i minuti totali
                $hours = floor($total_seconds / 3600);
                $minutes = floor(($total_seconds % 3600) / 60);

                // Stampa le ore impiegate da ogni dipendente
                echo "Dipendente $id_user: $hours h $minutes m\n";
            }
        }

        // Query per ottenere il totale delle ore per il cliente corrente
        $sql = "SELECT SUM(TIME_TO_SEC(TIMEDIFF(h_to, h_from))) AS total_seconds 
                FROM pmng_tracking 
                WHERE id_customer = $customer_id";
        $total_result = $conn->query($sql);

        if ($total_result->num_rows > 0) {
            $total_row = $total_result->fetch_assoc();
            $total_seconds = $total_row["total_seconds"];

            // Calcola le ore e i minuti totali
            $hours = floor($total_seconds / 3600);
            $minutes = floor(($total_seconds % 3600) / 60);

            // Stampa il totale delle ore per il cliente corrente
            echo "Totale ore per il cliente: $hours h $minutes m\n\n";
        }
    }
} else {
    echo "Nessun cliente trovato.";
}

// ...

$reportData = array();




$reportData[] = array(
    "cliente" => $customer_id,
    "dipendente" => "Dipendente $id_user",
    "ore_impiegate" => "$hours h $minutes m",
    "totale_ore_cliente" => "Totale ore per il cliente: $hours h $minutes m"
);


echo json_encode($reportData);

// ...





?>