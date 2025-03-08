const axiosInstance = axios.create({
    baseURL: "http://localhost:8080/api",
});

axiosInstance.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem("authToken"); 
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => Promise.reject(error)
);

axiosInstance.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response) {
            console.error("Erreur Axios détectée :", error.response);
        }
        return Promise.reject(error);
    }
);


export default axiosInstance;
