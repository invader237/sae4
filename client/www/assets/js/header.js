import { getUser } from "./core/api/api.js";

function waitForElement(selector) {
  return new Promise(resolve => {
    const element = document.querySelector(selector);
    if (element) {
      return resolve(element);
    }

    const observer = new MutationObserver(() => {
      const element = document.querySelector(selector);
      if (element) {
        resolve(element);
        observer.disconnect();
      }
    });

    observer.observe(document.body, {
      childList: true,
      subtree: true
    });
  });
}

(async () => {
  await waitForElement("#printHeader");

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
})();
