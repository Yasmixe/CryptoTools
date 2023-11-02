const dictionnaire = ["000", "001", "010", "011", "100", "101", "110", "111"];
let mdp = prompt("Entrer le mot de passe de 3 caractères de 0 et 1 : ");

while (mdp.length !== 3) {
  mdp = prompt("Le mot de passe doit avoir exactement 3 caractères. Réessayez : ");
  while (!mdp.split('').every(caractere => ['0', '1'].includes(caractere)) {
    mdp = prompt("Le mot de passe doit être composé uniquement de '0' ou '1'. Réessayez : ");
  }
}

for (const i of dictionnaire) {
  const fs = require('fs');
  fs.appendFileSync('Dict1.txt', i + '\n', 'utf8');
}

mdp = prompt("Donner le mot de passe : ");

function attaqueParDictionnaire(dictionnaire, mdp) {
  for (const mot of dictionnaire) {
    if (mot === mdp) {
      return mot;
    }
  }
  return null;
}

const resultat = attaqueParDictionnaire(dictionnaire, mdp);

if (resultat !== null) {
  console.log(`Le mot de passe est : ${resultat}`);
} else {
  console.log("Le mot de passe n'a pas été trouvé dans le dictionnaire.");
}
