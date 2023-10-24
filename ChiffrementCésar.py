def ChiffrementCésar(text, clé, direction):
    Résultat = ""
    for i in text:
        # la fonction isalpha permet de tester si le caractère est une lettre
        if i.isalpha():
            Décalage = clé if direction == "chiffrer" else -clé
            if i.islower():
                # la fonction ord() donne le code ascii du nouveau caractère et la fonction chr() donne la lettre correspondante
                Résultat += chr(((ord(i) - ord("a") + Décalage) % 26) + ord("a"))
            elif i.isupper():
                Résultat += chr(((ord(i) - ord("A") + Décalage) % 26) + ord("A"))
        else:
            # retournes le caractère non lettre inchangé
            Résultat += i
    return Résultat


test = input("Entrez la phrase à crypter : ")
clé = int(input("Entrez le code de chiffrement : "))
direction = input("Voulez-vous chiffrer ou déchiffrer : ")
match direction:
    case "chiffrer":
        Chiffrement = ChiffrementCésar(test, clé, direction)
        print(f"Résultat : {Chiffrement}")
    case "déchiffrer":
        Chiffrement = ChiffrementCésar(test, clé, direction)
        print(f"Résultat : {Chiffrement}")
