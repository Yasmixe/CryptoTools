function ChiffrementCesar($message, $pw, $Action, $Direction) {
    $cle = array_sum(array_map('ord', str_split($pw)));
    $Resultat = "";

    for ($i = 0; $i < strlen($message); $i++) {
        $char = $message[$i];
        if (ctype_alpha($char)) {
            if ($Action == "chiffrer" && $Direction == "droite") {
                $Decalage = $cle;
            } elseif ($Action == "chiffrer" && $Direction == "gauche") {
                $Decalage = -$cle;
            } elseif ($Action == "déchiffrer" && $Direction == "droite") {
                $Decalage = $cle;
            } else {
                $Decalage = -$cle;
            }
            if (ctype_lower($char)) {
                $Resultat .= chr((ord($char) - ord('a') + $Decalage) % 26 + ord('a'));
            } elseif (ctype_upper($char)) {
                $Resultat .= chr((ord($char) - ord('A') + $Decalage) % 26 + ord('A'));
            }
        } else {
            $Resultat .= $char;
        }
    }

    return $Resultat;
}

$message = readline("Entrez le message : ");
$pw = readline("Entrez le mot de passe : ");
$Action = readline("Voulez-vous chiffrer ou déchiffrer : ");
$Direction = readline("Entrez une direction : ");
$message_chiffre = ChiffrementCesar($message, $pw, $Action, $Direction);
echo $message_chiffre;
