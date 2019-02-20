import React from "react";
import Joi from "joi-browser";
import { changePassword } from "../../services/authService";
import Form from "../common/form";
import Grid from "@material-ui/core/Grid";

const emptyData = {
  email: ""
};

class PasswordChangeForm extends Form {
  state = {
    data: emptyData,
    errors: {},
    isLoggedIn: false
  };

  schema = {
    email: Joi.string()
      .email()
      .required()
  };

  doSubmit = async () => {
    try {
      const { data } = this.state;
      // await register(data);
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
  }

  render() {
    return (
      <div className="heightFull">
        <Grid
          container
          direction="row"
          justify="center"
          alignItems="center"
          className="heightFull "
        >
          <Grid item xs={12} sm={9} md={5} lg={3} className="bg-white p-5 c">
            <img src={require("../../logo.png")} className="w-25 mb-4" />
            <p>Cambio password!</p>
            <p>
              Inserisci la tua email e conferma. Ti invieremo una nuova password
              provvisoria con la quale potrai entrare nuovamente nel tuo profilo
            </p>
            <form onSubmit={this.handleSubmit}>
              {this.renderInput("email", "Email", "email")}
              {this.renderButton("Cambia password", false, "float-left")}
              {this.renderCancelButton("Annulla", true, "float-right")}
            </form>
          </Grid>
        </Grid>
      </div>
    );
  }
}

export default PasswordChangeForm;
