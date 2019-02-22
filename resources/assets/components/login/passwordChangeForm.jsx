import React from "react";
import Joi from "joi-browser";
import { changePassword } from "../../services/authService";
import Form from "../common/form";
import Grid from "@material-ui/core/Grid";
import Spinner from "react-spinner-material";

const emptyData = {
  email: ""
};

class PasswordChangeForm extends Form {
  state = {
    data: emptyData,
    errors: {},
    waiting: false
  };

  schema = {
    email: Joi.string()
      .email()
      .required()
  };

  doSubmit = async () => {
    try {
      const { email } = this.state.data;
      this.setState({ waiting: true });
      await changePassword(email);
      window.location = "/passwordChangeDone"; // Questo ricarica il browser così si prendono bene tutte le variabili User
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

  handleCancel = () => {
    window.location = "/login";
  };

  render() {
    const { waiting } = this.state;
    return (
      <div className="heightFull">
        <Grid
          container
          direction="row"
          justify="center"
          alignItems="center"
          className="heightFull "
        >
          {!waiting && (
            <Grid item xs={12} sm={9} md={5} lg={3} className="bg-white p-5 c">
              <img src={require("../../logo.png")} className="w-25 mb-4" />
              <p>Cambio password!</p>
              <p className="l">
                Inserisci la tua email e conferma. Ti invieremo una nuova
                password provvisoria con la quale potrai entrare nuovamente nel
                tuo profilo
              </p>
              <form onSubmit={this.handleSubmit}>
                {this.renderInput("email", "Email", "email")}
                {this.renderSubmitButton(
                  "Cambia password",
                  false,
                  "float-left"
                )}
                {this.renderCancelButton("Annulla", true, "float-right")}
              </form>
            </Grid>
          )}
          {waiting && (
            <div
              className="col-xs-12 col-sm-9 col-md-5 col-lg-3"
              className="p-5 justify-content-center"
            >
              <Spinner
                size={40}
                spinnerColor={"#33A"}
                spinnerWidth={2}
                visible={true}
                className="mx-auto"
              />
            </div>
          )}
        </Grid>
      </div>
    );
  }
}

export default PasswordChangeForm;
