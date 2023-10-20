def miroir(text):
    return text[::-1]

def chiffre_cesar(text, cle):
    resultat = ""
    for char in text:
        if char.isalpha():
            decalage = cle % 26
            if char.islower():
                nouvel_char = chr(((ord(char) - ord('a') - decalage) % 26) + ord('a'))
            else:
                nouvel_char = chr(((ord(char) - ord('A') - decalage) % 26) + ord('A'))
            resultat += nouvel_char
        else:
            resultat += char
    return resultat

def chiffre_miroir_et_cesar(phrase):
    mots = phrase.split()
    resultat = []
    for mot in mots:
        if mot == miroir(mot):
            resultat.append(chiffre_cesar(mot,3))  # Chiffrement de César pour les palindromes
        else:
            resultat.append(miroir(mot))  # Chiffrement en miroir pour les non-palindromes
    return " ".join(resultat)

def dechiffre_miroir_et_cesar(phrase, cle_cesar):
    mots = phrase.split()
    resultat = []
    for mot in mots:
        if mot == chiffre_cesar(mot, 3):
            resultat.append(miroir(mot))  # Déchiffrement en miroir pour les palindromes
        else:
            resultat.append(chiffre_cesar(mot, -cle_cesar))  # Déchiffrement de César pour les non-palindromes
    return " ".join(resultat)

message = input("Entrez le message à chiffrer : ")
cle_cesar = int(input("Entrez la clé de chiffrement de César : "))

message_chiffre = chiffre_miroir_et_cesar(message)
print("Message chiffré:", message_chiffre)

message_dechiffre = dechiffre_miroir_et_cesar(message_chiffre, cle_cesar)
print("Message déchiffré:", message_dechiffre)