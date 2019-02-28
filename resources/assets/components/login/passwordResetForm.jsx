import React from "react";
import { resetPassword } from "../../services/authService";
import queryString from "query-string";
import Joi from "joi-browser";
import Form from "../common/form";
import SpinnerLoading from "../common/spinnerLoading";

const emptyData = {
  password: "",
  password_confirmation: "",
  email: "",
  token: ""
};

class PasswordResetForm extends Form {
  state = {
    data: { ...emptyData },
    errors: {},
    loading: false
  };

  async componentDidMount() {
    localStorage.removeItem("token");
    const values = queryString.parse(this.props.location.search);
    const { email, token } = values;
    await this.setState({ data: { email, token } });
  }

  schema = {
    password: Joi.string()
      .required()
      .label("Passowrd"),
    password_confirmation: Joi.string()
      .required()
      .label("Conferma password")
  };

  doSubmit = async () => {
    const { data } = this.state;
    try {
      this.setState({ loading: true });
      await resetPassword(data);
      window.location = "/passwordResetDone";
      this.setState({ data: emptyData });
    } catch (error) {}
  };

  render() {
    const { data, loading } = this.state;

    return (
      <div className="row justify-content-center h-100">
        {loading && <SpinnerLoading />}
        {!loading && (
          <div className="col-xs-12 col-sm-9 col-md-5 col-lg-3 bg-white p-5 c my-auto">
            <img src={require("../../logo.png")} className="w-25 mb-4" />
            <p>Reset password!</p>
            <p className="l">
              Specifica la tua nuova password segreta e clicca sul pulsante{" "}
              <strong>Salva</strong>
            </p>
            <form onSubmit={this.handleSubmit}>
              {this.renderPassword("password", "Password")}
              {this.renderPassword(
                "password_confirmation",
                "Conferma password"
              )}
              <button
                variant="contained"
                className="btn btn-primary w-25 my-2"
                onClick={this.doSubmit}
              >
                Salva
              </button>
            </form>
          </div>
        )}
      </div>
    );
  }
}

export default PasswordResetForm;
