import React from "react";
import Joi from "joi-browser";
import FacebookLogin from "react-facebook-login/dist/facebook-login-render-props";
import GoogleLogin from "react-google-login";
import { login, loginWithFacebook } from "../../services/authService";
import Form from "../common/form";
import {
  facebookAppIdForLogin,
  googleAppIdForLogin,
  loginWithFacebookShow,
  loginWithGoogleShow,
  loginShowRegistration
} from "../../config.json";
import Typography from "@material-ui/core/Typography";
import {
  FacebookLoginButton,
  GoogleLoginButton
} from "react-social-login-buttons";
import SpinnerLoading from "../common/spinnerLoading";

class LoginForm extends Form {
  state = {
    data: { username: "", password: "" },
    errors: {},
    isLoggedIn: false,
    fbUserID: "",
    fbEmail: "",
    loading: false
  };

  schema = {
    username: Joi.string()
      .required()
      .label("Username"),
    password: Joi.string()
      .required()
      .label("Password")
  };

  componentDidMount = () => {
    localStorage.removeItem("token");
  };

  doSubmit = async () => {
    try {
      this.setState({ loading: true });
      const { data } = this.state;
      await login(data.username, data.password);
      window.location = "/"; // Questo ricarica il browser così si prendono bene tutte le variabili User
      this.setState({ data: { username: "" } });
    } catch (ex) {
      if (ex.response && ex.response.status === 400) {
        // Se ho un errore riconosciuto lo scrivo nella label errore dello username
        // Clono gli errori
        const errors = { ...this.state.errors };
        // Aggiungo l'errore al label username
        errors.username = ex.response.data;
        // Setto gli errori
        this.setState({ errors });
      }
    }
  };

  facebookClicked = () => {
    console.log("Facebook clicked");
  };

  facebookResponse = response => {
    console.log("Facebook response", response);
    loginWithFacebook(response);
    window.location = "/";
  };

  googleResponse = response => {
    console.log(response);
  };

  render() {
    const { loading } = this.state;
    return (
      <div className="heightFull">
        <div className="row w-100 h-100 justify-content-center ">
          <div className="col-xs-12 col-sm-9 col-md-5 col-lg-3 c my-auto">
            {loading && (
              <div className="col-12 c">
                <SpinnerLoading />
              </div>
            )}
            {!loading && (
              <div className=" bg-white p-5">
                <img src={require("../../logo.png")} className="w-25 mb-4" />

                <form onSubmit={this.handleSubmit}>
                  {this.renderInput("username", "Login")}
                  {this.renderPassword("password", "Password")}
                  <div className="row pt-2 pb-3 px-3">
                    <div className="col-6 p-0 l">
                      {loginShowRegistration && (
                        <a href="/registration">
                          <small>Registrazione</small>
                        </a>
                      )}
                    </div>
                    <div className="col-6 p-0 r">
                      <a href="/passwordChange">
                        <small>Password dimenticata</small>
                      </a>
                    </div>
                  </div>
                  <button
                    variant="contained"
                    className="btn btn-primary w-25 my-2 "
                    onClick={this.doSubmit}
                  >
                    Login
                  </button>
                </form>

                {(loginWithFacebookShow || loginWithGoogleShow) && (
                  <div>
                    <hr className="mt-4" />
                    <div className="row justify-content-center">
                      <div className="col-4 p-0">
                        <Typography
                          align="center"
                          style={{
                            marginTop: "-28px",
                            marginBottom: "20px",
                            fontSize: "1.05rem",
                            color: "#666",
                            background: "#fff"
                          }}
                        >
                          OPPURE
                        </Typography>
                      </div>
                    </div>
                  </div>
                )}

                {loginWithFacebookShow && (
                  <div className="row mx-1 mb-12">
                    <FacebookLogin
                      appId={facebookAppIdForLogin}
                      callback={this.facebookResponse}
                      render={renderProps => (
                        <FacebookLoginButton
                          onClick={renderProps.onClick}
                          style={{ width: "100%" }}
                        />
                      )}
                    />
                  </div>
                )}

                {loginWithGoogleShow && (
                  <div className="row mx-1">
                    <GoogleLogin
                      clientId={googleAppIdForLogin}
                      buttonText="LOGIN WITH GOOGLE"
                      onSuccess={this.googleResponse}
                      onFailure={this.googleResponse}
                      render={renderProps => (
                        <GoogleLoginButton
                          onClick={renderProps.onClick}
                          style={{ width: "100%" }}
                        />
                      )}
                    />
                  </div>
                )}
              </div>
            )}
          </div>
        </div>
      </div>
    );
  }
}

export default LoginForm;
