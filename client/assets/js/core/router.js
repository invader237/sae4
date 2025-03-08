const routes = {
    "/": "pages/accueil.html",
    "/login": "pages/login.html",
    "/register": "pages/register.html",
};

const loadPage = async () => {
    const path = window.location.hash.replace("#", "") || "/";
    const page = routes[path] || routes["/"];

    try {
        const response = await fetch(page);
        if (!response.ok) throw new Error("Page not found");
        const content = await response.text();
        let container = document.getElementById("app");
        container.innerHTML = content;
        container.querySelectorAll("script").forEach(oldScript => {
            let newScript = document.createElement("script");
            newScript.textContent = oldScript.textContent;
            newScript.setAttribute("src", oldScript.getAttribute("src"));
            newScript.setAttribute("type", oldScript.getAttribute("type"));
            document.body.appendChild(newScript); 
        });
    } catch (error) {
        document.getElementById("app").innerHTML = "<h2>Page non trouvée</h2>";
    }
};

window.addEventListener("hashchange", loadPage);
window.addEventListener("DOMContentLoaded", loadPage);
