import React from "react";
import TextField from "@material-ui/core/TextField";

const Input = ({ name, label, error, errorDisabled = false, ...rest }) => {
  return (
    <div className="form-group">
      <TextField
        {...rest}
        name={name}
        margin="dense"
        id={name}
        label={label}
        className="form-control"
      />
      {error && !errorDisabled && (
        <div className="alert alert-danger">{error}</div>
      )}
    </div>
  );
};

export default Input;
