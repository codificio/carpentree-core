import axios from "axios";
import logger from "./logService";
import { toast } from "react-toastify";

axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

axios.interceptors.response.use(null, error => {
  const expectedError =
    error.response &&
    error.response.status >= 400 &&
    error.response.status < 500;

  if (error.response.status == 401) {
    //toast.error("Errore imprevisto");
    localStorage.removeItem("token");
    localStorage.removeItem("facebookToken");
    window.location = "/login";
  }

  if (!expectedError) {
    logger.log(error);
    //toast.error("Errore imprevisto");
  }

  return Promise.reject(error);
});

function setJwt(jwt) {
  axios.defaults.headers.common["x-auth-token"] = jwt;
}

export default {
  get: axios.get,
  post: axios.post,
  put: axios.put,
  delete: axios.delete,
  setJwt
};
