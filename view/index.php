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

// Function for getting data
class Dataset {
    function __construct($conn, $column, $sort=true, $timesort=false) {
        $sql = "SELECT " . $column . " AS label, COUNT(*) AS number FROM SimpleWebStats GROUP BY label";
        if ($sort) {
            $sql .= " ORDER BY number DESC";
        }
        if ($timesort) {
            $sql .= " ORDER BY `time`";
        }
        $result = $conn->query($sql);
        $this->labels = [];
        $this->values = [];
        while($row = $result->fetch_assoc()) {
            $this->labels[] = $row["label"];
            $this->values[] = $row["number"];
        }
    }
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0-rc.1/Chart.bundle.js"></script>
    </head>
    <body>
        <!-- Visits -->
        <div id="visits-container">
            <canvas id="visits"></canvas>
        </div>
        <?php $visits = new Dataset($conn, "DATE_FORMAT(time, '%Y-%m-%d')", false, true) ?>
        <script>
        var colours = ['#1f77b4dd', '#ff7f0edd', '#2ca02cdd', '#d62728dd',
              '#9467bddd', '#8c564bdd', '#e377c2dd', '#7f7f7fdd',
              '#bcbd22dd', '#17becfdd']
        var ctxv = document.getElementById('visits').getContext('2d');
        var VisitsChart = new Chart(ctxv, {
            type: 'line',
            data: {
                labels: ["<?php echo implode('","', $visits->labels) ?>"],
                datasets: [{
                    label: "User visits each day",
                    data: [<?php for ($i=0; $i<count($visits->values); $i++) { ?>
                        {
                            t: "<?php echo $visits->labels[$i] ?>",
                            y: <?php echo $visits->values[$i] ?>
                        },
                    <?php } ?>],
                    borderColor: "#1f77b4",
                    backgroundColor : "#1f77b450"
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        type: 'time'
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
            <?php $mobile = new Dataset($conn, "mobile", false) ?>
            <script>
            var ctxm = document.getElementById('mobile').getContext('2d');
            var MobileChart = new Chart(ctxm, {
                type: 'pie',
                data: {
                    labels: ["<?php echo implode('","', $mobile->labels) ?>"],
                    datasets: [{
                        label: "On mobile?",
                        data: [<?php echo implode(',', $mobile->values) ?>],
                        "backgroundColor": colours
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    title: {
                        display: true,
                        fontSize: 20,
                        text: "User on mobile?"
                    }
                }
            });
            </script>

            <!-- Referrer -->
            <div id="referrer-container">
                <canvas id="referrer"></canvas>
            </div>
            <?php $referrer = new Dataset($conn, "referrer") ?>
            <script>
            var ctxr = document.getElementById('referrer').getContext('2d');
            var ReferrerChart = new Chart(ctxr, {
                type: 'pie',
                data: {
                    labels: ["<?php echo implode('","', $referrer->labels) ?>"],
                    datasets: [{
                        label: "On mobile?",
                        data: [<?php echo implode(',', $referrer->values) ?>],
                        "backgroundColor": colours
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    title: {
                        display: true,
                        fontSize: 20,
                        text: "Referrer"
                    }
                }
            });
            </script>

            <!-- Language -->
            <div id="lang-container">
                <canvas id="lang"></canvas>
            </div>
            <?php $lang = new Dataset($conn, "lang") ?>
            <script>
            var ctxr = document.getElementById('lang').getContext('2d');
            var ReferrerChart = new Chart(ctxr, {
                type: 'pie',
                data: {
                    labels: ["<?php echo implode('","', $lang->labels) ?>"],
                    datasets: [{
                        label: "On mobile?",
                        data: [<?php echo implode(',', $lang->values) ?>],
                        "backgroundColor": colours
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    title: {
                        display: true,
                        fontSize: 20,
                        text: "Language"
                    }
                }
            });
            </script>
        </div>
    </body>
</html>

<?php $conn->close(); ?>
