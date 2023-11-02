import time

start_time = time.time()
Liste = ["0", "1"]

mdp = input("Entrer le mot de passe de 3 caracteres : ")
while len(mdp) != 3:
    mdp = input("Le mot de passe doit avoir exactement 3 caractères. Réessayez : ")
    while not all(caractere in Liste for caractere in mdp):
        mdp = input(
            "Le mot de passe doit être composé uniquement de '0' ou '1'. Réessayez : "
        )
chaine = str()
for i in Liste:
    for j in Liste:
        for k in Liste:
            f = open("fich.txt", "a")
            f.write(str(i) + str(j) + str(k) + "\n")
            f.close()


def fonction1(chaine, mdp):
    if chaine == mdp:
        print("le mot de passe est : ", chaine)


def forcebrut():
    for i in Liste:
        chaine = i
        fonction1(chaine, mdp)
        for i2 in Liste:
            chaine = i + i2
            fonction1(chaine, mdp)
            for i3 in Liste:
                chaine = i + i2 + i3
                fonction1(chaine, mdp)


forcebrut()
print("Le temps d'éxecution est de : %s secondes" % (time.time() - start_time))
