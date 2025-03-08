import axiosInstance from "./axiosConfig.js";

/* 
export const <functionName> = async () => {
    try {
        const response = await axiosInstance.get("<apiEndpoint>");
        return response.data;
    } catch (error) {
        console.error(error);
    }
    };
*/

export const getAllProducts = async () => {
    try {
        const response = await axiosInstance.get("/getAllProducts");
        return response.data;
    } catch (error) {
        console.error(error);
    }
}