import React, { Component } from "react";
import Grid from "@material-ui/core/Grid";
import SpinnerLoading from "../common/spinnerLoading";

class PasswordChangeDone extends Component {
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

            <p>Cambio password</p>
            <p className="l">
              Controlla la tua email e segui le istruzioni in essa contenute per
              poter resettare la tua password
            </p>
          </Grid>
        </Grid>
      </div>
    );
  }
}

export default PasswordChangeDone;
