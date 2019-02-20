import React, { Component } from "react";
import PropTypes from 'prop-types';
import TextField from "@material-ui/core/TextField";
import { InputAdornment, withStyles } from '@material-ui/core';
import Visibility from '@material-ui/icons/Visibility';
import VisibilityOff from '@material-ui/icons/VisibilityOff';

const styles = theme => ({
  eye: {
    cursor: 'pointer',
  },
});



class Password extends Component  {
  state = {
    passwordIsMasked: true,
    name: "password",
    label: "Password"
  }

  togglePasswordMask = () => {
    this.setState(prevState => ({
      passwordIsMasked: !prevState.passwordIsMasked,
    }));
  };

  render() { 
    const { classes, error, name, label, value, ...rest } = this.props;
    const { passwordIsMasked } = this.state;

    return (

      <div className="form-group">
        <TextField
          {...rest}
          name={name}
          margin="dense"
          id={name}
          label={label}
          type={passwordIsMasked ? 'password' : 'text'}
          className="form-control"
          InputProps={{
            endAdornment: (
              <InputAdornment position="end">
                { passwordIsMasked ? <VisibilityOff className={classes.eye} onClick={this.togglePasswordMask}/> : <Visibility className={classes.eye} onClick={this.togglePasswordMask} /> }
              </InputAdornment>
            ),
          }}
        />
        {error && (
          <div className="alert alert-danger">{error}</div>
        )}
      </div>
    );
  }
};

Password.propTypes = {
  classes: PropTypes.object.isRequired,
};

export default withStyles(styles)(Password);
