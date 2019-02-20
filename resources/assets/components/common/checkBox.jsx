import React, { Component } from "react";
import PropTypes from 'prop-types';
import { withStyles } from '@material-ui/core/styles';
import FormControlLabel from "@material-ui/core/FormControlLabel";
import Checkbox from "@material-ui/core/Checkbox";
import green from '@material-ui/core/colors/green';

const styles = {
  root: {
    color: green[600],
    '&$checked': {
      color: green[500],
    },
  },
  checked: {},
};

class CheckBox extends Component {
  state = {  }
  render() { 
    const { classes, name, label, error, onChange, ...rest } = this.props;

    return ( 
      <div className="form-group">
      <FormControlLabel 
        control={
        <Checkbox 
          name={name}
          classes={{
            root: classes.root,
            checked: classes.checked,
          }}
          onChange={onChange}
          />} 
        label={label}
      />
      {error && (
        <div className="alert alert-danger">{error}</div>
      )}
    </div>
     );
  }
}
 

CheckBox.propTypes = {
  classes: PropTypes.object.isRequired,
};

export default withStyles(styles)(CheckBox);