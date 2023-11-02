<?php
//fonction cesar
include 'connect.php';
function encryptCesar($text, $shift) {
    $result = "";
    for ($i = 0; $i < strlen($text); $i++) {
        $char = $text[$i];
        if (ctype_alpha($char)) {
            $isLowerCase = ctype_lower($char);
            $asciiStart = $isLowerCase ? ord('a') : ord('A');
            $encryptedChar = chr(($isLowerCase ? (ord($char) - $asciiStart + $shift) % 26 : (ord($char) - $asciiStart + $shift) % 26) + $asciiStart);
            $result .=  $encryptedChar; // Ajoute 'C' avant le caractère chiffré
        } else {
            $result .= $char;
        }
    }
    return $result;
}

function miroir($word) {
    return strrev($word);
}

function chiffre_cesar($word, $shift) {
    $result = "";
    $word = strtolower($word); // Convert to lowercase for case-insensitive
    for ($i = 0; $i < strlen($word); $i++) {
        $char = $word[$i];
        if (ctype_alpha($char)) {
            $asciiStart = ord('a');
            $result .= chr((ord($char) - $asciiStart + $shift) % 26 + $asciiStart);
        } else {
            $result .= $char;
        }
    }
    return $result;
}

function chiffre_miroir_et_cesar($phrase) {
    $words = explode(" ", $phrase);
    $result = [];

    foreach ($words as $word) {
        if ($word == miroir($word)) {
            $result[] = chiffre_cesar($word, 3);
        } else {
            $result[] = miroir($word);
        }
    }

    return implode(" ", $result);
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
  function DechiffrementUser($phrase) {
    $mots = explode(" ", $phrase);
    $resultat = [];
    foreach ($mots as $mot) { 
            $resultat[] = Cesar($mot, -3);
        } 
    
    return implode(" ", $resultat);
  }
  

// Connexion à la base de données
    $servername = "localhost";
    $dbname = "cryptotool";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifiez la connexion à la base de données
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }
    
// Example usage:
session_start();
$sender = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown Sender';
// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupère les données du formulaire
    $contact = $_POST["contacts"];
    $encryptionType = $_POST["types"];
    $message = $_POST["message"];
    $key = isset($_POST["cle"]) ? $_POST["cle"] : 0;  // Assurez-vous que la clé est définie

    // Traitez le message en fonction du type de cryptage
    switch ($encryptionType) {
        case "cesar":
            $encryptedMessage = 'C' . encryptCesar($message, $key);
            break;
        case "miroir":
            $encryptedMessage = chiffre_miroir_et_cesar($message);
            break;
        case "affine":
            // Mettez ici le code de déchiffrement Affine
            break;
        default:
            // Gérez le cas où le type de cryptage n'est pas reconnu
            break;
    }

    // Insérez les données dans la table `messages`
    $sql = "INSERT INTO messages (`type_chiffrement`, `key`, `message`, `sender`, `receiver`) 
            VALUES ('$encryptionType', '$key', '$encryptedMessage', '$sender', '$contact')";

    if ($conn->query($sql) === TRUE) {
        echo "Le message a été enregistré avec succès dans la base de données.";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }

    // Fermez la connexion à la base de données
    $conn->close();
}

?>



<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>main page</title>
    <link rel="stylesheet" href="style2.css">
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
    
    <div class="contact-section">
    <form action="index.php" method="post">
        <div class="center">
            <h3 >Send Anonymous Messages</h3>
            <label for="contacts">Select a contact:</label>
            <select id="contacts" name="contacts">
            <?php
                    // Connectez-vous à la base de données pour récupérer la liste des utilisateurs
                    try {
                        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $stmt = $conn->prepare("SELECT uname FROM Utilisateurs");
                        $stmt->execute();
                        
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $decryptedName = DechiffrementUser($row['uname']); // Déchiffrez le nom du contact
                            echo "<option value='" . $decryptedName . "'>" . $decryptedName . "</option>";
                        }
                    } catch (PDOException $e) {
                        echo "Erreur : " . $e->getMessage();
                    }
                ?>
            </select>
        </div>
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
<div id="affine-params" style="display: none;">
    <label for="a">Enter parameter 'a'</label>
    <input type="text" id="a" name="a">
    <label for="b">Enter parameter 'b'</label>
    <input type="text" id="b" name="b">
</div>

        <div class="center">
            <label for="message">Your Message:</label>
            <textarea name="message" rows="5" required></textarea>
        </div>
        <div class="center">
            <input id="send" type="submit" value="Send">
        </div>
    </form>
</div>

<script type="text/javascript">
    document.getElementById("types").addEventListener("change", function () {
        var selectedOption = this.value;
        var cesarKey = document.getElementById("cesar-key");
        var affineParams = document.getElementById("affine-params");

        if (selectedOption === "cesar") {
            cesarKey.style.display = "block";
            affineParams.style.display = "none";
        } else if (selectedOption === "affine") {
            cesarKey.style.display = "none";
            affineParams.style.display = "block";
        } else {
            cesarKey.style.display = "block";
            affineParams.style.display = "none";
        }
    });
</script>


        
      </div>
      
    </div>
  </body>
</html>