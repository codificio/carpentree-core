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

http.setJwt(getJwt());

export async function login(username, password) {
  const token = fakeToken;
  if (!fakeLoginMode) {
    const { data } = await http.post(apiUrl + "/oauth/token", {
      username,
      password,
      client_id,
      client_secret,
      grant_type,
      scope
    });
    if (data.access_token) {
      localStorage.setItem("token", data.access_token);
    } else {
    }
  } else {
    localStorage.setItem("token", token);
    localStorage.removeItem("facebookToken");
  }
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
    let user = "";

    // Facebook
    const facebookToken = localStorage.getItem("facebookToken");
    if (facebookToken != undefined) {
      let user = JSON.parse(facebookToken);
      user.facebook = true;
      return user;
    }

    // Login e Password
    const token = localStorage.getItem("token");
    if (token) {
      user = { name: "Giorgio" };
    }

    return user;
  } catch (ex) {
    return null;
  }
}

export function register() {}

export async function changePassword(email) {
  const { data: result } = await http.post(apiUrl + "/password/email", {
    email
  });
}

export async function resendEmail(email) {
  const { data: result } = await http.post(apiUrl + "/email/resend", {
    email
  });
}

export async function resetPassword(data) {
  const { email, token, password, password_confirmation } = data;
  const { result } = await http.post(apiUrl + "/password/reset", {
    email,
    token,
    password,
    password_confirmation
  });
}

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
