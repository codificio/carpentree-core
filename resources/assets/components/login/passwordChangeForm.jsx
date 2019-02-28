import React from "react";
import Joi from "joi-browser";
import { changePassword } from "../../services/authService";
import Form from "../common/form";
import Grid from "@material-ui/core/Grid";
import SpinnerLoading from "../common/spinnerLoading";

const emptyData = {
  email: ""
};

class PasswordChangeForm extends Form {
  state = {
    data: { ...emptyData },
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
      /*this.props.history.push({
        pathname: "/passwordChangeDone",
        data: { email }
      });*/
      window.location = "/passwordChangeDone";
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
          {waiting && <SpinnerLoading />}
        </Grid>
      </div>
    );
  }
}

export default PasswordChangeForm;
