<?php
//fonction cesar
include 'connect.php';

/*-------------------------------------------------------------------------------------------------*/

function pgcd($a, $b) {
    while ($b != 0) {
        $temp = $a % $b;
        $a = $b;
        $b = $temp;
    }
    return $a;
}

function mod_inverse($a, $n) {
    for ($x = 1; $x < $n; $x++) {
        if (($a * $x) % $n == 1) {
            return $x;
        }
    }
    return null;
}

function chiffrement_affine($message, $a, $b) {
    $message_chiffre = "";
    $n = 26;  // Taille de l'alphabet

    if (pgcd($a, $n) != 1) {
        throw new Exception("La clé 'a' doit être choisie de telle sorte que pgcd(a, 26) = 1");
    }

    for ($i = 0; $i < strlen($message); $i++) {
        $char = $message[$i];
        if (ctype_alpha($char)) {  // Vérifiez si la lettre est alphabétique
            $decalage = (ctype_upper($char)) ? 65 : 97;
            $lettre_chiffree = chr((($a * (ord($char) - $decalage) + $b) % $n) + $decalage);
            $message_chiffre .= $lettre_chiffree;
        } else {
            $message_chiffre .= $char;  // On ne chiffre pas les symboles et caractères spéciaux
        }
    }
    return $message_chiffre;
}
/****************************************************************************************************************************** */
function encryptCesar($text, $shift) {
  $result = "";
  for ($i = 0; $i < strlen($text); $i++) {
      $char = $text[$i];
      if (ctype_alpha($char)) {
          $isLowerCase = ctype_lower($char);
          $asciiStart = $isLowerCase ? ord('a') : ord('A');
          $result .= chr(($isLowerCase ? (ord($char) - $asciiStart + $shift) % 26 : (ord($char) - $asciiStart + $shift) % 26) + $asciiStart);
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


session_start();
$sender = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown Sender';
// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupère les données du formulaire
    $contact = $_POST["contacts"];
    $encryptionType = $_POST["types"];
    $message = $_POST["message"];
    $key = $_POST["cle"];
    $a = $_POST['a'];
    $b = $_POST['b'];
  
    $keyy = $a.$b;
    // Traitez le message en fonction du type de cryptage
    switch ($encryptionType) {
        case "cesar":
            $encryptedMessage = encryptCesar($message, $key);
            break;
        case "miroir":
          $encryptedMessage = chiffre_miroir_et_cesar($message);
            break;
        case "affine":
          $encryptedMessage = chiffrement_affine($message, $a, $b);
          $sql = "INSERT INTO messages (`type_chiffrement`, `key`, `message`, `sender`, `receiver`) 
            VALUES ('$encryptionType', '$keyy', '$encryptedMessage', '$sender', '$contact')";
   

            break;
        default:
            break;
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
    
    //Insérez les données dans la table `messages`
    
    $sql = "INSERT INTO messages (`type_chiffrement`, `key`, `message`, `sender`, `receiver`) 
            VALUES ('$encryptionType', '$keyy', '$encryptedMessage', '$sender', '$contact')";
   
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
          <div class="logo"><a href="#"></a>crypto</div>
          <ul class="links">
            <li><a href= "index.html">home </a></li>
            <li><a href= "index.html">Send messages </a></li>
            <li><a href= "mailbox.html">mailbox </a></li>
            <li><a href="login.html" class="logout_btn">log out </a></li>
          </ul>
          
      </div>
    </header>
    
    <div class="contact-section">
    <form action="index.php" method="post">
        <div>
            <label for="contacts">Select a contact:</label>
            <select id="contacts" name="contacts">
                <option value="contact1">Contact 1</option>
                <option value="contact2">Contact 2</option>
                <option value="contact3">Contact 3</option>
            </select>
        </div>
        <div>
            <label for="types">Choose encryption type:</label>
            <form action="index.php" method="post">
            <select id="types" name="types">
                <option value="cesar">Cesar</option>
                <option value="miroir">Miroir</option>
                <option value="affine">Affine</option>
            </select>
        </div>
        <form action="index.php" method="post">
        <div id="cesar-key" style="display: none;">
            <label for="cle">Enter the Cesar key:</label>
            <input type="number" id="cle" name="cle" min="1" max="10">
        </div>
        <form action="index.php" method="post">
        <div id="affine-params" style="display: none;">
            <label for="a">Enter parameter 'a' (a > 0):</label>
            <input type="text" id="a" name="a">
            <label for="b">Enter parameter 'b' (b > 0):</label>
            <input type="text" id="b" name="b">
        </div>
        <div>
            <label for="message">Your Message:</label>
            <textarea name="message" rows="5" required></textarea>
        </div>
        <div>
            <input type="submit" value="Send">
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
            cesarKey.style.display = "none";
            affineParams.style.display = "none";
        }
    });
</script>


        
      </div>
      
    </div>
    <!--contact section end-->
  </body>
</html>