import React from "react";
import Joi from "joi-browser";
import { register  } from "../../services/authService";
import Form from "../common/form";
import Grid from "@material-ui/core/Grid";
import { recaptchaKey } from "../../config.json";
import Recaptcha from 'react-google-invisible-recaptcha';

const emptyData = { 
  name: "", 
  phone: "", 
  email: "", 
  password: "", 
  privacyPolicy: ""
}


 
function onChange(value) {
  console.log("Captcha value:", value);
}
 

class RegistrationForm extends Form {
  state = {
    data: emptyData,
    errors: {},
    isLoggedIn: false,
  };

  schema = {
    name: Joi.string()
      .required()
      .options({ language: { any: { allowOnly: "Il campo nome non può essere vuoto" }, label: "Ragione sociale" } }),
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
            <form onSubmit={this.handleSubmit}>
              {this.renderInput("email", "Email", "email")}
              {this.renderPassword("password", "Password", "password")}
              {this.renderInput("name", "Ragione sociale", "text")}
              {this.renderInput("phone", "Telefono", "number")}
              <div className="row pl-3">{this.renderCheckBox("privacyPolicy", "Ho letto ed accetto la privacy policy")}</div>
              <div className="row pb-3 justify-content-center">
                <Recaptcha
                  ref={ ref => this.recaptcha = ref }
                  sitekey={ recaptchaKey }
                  onResolved={ () => console.log( 'Human detected.' ) } />
              </div>
              <div className="row px-3 justify-content-center">{this.renderButton("Registra", false)}</div>
              <div className="row px-3 justify-content-center"><a href="/login">Sei già registrato?</a></div>
              
            </form>
          </Grid>
        </Grid>
      </div>
    );
  }
}


export default RegistrationForm;
