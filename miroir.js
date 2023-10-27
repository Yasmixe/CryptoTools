function estPalindrome(word) {
    return word === word.split('').reverse().join('');
}

function miroir(word) {
    return word.split('').reverse().join('');
}

function cesar(text, key, action) {
    let result = "";
    for (let i = 0; i < text.length; i++) {
        let char = text[i];
        if (char.match(/[a-zA-Z]/)) {
            let shift = key;
            if (action === "déchiffrement") {
                shift = -shift;
            }
            let asciiOffset = char === char.toLowerCase() ? 'a'.charCodeAt(0) : 'A'.charCodeAt(0);
            let newChar = String.fromCharCode(((char.charCodeAt(0) - asciiOffset + shift + 26) % 26) + asciiOffset);
            result += newChar;
        } else {
            result += char;
        }
    }
    return result;
}

function cryptage(text, key, action) {
    let result = "";
    for (let i = 0; i < text.length; i++) {
        let char = text[i];
        if (char.match(/[a-zA-Z]/)) {
            let shift = key;
            if (action === "déchiffrement") {
                shift = -shift;
            }
            let asciiOffset = char === char.toLowerCase() ? 'a'.charCodeAt(0) : 'A'.charCodeAt(0);
            let newChar = String.fromCharCode(((char.charCodeAt(0) - asciiOffset + shift + 26) % 26) + asciiOffset);
            result += newChar;
        } else {
            result += char;
        }
    }
    return result;
}

function cryptageMiroir(word, action) {
    if (action === "chiffrement") {
        return miroir(word);
    } else if (action === "déchiffrement") {
        return miroir(word);
    }
}

function cryptageMot(word, action) {
    if (estPalindrome(word)) {
        return cesar(word, word.length, action);
    } else {
        return cryptageMiroir(word, action);
    }
}

function chiffrement(phrase) {
    let words = phrase.split(" ");
    let wordEncrypted = words.map(function (word) {
        return cryptageMot(word, "chiffrement");
    });
    let phraseEncrypted = wordEncrypted.join(" ");
    let reversedWords = phraseEncrypted.split(" ").reverse();
    return reversedWords.join(" ");
}

function dechiffrement(phraseChiffree) {
    let phraseReversed = phraseChiffree;
    let words = phraseReversed.split(" ").reverse();
    let originalPhrase = words.join(" ");
    let mots = originalPhrase.split(" ");
    let wordDecrypted = mots.map(function (word) {
        return cryptageMot(word, "déchiffrement");
    });
    return wordDecrypted.join(" ");
}
