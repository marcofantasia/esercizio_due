<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Elenco attività dipendenti e clienti</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
  <h1>Elenco attività per dipendenti e clienti</h1>
  
  <table id="reportTable" class="table table-success">
    
    <tr>
      <th scope="col">Cliente</th>
      <th scope="col">Dipendente</th>
      <th scope="col">Ore impiegate</th>
      <th scope="col">Totale ore cliente</th>
    </tr>
    
    <tbody id="data-table">

     <?php
      
      include 'database.php';

       // Query per ottenere i dati
       $sql = "SELECT id_customer, id_user, h_to, h_from FROM pmng_tracking";
       $result = $conn->query($sql);

       if ($result->num_rows > 0) {
           $currentCustomer = null;
           $currentEmployee = null;
           $totalHoursForCustomer = 0;
           $totalHoursForEmployee = 0;

           while ($row = $result->fetch_assoc()) {
               $idCustomer = $row["id_customer"];
               $idUser = $row["id_user"];
               $hTo = strtotime($row["h_to"]);
               $hFrom = strtotime($row["h_from"]);
               $hoursDiff = ($hTo - $hFrom) / 3600;

               if ($currentCustomer !== $idCustomer) {
                   if ($currentCustomer !== null) {
                       // Stampa il totale per il cliente precedente
                       echo "<tr>";
                       echo "<td>Cliente $currentCustomer</td>";
                       echo "<td class='text-danger'>Totale ore cliente:</td>";
                       echo "<td></td>";
                       echo "<td class='text-danger'>" . formatHours($totalHoursForCustomer) . "</td>";
                       echo "</tr>";
                   }

                   $currentCustomer = $idCustomer;
                   $totalHoursForCustomer = 0;
               }

               if ($currentEmployee !== $idUser) {
                   if ($currentEmployee !== null) {
                       // Stampa il totale per il dipendente precedente
                       echo "<tr>";
                       echo "<td>Cliente $currentCustomer</td>";
                       echo "<td>Dipendente $currentEmployee</td>";
                       echo "<td></td>";
                       echo "<td>" . formatHours($totalHoursForEmployee) . "</td>";
                       echo "</tr>";
                   }

                   $currentEmployee = $idUser;
                   $totalHoursForEmployee = 0;
               }

               // Stampa i dati dell'attività
               echo "<tr>";
               echo "<td>Cliente $idCustomer</td>";
               echo "<td>Dipendente $idUser</td>";
               echo "<td>" . formatHours($hoursDiff) . "</td>";
               echo "<td></td>";
               echo "</tr>";

               $totalHoursForCustomer += $hoursDiff;
               $totalHoursForEmployee += $hoursDiff;
           }

           // Stampa i totali per l'ultimo cliente e dipendente
           echo "<tr>";
           echo "<td>Cliente $currentCustomer</td>";
           echo "<td>Dipendente $currentEmployee</td>";
           echo "<td></td>";
           echo "<td>" . formatHours($totalHoursForEmployee) . "</td>";
           echo "</tr>";

           echo "<tr>";
           echo "<td>Cliente $currentCustomer</td>";
           echo "<td>Totale ore cliente:</td>";
           echo "<td></td>";
           echo "<td>" . formatHours($totalHoursForCustomer) . "</td>";
           echo "</tr>";
       } else {
           echo "Nessun dato trovato.";
       }

       $conn->close();

       // Funzione per formattare le ore
       function formatHours($totalHours) {
           $hours = floor($totalHours);
           $minutes = ($totalHours - $hours) * 60;
           return sprintf("%02d h %02d m", $hours, $minutes);
       }
       ?>



      

    </tbody>
    
  </table>
  
  
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>