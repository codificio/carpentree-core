import React from "react";
import ResetPassword from "../../services/authService";
import { apiUrl } from "../../config.json";
import queryString from "query-string";
import Joi from "joi-browser";
import Form from "../common/form";

const emptyData = {
  password: "",
  email: "",
  token: ""
};

class PasswordResetForm extends Form {
  state = {
    data: emptyData,
    errors: {}
  };

  async componentDidMount() {
    localStorage.removeItem("token");
    const values = queryString.parse(this.props.location.search);
    this.setState({ token: values.token, email: values.email });
  }

  schema = {
    password: Joi.string()
      .required()
      .label("Passowrd")
  };

  doSubmit = async () => {
    const { data } = this.state;
    try {
      await ResetPassword(data);
    } catch (error) {}
  };

  render() {
    const { data } = this.state;

    return (
      <div className="row justify-content-center h-100">
        <div className="col-xs-12 col-sm-9 col-md-5 col-lg-3 bg-white p-5 c my-auto">
          <img src={require("../../logo.png")} className="w-25 mb-4" />
          <p>Reset password!</p>
          <p className="l">
            Specifica la tua nuova password segreta e clicca sul pulsante{" "}
            <strong>Salva</strong>
          </p>
          <form onSubmit={this.handleSubmit}>
            {this.renderPassword("password", "Password")}
            <button
              variant="contained"
              className="btn btn-primary w-25 my-2"
              onClick={this.doSubmit}
            >
              Salva
            </button>
          </form>
        </div>
      </div>
    );
  }
}

export default PasswordResetForm;
