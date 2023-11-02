<?php

$start_time = time();
$Liste = ["0", "1"];

$mdp = readline("Entrer le mot de passe de 3 caracteres : ");
while (strlen($mdp) != 3) {
    $mdp = readline("Le mot de passe doit avoir exactement 3 caractères. Réessayez : ");
    while (str_replace($Liste, '', $mdp) !== '') {
        $mdp = readline("Le mot de passe doit être composé uniquement de '0' ou '1'. Réessayez : ");
    }
}

function fonction1($chaine, $mdp)
{
    if ($chaine == $mdp) {
        echo "le mot de passe est : $chaine\n";
    }
}

function forcebrut($mdp, $Liste)
{
    foreach ($Liste as $i) {
        $chaine = $i;
        fonction1($chaine, $mdp);
        foreach ($Liste as $i2) {
            $chaine = $i . $i2;
            fonction1($chaine, $mdp);
            foreach ($Liste as $i3) {
                $chaine = $i . $i2 . $i3;
                fonction1($chaine, $mdp);
            }
        }
    }
}

forcebrut($mdp, $Liste);
echo "Le temps d'éxecution est de : " . (time() - $start_time) . " secondes\n";

?>