<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: autho.php');
    exit;
}

$loggedInUser = $_SESSION['username'];

$servername = "localhost";
$dbname = "Cryptotool";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Utilisez $loggedInUser comme récepteur pour récupérer les messages
    $sql = "SELECT * FROM messages WHERE receiver = :receiver";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':receiver', $loggedInUser);
    $stmt->execute();

    // Affichage des messages reçus
    echo "<!DOCTYPE html>
    <html lang=\"en\" dir=\"ltr\">
      <head>
        <meta charset=\"utf-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <title>Mailbox</title>
        <link rel=\"stylesheet\" href=\"style3.css\">
        <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css\">
      </head>
      <body>
        <header>
          <div class=\"navbar\">
            <div class=\"logo\"><a href=\"#\"></a>crypto</div>
            <ul class=\"links\">
              <li><a href=\"/\">home</a></li>
              <li><a href=\"index.php\">Send messages</a></li>
              <li><a href=\"mailbox.php\">mailbox</a></li>
            </ul>
            <a href=\"login.html\" class=\"logout_btn\">log out</a>
          </div>
        </header>
        <div class=\"container\">
          <div class=\"table_container\">
            <h2>Your Messages</h2>
            <table class=\"center\">
              <tr>
                <th>Sender</th>
                <th>Encrypted Message</th>
                <th>Action</th>
              </tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['sender'] . "</td>";
        echo "<td>" . $row['message'] . "</td>";
        echo "<td><button>Decrypt</button></td>";
        echo "</tr>";
    }

    echo "</table>
        </div>
    </div>
    </body>
    </html>";

    $conn = null;
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
