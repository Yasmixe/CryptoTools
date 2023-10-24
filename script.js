let captchaText = document.getElementById('captcha');
var ctx = captchaText.getContext("2d");
ctx.font = "15px Roboto";
ctx.fillStyle = "#000000";

let userText = document.getElementById('textBox');
let submitButton = document.getElementById('submission');
let output = document.getElementById('output');
let refreshButton = document.getElementById('refreshButton');

var captchaStr = "";

let alphaNums = ['A', 'B', 'C', 'D', 'E', 'F', 'G',
                 'H', 'I', 'J', 'K', 'L', 'M', 'N', 
                 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 
                 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 
                 'c', 'd', 'e', 'f', 'g', 'h', 'i', 
                 'j', 'k', 'l', 'm', 'n', 'o', 'p', 
                 'q', 'r', 's', 't', 'u', 'v', 'w', 
                 'x', 'y', 'z', '0', '1', '2', '3', 
                 '4', '5', '6', '7', '8', '9'];

function generate_captcha() {
   let emptyArr = [];

   for (let i = 1; i <= 7; i++) {
       emptyArr.push(alphaNums[Math.floor(Math.random() * alphaNums.length)]);
   }

   captchaStr = emptyArr.join('');

   ctx.clearRect(0, 0, captchaText.width, captchaText.height);
   ctx.fillText(captchaStr, captchaText.width/4, captchaText.height/2);

   output.innerHTML = "";
}

generate_captcha();                
function check_captcha() {
    if (userText.value === captchaStr) {
        output.className = "correctCaptcha"; 
        output.innerHTML = "Correct!";
        // Redirige vers une nouvelle page HTML
        window.location.href = "index.html";
    } else {
        output.className = "incorrectCaptcha";
        output.innerHTML = "Incorrect, please try again!";
    }
}

userText.addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
       check_captcha();
    }
});

submitButton.addEventListener('click', check_captcha);

refreshButton.addEventListener('click', generate_captcha);


var animation = bodymovin.loadAnimation({

    container: document.getElementById('animation'),
    
    path: 'https://lottie.host/?file=9e299113-f884-4e98-bd8d-656c17f1ea7f/T9oi0UMK9x.json',
    
    renderer: 'svg',
    
    loop: true,
    
    autoplay: true,    
    });          
    
    
 
 var loginButton = document.getElementById("loginButton");

        // Sélectionnez le div du captcha par son ID
        var captchaModal = document.getElementById("captchaModal");

        // Ajoutez un gestionnaire d'événements pour le clic sur le bouton de connexion
        loginButton.addEventListener("click", function() {
            // Affichez le captcha en changeant sa propriété de style pour le rendre visible
            captchaModal.style.display = "block";
        });

        var close = document.getElementById("closeButton");
        close.addEventListener('click', function(){
            captchaModal.style.display = "none";
        });