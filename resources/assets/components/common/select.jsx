import React from "react";
import PropTypes from "prop-types";
import { withStyles } from "@material-ui/core/styles";
import FormControl from "@material-ui/core/FormControl";
import InputLabel from "@material-ui/core/InputLabel";
import SelectMaterial from "@material-ui/core/Select";
import MenuItem from "@material-ui/core/MenuItem";

const styles = theme => ({
  root: {
    display: "flex",
    flexWrap: "wrap"
  },
  formControl: {
    width: "100%",
    marginBottom: 20,
    textAlign: "left"
  },
  selectEmpty: {
    marginTop: theme.spacing.unit * 2
  }
});

const Select = ({
  name,
  label,
  options,
  error,
  value,
  classes,
  handleChange,
  multiple,
  ...rest
}) => {
  const inputProps = {
    name: name,
    id: name
  };

  return (
    <FormControl className={classes.formControl}>
      <InputLabel htmlFor={name}>{label}</InputLabel>
      {multiple === true ? (
        <SelectMaterial
          value={value}
          onChange={handleChange}
          inputProps={inputProps}
          multiple
          {...rest}
        >
          {options.map(option => (
            <MenuItem key={option.id} value={option.id}>
              {option.attributes.name}
            </MenuItem>
          ))}
        </SelectMaterial>
      ) : (
        <SelectMaterial
          value={value}
          onChange={handleChange}
          inputProps={inputProps}
          {...rest}
        >
          {options.map(option => (
            <MenuItem key={option.id} value={option.id}>
              {option.name}
            </MenuItem>
          ))}
        </SelectMaterial>
      )}
    </FormControl>
  );
};

Select.propTypes = {
  classes: PropTypes.object.isRequired
};

export default withStyles(styles)(Select);
