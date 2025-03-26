import { getUser } from './core/api/api.js';

const response = await getUser();

if (response === undefined) {
    window.location.href = "./login.html";
}