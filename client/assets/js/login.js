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
    const hashPassword = sha256(password);
    await login(email, hashPassword).then((response) => {
        if (response.status === 200) {
            localStorage.setItem("authToken", response.data.token);
            window.location.href = "/pages/profil.html";
        }
    }).catch((error) => {
        const errorMessage = document.getElementById("error");
        errorMessage.innerText = error.data.message;
        errorMessage.attributes.removeNamedItem("hidden");
    });
}

