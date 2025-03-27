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

export const getProductByIdAndColorAndSize = async (id, color, size) => {
    try {
        const response = await axiosInstance.get("/getProductByIdAndColorAndSize", {
            params: {
                id: id,
                color: color,
                size: size
            }
        });
        return response.data;
    } catch (error) {
        console.error(error);
    }
};

export const getColorsByProductId = async (id) => {
    try {
        const response = await axiosInstance.get("/getColorsByProductId", {
            params: {
                id: id
            }
        });
        return response.data;
    } catch (error) {
        console.error(error);
    }
};

export const getSizesByProductId = async (id) => {
    try {
        const response = await axiosInstance.get("/getSizesByProductId", {
            params: {
                id: id
            }
        });
        return response.data;
    } catch (error) {
        console.error(error);
    }
};

export const getCart = async () => {
    try {
        const response = await axiosInstance.get("/getPanier");
        return response.data;
    } catch (error) {
        console.error(error);
    }
}

export const addToCart = async (id, quantity, color, size) => {
    try {
        const response = await axiosInstance.post("/addProduit", {
            idProduct: id,
            quantity: quantity,
            idColor: color,
            idSize: size,
        });
        return response.data;
    } catch (error) {
        console.error(error);
    }
}

export const deleteFromCart = async (id, color, size) => {
    try {
        const response = await axiosInstance.delete("/deleteProduct", {
            data: {
                idProduct: id,
                idColor: color,
                idSize: size
            }
        });
        return response.data;
    } catch (error) {
        console.error("Erreur axios delete:", error);
    }
};

export const deleteAllFromCart = async () => {
    try {
        const response = await axiosInstance.delete("/deleteAllProducts");
        return response.data;
    } catch (error) {
        console.error("Erreur axios delete:", error);
    }
}

export const getUser = async () => {
    try {
        const response = await axiosInstance.get("/getUser");
        return response.data;
    } catch (error) {
        console.error(error);
    }
}

export const getDelivery = async () => {
    try {
        const response = await axiosInstance.get("/delivery");
        return response.data;
    } catch (error) {
        console.error(error);
    }
}

export const getAllColors = async () => {
    try {
        const response = await axiosInstance.get("/getColor");
        return response.data;
    } catch (error) {
        console.error(error);
    }
}

export const getAllSizes = async () => {
    try {
        const response = await axiosInstance.get("/getSize");
        return response.data;
    } catch (error) {
        console.error(error);
    }
}

export const getAllCategorys = async () => {
    try {
        const response = await axiosInstance.get("/getCategory");
        return response.data;
    } catch (error) {
        console.error(error);
    }
}

export const validateOrder = async (idPayment, idDelivery, deliveryAddress) => {
    try {
        const response = await axiosInstance.post("/createOrder", {
            idPayment: idPayment,
            idDelivery: idDelivery,
            deliveryAddress: deliveryAddress
        });
        return response.data;
    } catch (error) {
        console.error(error);
    }
}

export const getOrders = async () => {
    try {
        const response = await axiosInstance.get("/getAllOrders");
        return response.data;
    } catch (error) {
        console.error(error);
    }
}

export const getOrderById = async (id) => {
    try {
        const response = await axiosInstance.get("/getOrderById", {
            params: {
                idOrder: id
            }
       });
        return response.data;
    } catch (error) {
        console.error(error);
    }
}

export const getSku = async (id, idSize, idColor) => {
    try {
        const response = await axiosInstance.get("/getSku", {
            params: {
                id: id,
                idSize: idSize,
                idColor: idColor
            }
        });
        return response.data;
    } catch (error) {
        console.error(error);
    }
}

export const getFavorites=async()=>{
    try {
        const response=await axiosInstance.get("/getFavorites");
        return response.data;
    } catch (error) {
        console.error(error);
    }
}

export const changePassword = async (oldPassword, newPassword) => {
    try {
        const response = await axiosInstance.put("/auth/changePassword", {
            oldPassword: oldPassword,
            newPassword: newPassword
        });
        return response.data;
    } catch (error) {
        console.error(error);
    }
}

export const addFavorites=async(id, size, color)=>{
    try {
        console.log("Ajout aux favoris:", { id, size, color });
        const response=await axiosInstance.post("/addFavorites",{
            idProduct: id,
            idSize: size,
            idColor: color,
        });
        return response.data;
    } catch(error) {
        console.error(error);
    }
}

export const removeFavorites=async(id,size,color)=>{
    try {
        console.log("Retrait des favoris:", { id, size, color });
        const response=await axiosInstance.delete("/removeFavorites",{
            data: {
                idProduct: id,
                idColor: color,
                idSize: size
            }
        });
        return response.data;
    } catch(error) {
        console.error(error);
    }
}

export const removeAllFavorites=async()=>{
    try {
        const response=await axiosInstance.delete("/removeAllFavorites");
        return response.data;
    } catch (error) {
        console.error(error);
    }
}
