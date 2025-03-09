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
    const id_civilite = 1;
    console.log(nom, prenom, date_naissance, email, mdp, id_civilite);
    await register(nom, prenom, date_naissance, email, mdp, id_civilite).then((response) => {
        if (response) {
            alert("Vous êtes inscrit");
            window.location.href = "/pages/login.html";
        }
        else {
            document.getElementById("MessageErreur").innerText = response.data.message;
        }
    }).catch((error) => {
        console.log("error");
    });
}
