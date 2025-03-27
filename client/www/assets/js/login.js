import { login, getUser } from './core/api/api.js';

const response = await getUser();

if (response !== undefined) {
    window.location.href = "./profil.html";
}

const loginForm = document.querySelector("#loginForm");
const errorMessage = document.getElementById("error");

loginForm.addEventListener("submit", async (event) => {
    event.preventDefault();
    await authenticateUser();
});

async function authenticateUser() {
    try {
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;
        
        if (!email || !password) {
            displayError("Tous les champs sont obligatoires.");
            return;
        }

        const hashedPassword = sha256(password);
        const response = await login(email, hashedPassword);
        
        if (response.status === 200 && response.data.token) {
            localStorage.setItem("authToken", response.data.token);
            window.location.href = "../pages/profil.html";
        } else {
            displayError("Identifiants incorrects. Veuillez réessayer.");
        }
    } catch (error) {
        displayError(error?.data?.message || "Une erreur s'est produite. Veuillez réessayer.");
    }
}

function displayError(message) {
    errorMessage.innerText = message;
    errorMessage.removeAttribute("hidden");
}

const createBtn = document.getElementById("createAccountBtn");
createBtn?.addEventListener("click", () => {
    window.location.href = "../pages/register.html";
});
