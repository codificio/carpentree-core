import React from "react";
import Joi from "joi-browser";
import { register } from "../../services/authService";
import Form from "../common/form";
import Typography from "@material-ui/core/Typography";
import Grid from "@material-ui/core/Grid";

class RegistraionDone extends Form {
  state = {};

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
            <Typography variant="h5">
              <p>Registraione avvenuta con successo</p>
            </Typography>
            <Typography variant="subtitle2">
              <p>
                Controlla la tua casella di posta elettronica e conferma la tua
                email tramite il link che ti abbiamo fornito
              </p>
            </Typography>
            <Typography variant="subtitle2">
              <a href="/login">torna alla schermata di Login</a>
            </Typography>
          </Grid>
        </Grid>
      </div>
    );
  }
}

export default RegistraionDone;
