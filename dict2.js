


const dictionnaire =["00000", "11111", "12345", "77033", "71201", "66666", "98765", "01010", "55555", "12121"];



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
