<?php
session_start();

include 'connect.php';
// Fonction de chiffrement César 
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


    
    // Vérifier si le formulaire d'authentification a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // Récupérer les valeurs du formulaire
        $enteredUsername = isset($_POST["uname"]) ? $_POST["uname"] : "";
        $enteredpsw = isset($_POST["psw"]) ? $_POST["psw"] : "";

        $encryptedUsername = encryptCesar($enteredUsername, 3);
        $encryptedPassword = encryptCesar($enteredpsw, 3);
    
        // Vérifier l'authentification uniquement sur le nom d'utilisateur
        $stmt = $conn->prepare("SELECT * FROM Utilisateurs WHERE uname = :username ");
        $stmt->bindParam(':username', $encryptedUsername);
       
        $stmt->execute();
        
        // Récupérer l'utilisateur de la base de données
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'utilisateur existe et si le mot de passe est correct
        //inclure le message chiffrer
        if ($user && $encryptedPassword == $user['psw']) {
            echo "Authentification réussie.";
            $_SESSION['username'] = $encryptedUsername;  // Utilisez 'username' au lieu de 'user'
            header('Location: index.php');
        } else {
            echo "Échec de l'authentification. Nom d'utilisateur ou mot de passe incorrect.";
        }
    
    }
     else {
        // Ajout manuel d'un nouvel utilisateur avec mot de passe chiffré
        
            // Ajout manuel d'un nouvel utilisateur avec mot de passe chiffré
            $manualUsername = "amina";
            $manualPassword = "benkherif";
            $shift = 3; // ou tout autre décalage que vous utilisez
            $encryptedManualPassword = encryptCesar($manualPassword, $shift);
            $encryptedManualUsername = encryptCesar($manualUsername, $shift);

            // Ajout manuel d'un nouvel utilisateur avec mot de passe chiffré
            $manualUsername = "Yanis";
            $manualPassword = "yanisso";
            $shift = 3; // ou tout autre décalage que vous utilisez
            $encryptedManualPassword = encryptCesar($manualPassword, $shift);
            $encryptedManualUsername = encryptCesar($manualUsername, $shift);
        
            // Vérifier si l'utilisateur existe déjà
            $stmtCheck = $conn->prepare("SELECT * FROM Utilisateurs WHERE uname = :username");
            $stmtCheck->bindParam(':username', $encryptedManualUsername);
            $stmtCheck->execute();
            $user = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        
            if ($user) {
                echo "L'utilisateur avec le nom d'utilisateur '$manualUsername' existe déjà.";
            } else {
                // L'utilisateur n'existe pas, effectuer l'insertion
                $stmt = $conn->prepare("INSERT INTO Utilisateurs (uname, psw) VALUES (:username, :password)");
                $stmt->bindParam(':username', $encryptedManualUsername);
                $stmt->bindParam(':password', $encryptedManualPassword);
                $stmt->execute();
        
                echo "Nouvel utilisateur ajouté avec succès.";
            }
       
        }

?>