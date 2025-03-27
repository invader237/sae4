import { getUser, changePassword } from "./core/api/api.js";

const response = await getUser();

if (response === undefined) {
    window.location.href = "./profil.html";
}


const changePasswordForm = document.getElementById("changePasswordForm");
const errorMessage = document.getElementById("error");

changePasswordForm.addEventListener("submit", async (event) => {
    console.log("changePasswordForm.addEventListener");
    event.preventDefault();
    await changeUserPassword();
});

async function changeUserPassword() {
    const password = document.getElementById("oldPassword").value;
    const newPassword = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    if (newPassword !== confirmPassword) {
        displayError("Les mots de passe ne correspondent pas.");
        return;
    }

    const hashPassword = sha256(password);
    const hashNewPassword = sha256(newPassword);

    const response = await changePassword(hashPassword, hashNewPassword);
    if (response === undefined) {
        displayError("Mot de passe incorrect.");
        return;
    }

    window.location.href = "./profil.html";
}


function displayError(message) {
    errorMessage.innerText = message;
    errorMessage.removeAttribute("hidden");
}
