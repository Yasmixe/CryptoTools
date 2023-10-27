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