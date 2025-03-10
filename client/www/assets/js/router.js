const routes = {
    "/": "pages/accueil.html",
    "/test": "pages/test.html",
};

const loadPage = async () => {
    const path = window.location.hash.replace("#", "") || "/";
    const page = routes[path] || routes["/"];
    
    try {
        const response = await fetch(page);
        if (!response.ok) throw new Error("Page not found");
        const content = await response.text();
        document.getElementById("app").innerHTML = content;
    } catch (error) {
        document.getElementById("app").innerHTML = "<h2>Page non trouvée</h2>";
    }
};

window.addEventListener("hashchange", loadPage);
window.addEventListener("DOMContentLoaded", loadPage);
