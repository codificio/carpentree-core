import React, { Component } from "react";
import { NavLink } from "react-router-dom";
import { logout } from "../../services/authService";
import Drawer from "@material-ui/core/Drawer";
import List from "@material-ui/core/List";
import Divider from "@material-ui/core/Divider";
import ListItem from "@material-ui/core/ListItem";
import ListItemIcon from "@material-ui/core/ListItemIcon";
import ListItemText from "@material-ui/core/ListItemText";
import GroupIco from "@material-ui/icons/Group";
import LocalFloristIcon from "@material-ui/icons/LocalFlorist";
import FolderIcon from "@material-ui/icons/Folder";
import Panorama from "@material-ui/icons/Panorama";
import FaceIcon from "@material-ui/icons/Face";
import ShoppingBasket from "@material-ui/icons/ShoppingBasket";
import Avatar from "@material-ui/core/Avatar";
import Grid from "@material-ui/core/Grid";
import "../../App.css";

const navBarItems = [
  { label: "Utenti", path: "/users", icon: <GroupIco /> },
  { label: "Prodotti", path: "/products", icon: <LocalFloristIcon /> },
  { label: "Ordini", path: "/orders", icon: <ShoppingBasket /> },
  { label: "File", path: "/files", icon: <FolderIcon /> },
  { label: "Gallery", path: "/gallery", icon: <Panorama /> }
];

const NavLinkStyle = {
  textDecoration: "none"
};

class NavBar extends Component {
  state = {};

  handleLogout() {
    logout();
    window.location = "/";
  }

  getSelectedItem = item => {
    const path = window.location.href.split("/").slice(-1)[0];
    return item.path === path;
  };

  render() {
    const { classes, user } = this.props;

    return (
      <Drawer
        className={classes.drawer}
        variant="permanent"
        classes={{
          paper: classes.drawerPaper
        }}
      >
        <List>
          <div className="p-3 c">
            <img src={require("../../logo.png")} className="w-25" />
          </div>
          <Divider />
          <div className="mt-4 c">
            {user.facebook === true && user.picture != undefined ? (
              <Grid container justify="center" alignItems="center">
                <Avatar
                  onClick={this.handleLogout}
                  alt="Remy Sharp"
                  src={user.picture.data.url}
                  className={classes.bigAvatar + " c-pointer"}
                />
              </Grid>
            ) : (
              <FaceIcon
                onClick={this.handleLogout}
                className="c-pointer"
                tooltip="Logout"
              />
            )}
          </div>
          <div className="mt-1 mb-4 c">{user.name}</div>
          <Divider />
          <div className="p-4">
            {navBarItems.map(item => (
              <NavLink to={item.path} style={NavLinkStyle} key={item.label}>
                <ListItem button selected={this.getSelectedItem(item)}>
                  <ListItemIcon className="m-0">{item.icon}</ListItemIcon>
                  <ListItemText className="m-0" primary={item.label} />
                </ListItem>
              </NavLink>
            ))}
          </div>
        </List>
      </Drawer>
    );
  }
}

export default NavBar;
