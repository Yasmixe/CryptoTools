import string
import time

start_time = time.time()
dictionnaire = [
    "00000",
    "11111",
    "12345",
    "77033",
    "71201",
    "66666",
    "98765",
    "01010",
    "55555",
    "12121",
]
mdp = input("Entrer le mot de passe de 5 caracteres de 0 à 9 : ")
while len(mdp) != 5:
    mdp = input("Le mot de passe doit avoir exactement 5 caractères. Réessayez : ")
    while not all(caractere in dictionnaire for caractere in mdp):
        mdp = input(
            "Le mot de passe doit être composé uniquement de '0' a '9'. Réessayez : "
        )

# Mot de passe à deviner
mdp = input("donner le mot de passe : ")
with open("fich.txt", "r") as fichier:
    contenu = fichier.read()
mots = contenu.split()


# Fonction pour effectuer l'attaque par dictionnaire
def attaque_par_dictionnaire(mots, mdp):
    for mot in mots:
        if mot == mdp:
            return mot
    return None


# Appel de la fonction d'attaque par dictionnaire
resultat = attaque_par_dictionnaire(mots, mdp)

# Vérification du résultat
if resultat is not None:
    print(f"Le mot de passe est : {resultat}")
else:
    print("Le mot de passe n'a pas été trouvé dans le dictionnaire.")

print("Le temps d'éxecution est de : %s secondes" % (time.time() - start_time))
