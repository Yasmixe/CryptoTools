const startTime = Date.now();
const Liste = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
const mdp = prompt("Entrer le mot de passe de 5 caracteres de 0 à 9 : ");
let chaine = "";

function fonction1(chaine, mdp) {
  if (chaine === mdp) {
    console.log("le mot de passe est : ", chaine);
  }
}

function forcebrut() {
  for (const i of Liste) {
    chaine = i;
    fonction1(chaine, mdp);
    for (const i2 of Liste) {
      chaine = i + i2;
      fonction1(chaine, mdp);
      for (const i3 of Liste) {
        chaine = i + i2 + i3;
        fonction1(chaine, mdp);
        for (const i4 of Liste) {
          chaine = i + i2 + i3 + i4;
          fonction1(chaine, mdp);
          for (const i5 of Liste) {
            chaine = i + i2 + i3 + i4 + i5;
            fonction1(chaine, mdp);
          }
        }
      }
    }
  }
}

forcebrut();
console.log("Le temps d'éxecution est de : " + (Date.now() - startTime) / 1000 + " secondes");
