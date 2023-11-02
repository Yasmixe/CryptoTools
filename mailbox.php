<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}


$loggedInUser = $_SESSION['username'];

$servername = "localhost";
$dbname = "Cryptotool";
$username = "root";
$password = "";

function miroir($text) {
  return strrev($text);
}
  

function Cesar($text, $cle_cesar) {
  $resultat = "";
  $longueur = strlen($text);
  for ($i = 0; $i < $longueur; $i++) {
      $caractere = $text[$i];
      if (ctype_alpha($caractere)) {
          $decalage = $cle_cesar;
          $minuscule = (ctype_lower($caractere));

          $ascii_de_base = ($minuscule) ? ord('a') : ord('A');
          $nouveau_caractere = chr((ord($caractere) - $ascii_de_base + $decalage) % 26 + $ascii_de_base);

          $resultat .= $nouveau_caractere;
      } else {
          $resultat .= $caractere;
      }
  }
  return $resultat;
}
function Dechiffrement($phrase, $cle_cesar) {
  $mots = explode("_", $phrase);
  $resultat = [];
  foreach ($mots as $mot) {
      if (strpos($mot, "C") === 0) {
          $mot = substr($mot, 1);  // Supprimer le préfixe "C"
          $resultat[] = Cesar($mot, -$cle_cesar);
      } else {
        
          $resultat[] = miroir($mot);
      }
  }
  return implode(" ", $resultat);
}


function DechiffrementUser($phrase) {
    $mots = explode(" ", $phrase);
    $resultat = [];
    foreach ($mots as $mot) { // Supprimer le préfixe "C"
            $resultat[] = Cesar($mot, -3);
        } 
    
    return implode(" ", $resultat);
  }
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>main page</title>
    <link rel="stylesheet" href="style3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
  </head>
  <body>
    
      <header class="top">
        <div class="navbar">
            <div class="logo"><a href="#"></a>CryptoTool</div>
            <ul class="links">
              <li><a href= "index.php">Send messages </a></li>
              <li><a href= "mailbox.php">mailbox </a></li>
              <li><a href="login.php" class="logout_btn">log out </a></li>
            </ul>
            
        </div>
        
      </header>
    <div class="container">
        <div class="table_container">
            <h2>your messages</h2>
            <table class="center">
              <tr>
                <th>Sender</th>
                <th>Crypted Message</th>
                <th>Action</th>
                <th>Encrypted Message</th>
              </tr>
              <?php
                 $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                 $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             
                 // Utilisez $loggedInUser comme récepteur pour récupérer les messages
                 $sql = "SELECT * FROM messages WHERE receiver = :receiver";
                 $stmt = $conn->prepare($sql);
                 $stmt->bindParam(':receiver', $loggedInUser); // Assurez-vous de définir $loggedInUser
                 $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
                   $message = $row['message'];
                   $key = $row['key'];
                   ?>
                    <tr>
                        <td><?php echo DechiffrementUser($row['sender']); ?></td>
                        <td><?php echo $message; ?></td>
                        <td><button onclick="openForm()">Déchiffrer</button></td>
                        <td class="decrypted-message" data-row-id="<?php echo $row['id']; ?>"></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
            <div class="form-popup" id="myForm">
  <form action="mailbox.php" class="form-container">
    <div class="center">
      <label for="types">Choose encryption type:</label>
      <select id="types" name="types">
          <option value="cesar">Cesar</option>
          <option value="miroir">Miroir</option>
          <option value="affine">Affine</option>
      </select>
    </div>
  <div id="cesar-key" style="display: none;">
      <label for="cle">Enter the Cesar key:</label>
      <input type="number" id="cle" name="cle" min="1" max="10">
  </div>
  <div id="affine" style="display: none;">
      <label for="a">Enter parameter 'a'</label>
      <input type="text" id="a" name="a">
      <label for="b">Enter parameter 'b'</label>
      <input type="text" id="b" name="b">
  </div>
  <div class="bou">
    <button type="submit" class="btn">Dechiffrer</button>
    <button type="button" class="btn cancel" onclick="closeForm()">Close</button>

  </div>
    
  </form>

</div>

        </div>
    </div>
    <script>
    function openForm() {
        document.getElementById("myForm").style.display = "block";
    }

    function closeForm() {
        document.getElementById("myForm").style.display = "none";
    }

    document.getElementById("types").addEventListener("change", function () {
        var selectedOption = this.value;
        var cesarKey = document.getElementById("cesar-key");
        var affineParams = document.getElementById("affine");

        if (selectedOption === "cesar") {
            cesarKey.style.display = "block";
            affineParams.style.display = "none";
        } else if (selectedOption === "affine") {
            cesarKey.style.display = "none";
            affineParams.style.display = "flex";
            affineParams.style.flexDirection = "column";
        } else {
            cesarKey.style.display = "none";
            affineParams.style.display = "none";
        }
    });

</script>


  </body>