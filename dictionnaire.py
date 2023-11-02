import string 
import time

start_time = time.time()
dictionnaire=["a", "e","b","D", "I","U","1", "4","6","@", "*","&"]
mdp = input("Entrer le mot de passe de 5 caracteres : ")
while len(mdp) != 5:
    mdp = input("Le mot de passe doit avoir exactement 5 caractères. Réessayez : ")
    while not all(caractere in dictionnaire for caractere in mdp):
        mdp = input("Le mot de passe doit être composé uniquement des caracteres de la chaine, ["a", "e","b","D", "I","U","1", "4","6","@", "*","&"]. Réessayez : ")
for i in dictionnaire:
    for j in dictionnaire:
        for k in dictionnaire:
            for x in dictionnaire:
                for y in dictionnaire:
                    f=open('Dict2.txt','a')
                    f.write(str(i)+str(j)+str(k)+str(x)+str(y)+'\n')
                    f.close()
# Mot de passe à deviner
mdp = input("donner le mot de passe composé des caracteres de la chaine suivante  ["a", "e","b","D", "I","U","1", "4","6","@", "*","&"]: ")
with open('fich.txt', 'r') as fichier:
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