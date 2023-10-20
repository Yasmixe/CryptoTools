def pgcd(a, b):
    while b:
        a, b = b, a % b
    return a

def mod_inverse(a, n):
    for x in range(1, n):
        if (a * x) % n == 1:
            return x
    return None

def chiffrement_affine(message, a, b):
    message_chiffre = ""
    n = 26  # Taille de l'alphabet

    if pgcd(a, n) != 1:
        raise ValueError("La clé 'a' doit être choisie de telle sorte que pgcd(a, 26) = 1")

    for i in message:
        if i.isalpha():  # Vérifiez si la lettre est alphabétique
            decalage = 65 if i.isupper() else 97
            lettre_chiffree = chr(((a * (ord(i) - decalage) + b) % n) + decalage)
            message_chiffre += lettre_chiffree
        else:
            message_chiffre += i  # On ne déchiffre pas les symboles et caractères spéciaux
    return message_chiffre

def dechiffrement_affine(message_chiffre, a, b):
    message_dechiffre = ""
    n = 26  # Taille de l'alphabet

    if pgcd(a, n) != 1:
        raise ValueError("La clé 'a' doit être choisie de telle sorte que pgcd(a, 26) = 1")

    a_inverse = mod_inverse(a, n)
    if a_inverse is None:
        raise ValueError("La clé 'a' n'a pas d'inverse modulo 26")

    for i in message_chiffre:
        if i.isalpha():  # Vérifiez si la lettre est alphabétique
            decalage = 65 if i.isupper() else 97
            lettre_dechiffree = chr(((a_inverse * (ord(i) - decalage - b)) % n) + decalage)
            message_dechiffre += lettre_dechiffree
        else:
            message_dechiffre += i  # On ne déchiffre pas les symboles et caractères spéciaux
    return message_dechiffre

def main(): #Donner la main de choisir la méthode de cryptage
    while True:
        print("Choisissez une option :")
        print("1. Chiffrement")
        print("2. Déchiffrement")
        print("3. Quitter")
        choix = input("Entrez votre choix : ")

        if choix == "1":
            message_initial = input("Entrez le message à chiffrer : ")
            #Introduire les paramètres
            a = int(input("Entrez la valeur de 'a' : "))
            b = int(input("Entrez la valeur de 'b' : "))
            message_chiffre = chiffrement_affine(message_initial, a, b)
            print("Le Message chiffré est:", message_chiffre)
        elif choix == "2":
            message_chiffre = input("Entrez le message à déchiffrer : ")
            a = int(input("Entrez la valeur de 'a' : "))
            b = int(input("Entrez la valeur de 'b' : "))
            message_dechiffre = dechiffrement_affine(message_chiffre, a, b)
            print("Le Message déchiffré est:", message_dechiffre)
        elif choix == "3":
            break
        else:
            print("Choix non valide. Veuillez opter pour une option valide.")

if __name__ == "__main__":
    main()
