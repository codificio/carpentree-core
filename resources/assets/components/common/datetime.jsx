import React from "react";
import PropTypes from 'prop-types';
import { withStyles } from '@material-ui/core/styles';
import TextField from "@material-ui/core/TextField";

const styles = theme => ({
  container: {
    display: 'flex',
    flexWrap: 'wrap',
  },
  textField: {
    marginBottom: "10px",
    width: "100%",
  },
});

const Datetime = ({ name, label, options, error, value, classes, onChange, ...rest }) => {
   
    return ( 
      <TextField
        id= { name }
        name= { name }
        label= { label }
        type="date"
        onChange={onChange}
        className={classes.textField}
        InputLabelProps={{
          shrink: true,
        }}
      />);
}
 
Datetime.propTypes = {
  classes: PropTypes.object.isRequired,
};

export default withStyles(styles)(Datetime);
