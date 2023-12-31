<?php
session_start();
$_SESSION['authSuccess'] = false;


include 'connect.php';

// Fonction de chiffrement César (à adapter selon votre implémentation)
function encryptCesar($text, $shift)
{
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
    $enteredUsername = isset($_POST["uname"]) ? $_POST["uname"] : "";
    $enteredpsw = isset($_POST["psw"]) ? $_POST["psw"] : "";

    if (empty($enteredUsername) || empty($enteredpsw)) {
        echo "Veuillez remplir tous les champs.";
    } else {
        $encryptedUsername = encryptCesar($enteredUsername, 3);
        $encryptedPassword = encryptCesar($enteredpsw, 3);

        $stmt = $conn->prepare("SELECT * FROM Utilisateurs WHERE uname = :username ");
        $stmt->bindParam(':username', $encryptedUsername);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Vérifier si l'utilisateur est bloqué
            if ($user['loginAttempts'] >= 3) {
                $failedLoginTime = strtotime($user['lastFailedLoginTime']);
                $currentTime = time();
                $timeDifference = $currentTime - $failedLoginTime;

                if ($timeDifference < 1800) { //30min
                    echo "blocked";
                    exit;
                } else {
                    $currentTime = date("Y-m-d H:i:s");
                    $stmtResetAttempts = $conn->prepare("UPDATE Utilisateurs SET loginAttempts = 0, lastFailedLoginTime = :currentTime WHERE uname = :username");
                    $stmtResetAttempts->bindParam(':username', $encryptedUsername);
                    $stmtResetAttempts->bindParam(':currentTime', $currentTime);
                    $stmtResetAttempts->execute();
                }
            }

            // Vérifier si l'utilisateur existe et si le mot de passe est correct
            if ($encryptedPassword == $user['psw']) {
                $_SESSION['username'] = $encryptedUsername;
                $_SESSION['authSuccess'] = true;
                // Réinitialiser le compteur d'essais
                $stmtResetAttempts = $conn->prepare("UPDATE Utilisateurs SET loginAttempts = 0 WHERE uname = :username");
                $stmtResetAttempts->bindParam(':username', $encryptedUsername);
                $stmtResetAttempts->execute();
                echo "Authentification réussie.";
                exit;
            } else {
                $loginAttempts = $user['loginAttempts'] + 1;
                echo "Échec de l'authentification. Nom d'utilisateur ou mot de passe incorrect.";
                $stmtUpdateAttempts = $conn->prepare("UPDATE Utilisateurs SET loginAttempts = :attempts WHERE uname = :username");
                $stmtUpdateAttempts->bindParam(':username', $encryptedUsername);
                $stmtUpdateAttempts->bindParam(':attempts', $loginAttempts);
                $stmtUpdateAttempts->execute();
                $_SESSION['authSuccess'] = false;
                exit;
            }
        }
    }
} else {
    // Ajout manuel d'un nouvel utilisateur avec mot de passe chiffré
    $manualUsername = "mimi";
    $manualPassword = "77033";
    $shift = 3;
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
        $stmt = $conn->prepare("INSERT INTO Utilisateurs (uname, psw, loginattempts, lastFailedLoginTime) VALUES (:username, :password, 0, NOW())");
        $stmt->bindParam(':username', $encryptedManualUsername);
        $stmt->bindParam(':password', $encryptedManualPassword);
        $stmt->execute();
        echo "Nouvel utilisateur ajouté avec succès.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style4.css">
    <title>Document</title>
</head>

<body>
    <header class="top">
        <div class="navbar">
            <div class="logo"><a href="#"></a>CryptoTool</div>
            <ul class="links">
                <li><a href="index.php">Send messages</a></li>
                <li><a href="mailbox.php">mailbox</a></li>
                <li><a href="login.php" class="logout_btn">log out</a></li>
            </ul>
        </div>
    </header>
    <div>
        <div class="droite">
            <div class="login-left">
                <div class="login2">
                    <h2>CryptoTools</h2>
                    <h3> Log in to your Account</h3>
                    <p>Hi, Welcome again.</p>
                    <p class="middle">Log in with your Username</p>
                    <div class="placeholderr">
                        <div class="center">
                            <input type="text" placeholder="Username" name="uname" id="usernameInput" required />
                            <input type="password" placeholder="Password" name="psw" id="passwordInput" required />
                            <button id="loginButton">Login</button>
                            <button id="attaque">attack</button>
                            <a href="forcebrute.php" style="color:black">attack</a>
                        </div>
                        <div id="captchaModal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <span class="close" id="closeButton">&times;</span>
                                <canvas id="captcha">captcha text</canvas>
                                <input id="textBox" type="text" name="text" />
                                <div id="buttons">
                                    <button id="refreshButton" type="button">Refresh</button>
                                    <button id="submission" type="button">Submission</button>
                                </div>
                                <span id="output"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="login right">
                    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs"
                        type="module"></script>
                    <dotlottie-player src="https://lottie.host/942aa4aa-343a-49bd-b387-5534d624fb90/jt6hI4bKsy.json"
                        background="transparent" speed="1" style="width: 400px; height: 400px; " loop
                        autoplay></dotlottie-player>
                </div>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.12.2/lottie.min.js"
            integrity="sha512-jEnuDt6jfecCjthQAJ+ed0MTVA++5ZKmlUcmDGBv2vUI/REn6FuIdixLNnQT+vKusE2hhTk2is3cFvv5wA+Sgg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            var authSuccess = <?php echo $_SESSION['authSuccess'] ? 'true' : 'false'; ?>;
            var captchaModal = document.getElementById("captchaModal");
            var captchaText = document.getElementById('captcha');
            var ctx = captchaText.getContext("2d");
            ctx.font = "15px Roboto";
            ctx.fillStyle = "#000000";
            var captchaStr = "";
            var userText = document.getElementById('textBox');
            var refreshButton = document.getElementById('refreshButton');
            var closeButton = document.querySelector(".close");
            let alphaNums = ['A', 'B', 'C', 'D', 'E', 'F', 'G',
                'H', 'I', 'J', 'K', 'L', 'M', 'N',
                'O', 'P', 'Q', 'R', 'S', 'T', 'U',
                'V', 'W', 'X', 'Y', 'Z', 'a', 'b',
                'c', 'd', 'e', 'f', 'g', 'h', 'i',
                'j', 'k', 'l', 'm', 'n', 'o', 'p',
                'q', 'r', 's', 't', 'u', 'v', 'w',
                'x', 'y', 'z', '0', '1', '2', '3',
                '4', '5', '6', '7', '8', '9'];

            // Fonction pour générer le captcha
            function generate_captcha() {
                let emptyArr = [];
                for (let i = 1; i <= 7; i++) {
                    emptyArr.push(alphaNums[Math.floor(Math.random() * alphaNums.length)]);
                }
                captchaStr = emptyArr.join('');
                ctx.clearRect(0, 0, captchaText.width, captchaText.height);
                ctx.fillText(captchaStr, captchaText.width / 4, captchaText.height / 2);
                userText.value = "";
            }
            refreshButton.addEventListener('click', generate_captcha);
            closeButton.addEventListener('click', function () {
                captchaModal.style.display = "none";
            });

            // Définir la fonction authenticateUser
            function authenticateUser(username, password) {
                var authenticationResult = false;
                var authMessage = "";

                console.log("Tentative d'authentification avec les identifiants :");
                console.log("Username : " + username);
                console.log("Password : " + password);

                var formData = new FormData();
                formData.append("uname", username);
                formData.append("psw", password);

                console.log("Envoi des données au serveur : " + formData);

                return fetch("login.php", {
                    method: "POST",
                    body: formData,
                })
                    .then(response => response.text())
                    .then(data => {
                        console.log("Contenu de data : " + data);
                        // Stocker la réponse d'authentification
                        authMessage = data;

                        // Nettoyer la réponse en supprimant les espaces en début et en fin de chaîne
                        var trimmedData = data.trim();
                        console.log("Contenu trimmeddata : " + trimmedData);

                        if (trimmedData === "Authentification réussie.") {
                            alert("Authentification réussie.");
                            authenticationResult = true;
                        } else if (trimmedData === "blocked") {
                            alert("Vous avez été bloqué. Veuillez contacter l'administrateur pour débloquer votre compte.");
                            authenticationResult = false;
                        } else {
                            alert("Authentification échouée. Nom d'utilisateur ou mot de passe incorrect.");
                            authenticationResult = false;
                        }
                        return authenticationResult;
                    })
                    .catch(error => {
                        console.error("Une erreur s'est produite lors de l'authentification : ", error);
                        authenticationResult = false;
                        return authenticationResult;
                    });
            }

            // Gestionnaire pour le bouton "Login"
            document.getElementById('loginButton').addEventListener('click', function () {
                var username = document.getElementById('usernameInput').value;
                var password = document.getElementById('passwordInput').value;

                // Ajoutez ces lignes pour vérifier les valeurs récupérées
                console.log("Valeur de username : " + username);
                console.log("Valeur de password : " + password);

                authenticateUser(username, password).then(function (authenticationSuccess) {
                    if (authenticationSuccess) {
                        console.log("Authentification est réussie.");
                        const dictionnaire = ["00000", "11111", "12345", "77033", "71201", "66666", "98765", "01010", "55555", "12121"];



                        function attaqueParDictionnaire(dictionnaire, password) {
                            for (const mot of dictionnaire) {
                                if (mot === password) {
                                    return mot;
                                }
                            }
                            return null;
                        }

                        const resultat = attaqueParDictionnaire(dictionnaire, password);

                        if (resultat !== null) {
                            alert(`Le mot de passe est : ${resultat}`);
                        } else {
                            alert("Le mot de passe n'a pas été trouvé dans le dictionnaire.");
                        }

                        generate_captcha();
                        captchaModal.style.display = "block";
                    } else {
                        alert("Captcha incorrect. Veuillez réessayer.");
                    }
                })

            });

            // Gestionnaire pour le bouton "Submission" du captcha
            document.getElementById('submission').addEventListener('click', function () {
                var enteredCaptcha = document.getElementById('textBox').value;
                if (enteredCaptcha === captchaStr) {
                    alert("Captcha correct. Redirection vers index.php.");
                    window.location.href = "index.php";
                } else {
                    alert("Captcha incorrect. Veuillez réessayer.");
                    generate_captcha();
                }
            });

        </script>
</body>

</html>