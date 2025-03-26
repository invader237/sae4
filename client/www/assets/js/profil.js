import { getUser } from './core/api/api.js';

const response = await getUser();

if (response === undefined) {
    window.location.href = "./login.html";
}

const user = response.data
document.getElementById("name").innerHTML = user.name;
document.getElementById("firstName").innerHTML = user.firstName;
document.getElementById("civ").innerHTML = user.idTitle;
document.getElementById("mail").innerHTML = user.email;
document.getElementById("birthDate").innerHTML = user.birthDate;