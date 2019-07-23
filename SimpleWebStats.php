<?php
header("Access-Control-Allow-Origin: *");

if (isset($_POST["visit"])) {
    header("HTTP/1.0 204 No Content");

    // Options
    $servername = "server";
    $username = "username";
    $password = "password";
    $dbname = "myDB";
    // Additional column names. id and timestamp are automatic.
    $columns = ["example"];

    // Establish a connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Construct the query
    foreach ($columns as $value) {
        $column_vals[] = $_POST[$value];
        echo "Name: ".$value." Value: ".$_POST[$value]."<br>";
    }
    $sql = "INSERT INTO SimpleWebStats (time, " . implode(", ", $columns);
    $sql .= ") VALUES (NOW(), ";
    $sql .= str_repeat("?, ", count($columns)-1) . "?)";

    // Prepare statement and bind paramet$conn->prepareers
    echo $sql."<br>";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat("s", count($columns)), ...$column_vals);
    $stmt->execute();
    if ($stmt->error) {
        echo "Error: ".$stmt->error;
    }

    $stmt->close();
    $conn->close();
} else { ?>
    This is a simple, non-intrusive, GDPR-compliant website analytics script.
    It collects only the time of visit and simple metadata that do not identify
    you or expose any personal details (for example, only the domain of the
    referrer will be saved, so google.com instead of the particular search query).
    <br><br>
    This is the bare minimum needed to see how well the website is doing, without
    infringing on your privacy!
    <br><br>
    To find out more and read the code go to <a href="https://github.com/jdranczewski/SimpleWebStats">
    https://github.com/jdranczewski/SimpleWebStats</a>.
<?php }
// echo isset($_POST["referrer"]) ? $_POST["referrer"] : "No";
?>
