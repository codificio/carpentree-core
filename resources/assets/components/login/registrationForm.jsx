import React from "react";
import Joi from "joi-browser";
import Form from "../common/form";
import Grid from "@material-ui/core/Grid";
import { recaptchaKey } from "../../config.json";
import Recaptcha from "react-google-invisible-recaptcha";
import Button from "@material-ui/core/Button";
import Dialog from "@material-ui/core/Dialog";
import DialogActions from "@material-ui/core/DialogActions";
import DialogContent from "@material-ui/core/DialogContent";
import DialogTitle from "@material-ui/core/DialogTitle";
import Typography from "@material-ui/core/Typography";

const emptyData = {
  name: "",
  phone: "",
  email: "",
  password: "",
  privacyPolicy: ""
};

function onChange(value) {
  console.log("Captcha value:", value);
}

class RegistrationForm extends Form {
  state = {
    data: emptyData,
    errors: {},
    isLoggedIn: false,
    opened: true
  };

  schema = {
    name: Joi.string()
      .required()
      .options({
        language: {
          any: { allowOnly: "Il campo nome non può essere vuoto" },
          label: "Ragione sociale"
        }
      }),
    phone: Joi.string()
      .required()
      .label("Telefono"),
    email: Joi.string()
      .required()
      .email()
      .label("Email"),
    password: Joi.string()
      .required()
      .label("Password"),
    privacyPolicy: Joi.any()
      .valid([true, 1, "on"])
      .required()
      .label("Privacy policy")
  };

  handleClose = () => {
    this.setState({ opened: false });
    window.location = "/login";
  };

  doSubmit = async () => {
    try {
      const { data } = this.state;
      // await register(data);
      window.location = "/registrationDone"; // Questo ricarica il browser così si prendono bene tutte le variabili User
      this.setState({ data: emptyData });
    } catch (ex) {
      if (ex.response && ex.response.status === 400) {
        // Se ho un errore riconosciuto lo scrivo nella label errore dello username
        // Clono gli errori
        const errors = { ...this.state.errors };
        // Aggiungo l'errore al label username
        errors.email = ex.response.data;
        // Setto gli errori
        this.setState({ errors });
      }
    }
  };

  render() {
    const { opened } = this.state;
    return (
      <Dialog
        open={opened}
        onClose={this.handleClose}
        aria-labelledby="customized-dialog-title"
      >
        <DialogTitle id="customized-dialog-title" onClose={this.handleClose}>
          Registrazione utente
          <hr />
        </DialogTitle>
        <DialogContent>
          <div className="row" style={{ width: "400px" }}>
            <div className="col-12 bg-white p-3 c">
              <form onSubmit={this.handleSubmit}>
                {this.renderInput("email", "Email", "email")}
                {this.renderPassword("password", "Password", "password")}
                {this.renderInput("name", "Ragione sociale", "text")}
                {this.renderInput("phone", "Telefono", "number")}
                <div className="row pl-5">
                  {this.renderCheckBox(
                    "privacyPolicy",
                    "Ho letto ed accetto la privacy policy"
                  )}
                </div>
                <div className="row pb-3 justify-content-center">
                  <Recaptcha
                    ref={ref => (this.recaptcha = ref)}
                    sitekey={recaptchaKey}
                    onResolved={() => console.log("Human detected.")}
                  />
                </div>
                <div className="row px-3 justify-content-center">
                  {this.renderSubmitButton("Registra", false)}
                </div>
                <div className="row px-3 mt-3 justify-content-center c-pointer">
                  <a className="text-primary " onClick={this.handleClose}>
                    Sei già registrato?
                  </a>
                </div>
              </form>
            </div>
          </div>
        </DialogContent>
      </Dialog>
    );
  }
}

export default RegistrationForm;
