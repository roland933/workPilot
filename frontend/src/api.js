import axios from "axios";
import { setError } from "./stores/error";

const api = axios.create({
  baseURL: "http://localhost:8000/api",
});

api.interceptors.request.use((config) => {
  const token = localStorage.getItem("token");

  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }

  return config;
});


api.interceptors.response.use(
  (response) => response,
  (error) => {

    // ha a request mondja hogy NE kezeljük globálisan
    if (error.config?.skipGlobalError) {
      return Promise.reject(error);
    }

    let message = "Something went wrong";

    if (error.response?.data?.message) {
      message = error.response.data.message;
    }

    setError(message);

    return Promise.reject(error);
  }
);

export default api;