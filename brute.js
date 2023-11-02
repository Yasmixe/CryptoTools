const Liste = ["0", "1"];
const readline = require('readline');
const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
});

const start_time = Date.now();

function fonction1(chaine, mdp) {
    if (chaine === mdp) {
        console.log("Le mot de passe est : " + chaine);
    }
}

function forcebrut(mdp) {
    for (let i of Liste) {
        let chaine = i;
        fonction1(chaine, mdp);
        for (let i2 of Liste) {
            chaine = i + i2;
            fonction1(chaine, mdp);
            for (let i3 of Liste) {
                chaine = i + i2 + i3;
                fonction1(chaine, mdp);
            }
        }
    }
}

rl.question("Entrer le mot de passe de 3 caractères : ", (mdp) => {
    while (mdp.length !== 3 || !/^[01]+$/.test(mdp)) {
        rl.question("Le mot de passe doit avoir exactement 3 caractères et être composé uniquement de '0' ou '1'. Réessayez : ", (newMdp) => {
            mdp = newMdp;
        });
    }
    forcebrut(mdp);
    console.log("Le temps d'exécution est de : " + (Date.now() - start_time) + " millisecondes");
    rl.close();
});
