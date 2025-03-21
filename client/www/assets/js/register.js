import { login, register } from './core/api/api.js';

console.log("Scripts loaded");

const loginForm = document.querySelector("#loginForm");
const registerForm = document.querySelector("#registerForm");
const errorMessage = document.getElementById("error");

if (loginForm) {
    loginForm.addEventListener("submit", async (event) => {
        event.preventDefault();
        await authenticateUser();
    });
}

async function authenticateUser() {
    try {
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;
        
        if (!email || !password) {
            displayError("Please enter both email and password.");
            return;
        }

        const hashedPassword = sha256(password);
        const response = await login(email, hashedPassword);
        
        if (response.status === 200 && response.data.token) {
            localStorage.setItem("authToken", response.data.token);
            window.location.href = "../pages/profil.html";
        } else {
            displayError("Invalid login credentials.");
        }
    } catch (error) {
        displayError(error?.data?.message || "An error occurred. Please try again.");
    }
}

function displayError(message) {
    errorMessage.innerText = message;
    errorMessage.removeAttribute("hidden");
}

if (registerForm) {
    registerForm.addEventListener("submit", async (event) => {
        event.preventDefault();
        await registerUser();
    });
}

async function registerUser() {
    try {
        const firstName = document.getElementById("prenom").value.trim();
        const lastName = document.getElementById("nom").value.trim();
        const birthDate = document.getElementById("date_naissance").value;
        const email = document.getElementById("mel").value.trim();
        const password = document.getElementById("mdp").value;
        const confirmPassword = document.getElementById("mdp2").value;
        const titleId = document.getElementById("civilite").value;

        const errors = {
            firstName: "Le prénom doit être entre 3 et 40 caractères.",
            lastName: "Le nom doit être entre 3 et 40 caractères.",
            civility: "Veuillez sélectionner une civilité.",
            email: "Entrez une adresse e-mail valide.",
            password: "Le mot de passe doit contenir au moins 8 caractères, une lettre majuscule, un chiffre et un caractère spécial.",
            confirmPassword: "Les mots de passe ne correspondent pas."
        };

        document.querySelectorAll(".error-message").forEach(e => e.innerText = "");
        let hasError = false;

        if (firstName.length < 3 || firstName.length > 40) {
            document.getElementById("prenomErreur").innerText = errors.firstName;
            hasError = true;
        }
        if (lastName.length < 3 || lastName.length > 40) {
            document.getElementById("nomErreur").innerText = errors.lastName;
            hasError = true;
        }
        if (!titleId) {
            document.getElementById("civiliteErreur").innerText = errors.civility;
            hasError = true;
        }
        if (!email || !email.includes("@") || !email.includes(".") || email.length < 5 || email.length > 40) {
            document.getElementById("melErreur").innerText = errors.email;
            hasError = true;
        }
        if (password.length < 8 || password.length > 40 || !/[A-Z]/.test(password) || !/[0-9]/.test(password) || !/[$&+,:;=?@#|'<>.^*()%!-]/.test(password)) {
            document.getElementById("mdpErreur").innerText = errors.password;
            hasError = true;
        }
        if (confirmPassword !== password) {
            document.getElementById("mdp2Erreur").innerText = errors.confirmPassword;
            hasError = true;
        }

        if (hasError) return;

        const hashedPassword = sha256(password);
        const response = await register(lastName, firstName, birthDate, email, hashedPassword, titleId);

        if (response) {
            window.location.href = "../pages/login.html";
        } else {
            document.getElementById("MessageErreur").innerText = response.data.message;
        }
    } catch (error) {
        console.log("Registration error", error);
    }
}
