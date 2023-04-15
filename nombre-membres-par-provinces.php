<?php
// Connexion à la base de données
$conn = mysqli_connect("localhost", "utilisateur", "motdepasse", "basededonnees");

// Requête SQL pour récupérer les données
$sql = "SELECT provinces_lib, COUNT(*) AS nb FROM membres AS m
        JOIN churchs AS c ON m.`membres_id_church`=c.`churchs_id_region`
        JOIN regions AS r ON c.`churchs_id_region`=r.`regions_id`
        JOIN provinces AS p ON r.`regions_id_province`=p.`provinces_id`
        GROUP BY provinces_lib
        ORDER BY nb DESC";

$result = mysqli_query($conn, $sql);

// Tableau pour stocker les données du graphique
$provinces = array();
$nbMembres = array();

// Récupération des données
while ($row = mysqli_fetch_assoc($result)) {
    $provinces[] = $row["provinces_lib"];
    $nbMembres[] = $row["nb"];
}

// Fermeture de la connexion à la base de données
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Graphique en camembert avec Chart.js</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Graphique en camembert avec Chart.js</h1>
        <canvas id="chart"></canvas>
    </div>

    <!-- Inclusion de la bibliothèque Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Script pour créer le graphique -->
    <script>
        var ctx = document.getElementById('chart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($provinces); ?>,
                datasets: [{
                    label: 'Nombre de membres',
                    data: <?php echo json_encode($nbMembres); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
</body>
</html>