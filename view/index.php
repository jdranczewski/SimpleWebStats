<!-- This is an example of what can be done with the data -->
<?php
$servername = "server";
$username = "username";
$password = "password";
$dbname = "myDB";

// Establish a connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>SimpleWebStats - data</title>
        <meta name="description" content="View data collected by SimpleWebStats">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="view.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    </head>
    <body>
        <!-- Visits -->
        <div id="visits-container">
            <canvas id="visits"></canvas>
        </div>
        <?php
        $sql = "SELECT DATE_FORMAT(time, '%d-%m-%Y') AS label, COUNT(*) AS number FROM SimpleWebStats GROUP BY DATE_FORMAT(time, '%d-%m-%Y')";
        $result = $conn->query($sql);
        $labels = [];
        $values = [];
        while($row = $result->fetch_assoc()) {
            $labels[] = $row["label"];
            $values[] = $row["number"];
        }
        ?>
        <script>
        var ctxv = document.getElementById('visits').getContext('2d');
        var VisitsChart = new Chart(ctxv, {
            type: 'line',
            data: {
                labels: ["<?php echo implode('","', $labels) ?>"],
                datasets: [{
                    label: "User visits each day",
                    data: [<?php echo implode(",", $values) ?>]
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                maintainAspectRatio: false
            }
        });
        </script>

        <div id="bottom">
            <!-- Mobile -->
            <div id="mobile-container">
                <canvas id="mobile"></canvas>
            </div>
            <?php
            $sql = "SELECT mobile AS label, COUNT(*) AS number FROM SimpleWebStats GROUP BY mobile";
            $result = $conn->query($sql);
            $labels = [];
            $values = [];
            while($row = $result->fetch_assoc()) {
                $labels[] = $row["label"];
                $values[] = $row["number"];
            }
            ?>
            <script>
            var ctxm = document.getElementById('mobile').getContext('2d');
            var MobileChart = new Chart(ctxm, {
                type: 'pie',
                data: {
                    labels: ["<?php echo implode('","', $labels) ?>"],
                    datasets: [{
                        label: "On mobile?",
                        data: [<?php echo implode(',', $values) ?>],
                        "backgroundColor": ["rgb(255, 205, 86)", "rgb(54, 162, 235)"]
                    }]
                },
                options: {
                    maintainAspectRatio: false
                }
            });
            </script>

            <!-- Referrer -->
            <div id="referrer-container">
                <canvas id="referrer"></canvas>
            </div>
            <?php
            $sql = "SELECT referrer AS label, COUNT(*) AS number FROM SimpleWebStats GROUP BY referrer";
            $result = $conn->query($sql);
            $labels = [];
            $values = [];
            while($row = $result->fetch_assoc()) {
                $labels[] = $row["label"];
                $values[] = $row["number"];
            }
            ?>
            <script>
            var ctxr = document.getElementById('referrer').getContext('2d');
            var ReferrerChart = new Chart(ctxr, {
                type: 'pie',
                data: {
                    labels: ["<?php echo implode('","', $labels) ?>"],
                    datasets: [{
                        label: "On mobile?",
                        data: [<?php echo implode(',', $values) ?>],
                        "backgroundColor": ["rgb(255, 205, 86)", "rgb(54, 162, 235)"]
                    }]
                },
                options: {
                    maintainAspectRatio: false
                }
            });
            </script>
        </div>
    </body>
</html>

<?php $conn->close(); ?>
