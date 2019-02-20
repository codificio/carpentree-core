import React, { Component, Fragment } from "react";
import MoreVertIcon from "@material-ui/icons/MoreVert";
import Button from "@material-ui/core/Button";
import Popper from "@material-ui/core/Popper";
import Grow from "@material-ui/core/Grow";
import Paper from "@material-ui/core/Paper";
import MenuList from "@material-ui/core/MenuList";
import MenuItem from "@material-ui/core/MenuItem";
import ClickAwayListener from "@material-ui/core/ClickAwayListener";

class TablePopper extends Component {
  state = { 
    open: false, 
    item: {}, 
    popperShows: {} 
  };

  componentDidMount() {
    const { item, popperShows } = this.props;
    this.setState({ item });
    this.setState({ popperShows });
  }

  handleToggle = () => {
    const open = !open;
    this.setState({ open });
  };

  handleClose = () => {
    const open = false;
    this.setState({ open });
  };

  render() {
    const { open, item, popperShows } = this.state;
    const { onDelete, onEdit } = this.props;

    return (
      <Fragment>
        <Button
          size="small"
          buttonRef={node => {
            this.anchorEl = node;
          }}
          aria-owns={open ? "menu-list-grow" : undefined}
          aria-haspopup="true"
          onClick={this.handleToggle}
        >
          <MoreVertIcon className="c-pointer" style={{ fontSize: 18 }} />
        </Button>
        <Popper
          open={open}
          anchorEl={this.anchorEl}
          transition
          disablePortal
          style={{ zIndex: 100 }}
          placement="left"
        >
          {({ TransitionProps, placement }) => (
            <Grow
              {...TransitionProps}
              id="menu-list-grow"
              style={{
                transformOrigin:
                  placement === "bottom" ? "center top" : "center bottom"
              }}
            >
              <Paper>
                <ClickAwayListener onClickAway={this.handleClose}>
                  <MenuList>
                    { popperShows.edit && <MenuItem onClick={() => onEdit(item)}>Modifica</MenuItem> }
                    { popperShows.delete && <MenuItem onClick={() => onDelete(item)}>Rimuovi</MenuItem> }
                  </MenuList>
                </ClickAwayListener>
              </Paper>
            </Grow>
          )}
        </Popper>
      </Fragment>
    );
  }
}

export default TablePopper;
