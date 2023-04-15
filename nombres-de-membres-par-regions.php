<?php
// Connexion à la base de données
$conn = new mysqli('localhost', 'utilisateur', 'mot_de_passe', 'ma_base_de_données');

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// Exécution de la requête SQL
$sql = "SELECT regions_lib, COUNT(*) AS nb FROM membres AS m
        JOIN churchs AS c ON m.`membres_id_church`=c.`churchs_id_region`
        JOIN regions AS r ON c.`churchs_id_region`=r.`regions_id`
        GROUP BY regions_lib
        ORDER BY nb DESC;";
$result = $conn->query($sql);

// Récupération des données de la requête SQL
$data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = array($row["regions_lib"], $row["nb"]);
    }
}

// Fermeture de la connexion à la base de données
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Exemple de graphique avec Chart.js</title>
    <!-- Importation de la librairie Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <!-- Importation de la librairie Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Exemple de graphique avec Chart.js</h1>
        <div class="row">
            <div class="col-md-6">
                <!-- Élément HTML du graphique -->
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Données du graphique
        var data = {
            labels: [<?php foreach ($data as $row) { echo "'".$row[0]."',"; } ?>],
            datasets: [{
                label: 'Nombre de membres par région',
                data: [<?php foreach ($data as $row) { echo $row[1].","; } ?>],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        // Options du graphique
        var options = {
            scales: {
                xAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        };

        // Création du graphique
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'horizontalBar',
            data: data,
            options: options
        });
    </script>
</body>
</html>