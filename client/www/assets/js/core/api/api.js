import axiosInstance from "./axiosConfig.js";

export const login = async (email, password) => {
    try {
        const response = await axiosInstance.post("/auth/login", {
            email: email,
            password: password
        });
        return response;
    } catch (error) {
        throw error.response
    }
}

export const register = async (nom, prenom, date_naissance, email, mdp, id_civilite) => {
    try {
        const response = await axiosInstance.post("/auth/register", {
            nom: nom,
            prenom: prenom,
            date_naissance: date_naissance,
            email: email,
            mdp: mdp,
            id_civilite: id_civilite
        });
        return response.data;
    } catch (error) {
        return error.response.data;
    }
}

export const getAllProducts = async () => {
    try {
        const response = await axiosInstance.get("/getAllProducts");
        return response.data;
    } catch (error) {
        console.error(error);
    }
}

export const getAllProductsFilter = async (search, category, color, size) => {
    try {
        const response = await axiosInstance.get("/getAllProducts", {
            params: {
                search: search,
                category: category,
                color: color,
                size: size
            }
        });
        return response.data;
    } catch (error) {
        console.error(error);
    }
}
