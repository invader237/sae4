import { register } from './core/api/api.js';

console.log("register.js");

const formRegister = document.querySelector("#registerForm");

formRegister.addEventListener("submit", (event) => {
    event.preventDefault();
    registerUser();
});

async function registerUser() {
    const nom = document.getElementById("nom").value;
    const prenom = document.getElementById("prenom").value;
    const date_naissance = document.getElementById("date_naissance").value;
    const email = document.getElementById("mel").value;
    const mdp = document.getElementById("mdp").value;
    const mdp2 = document.getElementById("mdp2").value;
    const id_civilite = document.getElementById("civilite").value

    const nomErreur = document.getElementById("nomErreur").innerText = "";
    const prenomErreur = document.getElementById("prenomErreur").innerText = "";
    const civiliteErreur = document.getElementById("civiliteErreur").innerText = "";
    const melErreur = document.getElementById("melErreur").innerText = "";
    const mdpErreur = document.getElementById("mdpErreur").innerText = "";
    const mdp2Erreur = document.getElementById("mdp2Erreur").innerText = "";

    const errorStatus = false;

    if (nom.length < 3 || nom.length > 40) {
        document.getElementById("nomErreur").innerText = "Le nom doit contenir entre 3 et 40 caractères";
        errorStatus = true;
    } 
    if (prenom.length < 3 || prenom.length > 40) {
        document.getElementById("prenomErreur").innerText = "Le prénom doit contenir entre 3 et 40 caractères";
        errorStatus = true;
    }
    if (civilite === "") {
        document.getElementById("civiliteErreur").innerText = "Veuillez choisir une civilité";
        errorStatus = true;
    }
    if (email === "" || !email.includes("@") || !email.includes(".") || email.length < 5 || email.length > 40) {
        document.getElementById("melErreur").innerText = "Veuillez entrer une adresse email valide";
        errorStatus = true;
    }
    if (mdp === "" || mdp.length < 8 || mdp.length > 40) {
        document.getElementById("mdpErreur").innerText = "Le mot de passe doit contenir entre 8 et 40 caractères";
        errorStatus = true;
    }
    if (!mdp.match(/[A-Z]/)) {
        document.getElementById("mdpErreur").innerText = "Le mot de passe doit contenir au moins une majuscule";
        errorStatus = true;
    }
    if (!mdp.match(/[0-9][0-9]/)) {
        document.getElementById("mdpErreur").innerText = "Le mot de passe doit contenir au moins un chiffre";
        errorStatus = true;
    }
    if (!mdp.match(/[$&+,:;=?@#|'<>.^*()%!-]/)) {
        document.getElementById("mdpErreur").innerText = "Le mot de passe doit contenir au moins un caractère spécial";
        errorStatus = true;
    }
    if (mdp2 === "" || mdp2 !== mdp) {
        document.getElementById("mdp2Erreur").innerText = "Les mots de passe ne correspondent pas";
        errorStatus = true;
    }

    const hashPassword = sha256(mdp);

    if (!errorStatus) {
        await register(nom, prenom, date_naissance, email, hashPassword, id_civilite).then((response) => {
            if (response) {
                alert("Vous êtes inscrit");
                window.location.href = "../pages/login.html";
            }
            else {
                document.getElementById("MessageErreur").innerText = response.data.message;
            }
        }).catch((error) => {
            console.log("error");
        });
    }
}
