import React, { Component } from 'react';
import TextField from "@material-ui/core/TextField";
import PropTypes from 'prop-types';
import { withStyles } from '@material-ui/core/styles';
import classes from 'classnames';

const styles = theme => ({
  container: {
    display: 'flex',
    flexWrap: 'wrap',
  },
  textField: {
    marginLeft: theme.spacing.unit,
    marginRight: theme.spacing.unit,
    padding:0
  },
  normal:{
    padding:0
  },
  menu: {
    width: 200,
  },
});

const SearchBox = ({ value, onChange}) => {
  return ( 
    <TextField
        name="query"
        label="Cerca..."
        className={classes.textField}
        value={value}
        margin="normal"
        padding= "normal"
        variant="outlined"
        onChange={e => onChange(e.currentTarget.value)}
      />
   );
}

SearchBox.propTypes = {
  classes: PropTypes.object.isRequired,
};
 
export default withStyles(styles)(SearchBox);