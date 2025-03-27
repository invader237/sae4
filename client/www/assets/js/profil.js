import { getUser } from './core/api/api.js';

const response = await getUser();

if (!response) {
  window.location.href = "./login.html";
}

const user = response.data;

// Remplir les champs
const fields = {
  inputName: user.name,
  inputFirstName: user.firstName,
  inputCiv: user.idTitle,
  inputMail: user.email,
  inputBirthDate: user.birthDate,
};

for (const [id, value] of Object.entries(fields)) {
  document.getElementById(id).value = value;
}

// Gestion de la déconnexion
const logout = document.getElementById("logout");
logout.addEventListener("click", () => {
  localStorage.removeItem("authToken");
  window.location.href = "./accueil.html";
});

// Gestion du mode édition
const editBtn = document.getElementById('editBtn');
const editActionBtn = document.getElementById('editActionBtn');
const saveBtn = document.getElementById('saveBtn');
const cancelBtn = document.getElementById('cancelBtn');
const form = document.getElementById('profileForm');
const inputs = form.querySelectorAll('input');

editBtn.addEventListener('click', () => {
  inputs.forEach(input => input.disabled = false);
  editBtn.classList.add('d-none');
  editActionBtn.classList.remove('d-none');
  saveBtn.classList.remove('d-none');
  cancelBtn.classList.remove('d-none');
});

cancelBtn.addEventListener('click', () => {
  for (const [id, value] of Object.entries(fields)) {
    document.getElementById(id).value = value;
  }
  inputs.forEach(input => input.disabled = true);
  editBtn.classList.remove('d-none');
  saveBtn.classList.add('d-none');
  cancelBtn.classList.add('d-none');
  editActionBtn.classList.add('d-none');
});

form.addEventListener('submit', (e) => {
  e.preventDefault();
  const updatedData = {
    name: document.getElementById('inputName').value,
    firstName: document.getElementById('inputFirstName').value,
    civ: document.getElementById('inputCiv').value,
    birthDate: document.getElementById('inputBirthDate').value,
    mail: document.getElementById('inputMail').value,
  };

  console.log("Enregistrement des données :", updatedData);
  // TODO: Envoyer les données mises à jour au serveur avec Axios

  inputs.forEach(input => input.disabled = true);
  saveBtn.classList.add('d-none');
  cancelBtn.classList.add('d-none');
  editBtn.classList.remove('d-none');
});

const changePasswordBtn = document.getElementById('changePassword');
changePasswordBtn.addEventListener('click', () => {
  window.location.href = "./changePassword.html";
});

