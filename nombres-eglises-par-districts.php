<?php
// Connexion à la base de données
$db_host = 'localhost';
$db_user = 'utilisateur';
$db_pass = 'motdepasse';
$db_name = 'nom_de_la_base_de_donnees';

$db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);

// Exécution de la requête SQL
$sql = "SELECT districts_lib, COUNT(*) AS nb FROM churchs AS c
        JOIN districts AS d ON c.`churchs_id_district`=d.`districts_id`
        GROUP BY churchs_id_district
        ORDER BY nb DESC";

$stmt = $db->query($sql);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Préparation des données pour le graphique camembert
$labels = array();
$values = array();
foreach ($data as $row) {
    $labels[] = $row['districts_lib'];
    $values[] = $row['nb'];
}
$labels = json_encode($labels);
$values = json_encode($values);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Exemple de graphique camembert avec Chart.js</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
</head>
<body>

<div class="container">
    <h1>Graphique camembert des églises par district</h1>
    <canvas id="myChart"></canvas>
</div>

<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?php echo $labels; ?>,
        datasets: [{
            label: 'Nombre d\'églises',
            data: <?php echo $values; ?>,
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