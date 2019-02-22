import React from "react";
import Button from "@material-ui/core/Button";
import Dialog from "@material-ui/core/Dialog";
import DialogActions from "@material-ui/core/DialogActions";
import DialogContent from "@material-ui/core/DialogContent";
import DialogTitle from "@material-ui/core/DialogTitle";
import Typography from "@material-ui/core/Typography";

export const MsgYesNo = ({
  title,
  text,
  open,
  onMsgYesNoClickYes,
  onMsgYesNoClickNo
}) => {
  return (
    <Dialog
      open={open}
      onClose={onMsgYesNoClickNo}
      aria-labelledby="customized-dialog-title"
    >
      <DialogTitle id="customized-dialog-title" onClose={onMsgYesNoClickNo}>
        {title}
      </DialogTitle>
      <DialogContent>
        <Typography gutterBottom>{text}</Typography>
      </DialogContent>
      <DialogActions>
        <Button onClick={onMsgYesNoClickYes} color="primary">
          Si
        </Button>
        <Button onClick={onMsgYesNoClickNo} color="primary" autoFocus>
          No
        </Button>
      </DialogActions>
    </Dialog>
  );
};
