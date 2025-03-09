import { login } from './core/api/api.js';

console.log("login.js");

const form = document.querySelector("#loginForm");

form.addEventListener("submit", (event) => {
    event.preventDefault();
    authentifier();
});


async function authentifier() {
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    console.log(email, password);
    await login(email, password).then((response) => {
        if (response.status === 200) {
            localStorage.setItem("authToken", response.data.token);
            alert("Vous êtes connecté");
            window.location.href = "/pages/profil.html";
        }
        else {
            document.getElementById("error").innerText = response.data.message
        }
    }).catch((error) => {
        console.error("error");
        console.error(error);
        document.getElementById("error").innerText = error.data.message;
    });
}

