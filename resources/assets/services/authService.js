import jwtDecode from "jwt-decode";
import http from "./httpService";
import {
  apiUrl,
  fakeLoginMode,
  fakeToken,
  client_id,
  client_secret,
  grant_type,
  scope
} from "../config.json";

const apiEndpoint = apiUrl + "/oauth/token";

http.setJwt(getJwt());

export async function login(username, password) {
  const jwt = fakeToken;
  if (!fakeLoginMode) {
    const { data: jwt } = await http.post(apiEndpoint, {
      username,
      password,
      client_id,
      client_secret,
      grant_type,
      scope
    });
  }
  localStorage.setItem("token", jwt);
  localStorage.removeItem("facebookToken");
}

export function loginWithFacebook(response) {
  localStorage.removeItem("token");
  localStorage.setItem("facebookToken", JSON.stringify(response));
}

export function loginWithJwt(jwt) {
  localStorage.setItem("token", jwt);
}

export function logout() {
  localStorage.removeItem("token");
  localStorage.removeItem("facebookToken");
}

export function getCurrentUser() {
  try {
    // Facebook
    const facebookToken = localStorage.getItem("facebookToken");
    if (facebookToken != undefined) {
      let user = JSON.parse(facebookToken);
      user.facebook = true;
      return user;
    }

    // Login e Password
    const jwt = localStorage.getItem("token");
    let user = jwtDecode(jwt);
    return user;
  } catch (ex) {
    return null;
  }
}

export function register() {}

export function changePassword() {}

export function getJwt() {
  return localStorage.getItem("token");
}

export default {
  login,
  loginWithFacebook,
  loginWithJwt,
  logout,
  getCurrentUser,
  getJwt,
  register,
  changePassword
};
