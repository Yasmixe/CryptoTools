<?php
function est_palindrome($word)
{
    return $word === strrev($word);
}

function miroir($word)
{
    return strrev($word);
}

function cesar($text, $key, $action)
{
    $resultat = "";
    for ($i = 0; $i < strlen($text); $i++) {
        $char = $text[$i];
        if (ctype_alpha($char)) {
            $décalage = $key;
            if ($action === "déchiffrement") {
                $décalage = -$décalage;
            }
            $ascii_offset = ctype_lower($char) ? ord('a') : ord('A');
            $new_char = chr((ord($char) - $ascii_offset + $décalage + 26) % 26 + $ascii_offset);
            $resultat .= $new_char;
        } else {
            $resultat .= $char;
        }
    }
    return $resultat;
}

function cryptage($text, $key, $action)
{
    $resultat = "";
    for ($i = 0; $i < strlen($text); $i++) {
        $char = $text[$i];
        if (ctype_alpha($char)) {
            $décalage = $key;
            if ($action === "déchiffrement") {
                $décalage = -$décalage;
            }
            $ascii_offset = ctype_lower($char) ? ord('a') : ord('A');
            $new_char = chr((ord($char) - $ascii_offset + $décalage + 26) % 26 + $ascii_offset);
            $resultat .= $new_char;
        } else {
            $resultat .= $char;
        }
    }
    return $resultat;
}

function cryptage_miroir($mot, $action)
{
    if ($action === "chiffrement") {
        return strrev($mot);
    } elseif ($action === "déchiffrement") {
        return strrev($mot);
    }
}

function cryptage_mot($mot, $action)
{
    if (est_palindrome($mot)) {
        return cesar($mot, strlen($mot), $action);
    } else {
        return cryptage_miroir($mot, $action);
    }
}

function chiffrement($phrase)
{
    $mots = explode(" ", $phrase);
    $mot_crypté = array_map(function ($mot) {
        return cryptage_mot($mot, "chiffrement");
    }, $mots);
    $phrase_cryptée = implode(" ", $mot_crypté);
    $words = explode(" ", $phrase_cryptée);
    $reversed_phrase = implode(" ", array_reverse($words));
    return $reversed_phrase;
}

function dechiffrement($phrase_chiffree)
{
    $phrase_inv = $phrase_chiffree;
    $words = explode(" ", $phrase_inv);
    $original_phrase = implode(" ", array_reverse($words));
    $mots = explode(" ", $original_phrase);
    $mot_décrypté = array_map(function ($mot) {
        return cryptage_mot($mot, "déchiffrement");
    }, $mots);
    return implode(" ", $mot_décrypté);
}

?>