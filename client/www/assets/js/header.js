import { getUser } from "./core/api/api.js";

const userIcon = document.getElementById("userIcon");
const userName = document.getElementById("user");
const loginIcon = document.getElementById("login");
const registerIcon = document.getElementById("register");

userIcon.classList.add("d-none");

try {
  const response = await getUser();
  const user = response?.data;

  if (user) {
    userIcon.classList.remove("d-none");
    userName.classList.remove("d-none");
    userName.innerText = `${user.firstName} ${user.name}`;

    loginIcon?.classList.add("d-none");
    registerIcon?.classList.add("d-none");
  } else {
    loginIcon?.classList.remove("d-none");
    registerIcon?.classList.remove("d-none");
  }

  console.log("Utilisateur connecté :", user);
} catch (error) {
  console.error("Erreur lors de la récupération de l'utilisateur :", error);
  loginIcon?.classList.remove("d-none");
  registerIcon?.classList.remove("d-none");
}
