const Liste = [
    "a",
    "e",
    "b",
    "D",
    "I",
    "U",
    "1",
    "4",
    "6",
    "@",
    "#",
    "&"
  ];
  
  let mdp = prompt("Entrer le mot de passe de 5 caractères : ");
  while (mdp.length !== 5) {
    mdp = prompt("Le mot de passe doit avoir exactement 5 caractères. Réessayez : ");
    while (!mdp.split('').every(caractere => Liste.includes(caractere))) {
      mdp = prompt("Le mot de passe doit être composé uniquement des caractères de la liste. Réessayez : ");
    }
  }
  
  function fonction1(chaine, mdp) {
    if (chaine === mdp) {
      console.log("le mot de passe est : ", chaine);
    }
  }
  
  function forcebrut() {
    for (const i of Liste) {
      let chaine = i;
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
  console.log("Le temps d'exécution est de : " + (Date.now() - start_time) / 1000 + " secondes");
  