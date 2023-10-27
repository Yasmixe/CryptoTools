def est_palindrome(word):
    return word == word[::-1]


def miroir(word):
    return word[::-1]


def cesar(text, key, action):
    resultat = ""
    for i in text:
        if i.isalpha():
            décalage = key
            if action == "déchiffrement":
                décalage = -décalage  # Pour le déchiffrement, nous décalons à gauche
            nouveau_caractère = chr(
                ((ord(i) - ord("a" if i.islower() else "A") + décalage) % 26)
                + ord("a" if i.islower() else "A")
            )
            resultat += nouveau_caractère
        else:
            resultat += i
    return resultat


def cryptage(text, key, action):
    resultat = ""
    for i in text:
        if i.isalpha():
            décalage = key
            if action == "déchiffrement":
                décalage = -décalage  # Pour le déchiffrement, nous décalons à gauche
            nouveau_caractère = chr(
                ((ord(i) - ord("a" if i.islower() else "A") + décalage) % 26)
                + ord("a" if i.islower() else "A")
            )
            resultat += nouveau_caractère
        else:
            resultat += i
    return resultat


def cryptage_miroir(mot, action):
    if action == "chiffrement":
        return mot[::-1]
    elif action == "déchiffrement":
        return mot[::-1]


def cryptage_mot(mot, action):
    if est_palindrome(mot):
        return cesar(mot, len(mot), action)
    else:
        return cryptage_miroir(mot, action)


def chiffrement(phrase):
    mots = phrase.split()
    mot_crypté = [cryptage_mot(mot, "chiffrement") for mot in mots]
    phrase_cryptée = " ".join(mot_crypté)
    words = phrase_cryptée.split()
    reversed_phrase = " ".join(reversed(words))
    return reversed_phrase


def dechiffrement(phrase_chiffree):
    phrase_inv = phrase_chiffree
    words = phrase_inv.split()
    original_phrase = " ".join(reversed(words))
    mots = original_phrase.split()
    mot_décrypté = [cryptage_mot(mot, "déchiffrement") for mot in mots]
    return " ".join(mot_décrypté)


phrase = input("Entrez la phrase à chiffrer : ")
phrase_chiffree = chiffrement(phrase)
print(f"Phrase chiffrée : {phrase_chiffree}")
phrase_dechiffree = dechiffrement(phrase_chiffree)
print(f"Phrase déchiffrée : {phrase_dechiffree}")
