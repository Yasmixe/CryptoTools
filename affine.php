<?php
function pgcd($a, $b)
{
    while ($b) {
        $temp = $b;
        $b = $a % $b;
        $a = $temp;
    }
    return $a;
}

function mod_inverse($a, $n)
{
    for ($x = 1; $x < $n; $x++) {
        if (($a * $x) % $n == 1) {
            return $x;
        }
    }
    return null;
}
function chiffrement_affine($message, $a, $b)
{
    $message_chiffre = "";
    $n = 26; // Taille de l'alphabet

    if (pgcd($a, $n) != 1) {
        throw new Exception("La clé 'a' doit être choisie de telle sorte que pgcd(a, 26) = 1");
    }

    for ($i = 0; $i < strlen($message); $i++) {
        $char = $message[$i];
        if (ctype_alpha($char)) { // Vérifiez si la lettre est alphabétique
            $decalage = (ctype_upper($char)) ? 65 : 97;
            $lettre_chiffree = chr((($a * (ord($char) - $decalage) + $b) % $n) + $decalage);
            $message_chiffre .= $lettre_chiffree;
        } else {
            $message_chiffre .= $char; // On ne chiffre pas les symboles et caractères spéciaux
        }
    }
    return $message_chiffre;
}

function dechiffrement_affine($message_chiffre, $a, $b)
{
    $message_dechiffre = "";
    $n = 26; // Taille de l'alphabet

    if (pgcd($a, $n) != 1) {
        throw new Exception("La clé 'a' doit être choisie de telle sorte que pgcd(a, 26) = 1");
    }

    $a_inverse = mod_inverse($a, $n);
    if ($a_inverse === null) {
        throw new Exception("La clé 'a' n'a pas d'inverse modulo 26");
    }

    for ($i = 0; $i < strlen($message_chiffre); $i++) {
        $char = $message_chiffre[$i];
        if (ctype_alpha($char)) { // Vérifiez si la lettre est alphabétique
            $decalage = (ctype_upper($char)) ? 65 : 97;
            $lettre_dechiffree = chr((($a_inverse * (ord($char) - $decalage - $b + $n)) % $n) + $decalage);
            $message_dechiffre .= $lettre_dechiffree;
        } else {
            $message_dechiffre .= $char; // On ne déchiffre pas les symboles et caractères spéciaux
        }
    }
    return $message_dechiffre;
}
?>