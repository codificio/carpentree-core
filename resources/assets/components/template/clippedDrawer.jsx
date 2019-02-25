import React, { Component } from "react";
import { Route, Switch, Redirect } from "react-router-dom";
import ProtectedRoute from "../common/protectedRoute";
import auth from "../../services/authService";
import config from "../../config.json";
import { withStyles } from "@material-ui/core/styles";
import NavBar from "./navBar";
import Users from "../pages/users";
import Products from "../pages/products";
import ProductForm from "../pages/productForm";
import Orders from "../pages/orders";
import Files from "../pages/files";
import FilesUpload from "../pages/filesUpload";
import Gallery from "../pages/gallery";
import GalleryUpload from "../pages/galleryUpload";
import LoginForm from "../login/loginForm";
import RegistrationForm from "../login/registrationForm";
import RegistrationDone from "../login/registrationDone";
import PasswordResetForm from "../login/passwordResetForm";
import PasswordChangeForm from "../login/passwordChangeForm";
import PasswordChangeDone from "../login/passwordChangeDone";
import PasswordResetDone from "../login/passwordResetDone";
import UserForm from "../pages/userForm";
import Hello from "../pages/hello";
import AppBar from "@material-ui/core/AppBar";
import CssBaseline from "@material-ui/core/CssBaseline";
import Toolbar from "@material-ui/core/Toolbar";

const drawerWidth = config.navBarWidth;

let styles = theme => ({
  root: {
    display: "flex"
  },
  appBar: {
    zIndex: theme.zIndex.drawer + 1
  },
  drawer: {
    width: drawerWidth,
    flexShrink: 0
  },
  drawerPaper: {
    width: drawerWidth
  },
  content: {
    flexGrow: 1,
    height: "100%",
    padding: theme.spacing.unit * 3,
    marginLeft: drawerWidth
  },
  fullHeight: {
    height: "100%",
    minHeight: "100%"
  },
  toolbar: theme.mixins.toolbar
});

class ClippedDrawer extends Component {
  state = {
    user: {}
  };

  componentDidMount() {
    const user = auth.getCurrentUser();
    if (!user) {
    }
    this.setState({ user });
  }

  render() {
    const { classes } = this.props;
    const { user } = this.state;
    return (
      <div className={classes.root} className="heightFull">
        <CssBaseline />
        {user && <NavBar classes={classes} user={user} />}

        <main className={classes.content} style={{ marginLeft: user ? "" : 0 }}>
          <Switch className={classes.fullHeight}>
            <Route path="/login" component={LoginForm} />
            <Route path="/hello" component={Hello} />
            <Route path="/password/reset" component={PasswordResetForm} />
            <Route path="/passwordResetDone" component={PasswordResetDone} />
            <Route path="/passwordChange" component={PasswordChangeForm} />
            <Route path="/passwordChangeDone" component={PasswordChangeDone} />
            <Route path="/registration" component={RegistrationForm} />
            <Route path="/registrationDone" component={RegistrationDone} />
            <ProtectedRoute path="/galleryUpload" component={GalleryUpload} />
            <ProtectedRoute path="/gallery" component={Gallery} />
            <ProtectedRoute path="/filesUpload" component={FilesUpload} />
            <ProtectedRoute path="/files" component={Files} />
            <ProtectedRoute path="/orders" component={Orders} />
            <ProtectedRoute path="/products/:id" component={ProductForm} />
            <ProtectedRoute path="/products" component={Products} />
            <ProtectedRoute path="/users/:id" component={UserForm} />
            <ProtectedRoute path="/users" component={Users} />
            <Redirect from="/" to="/hello" exact />
          </Switch>
        </main>
      </div>
    );
  }
}

export default withStyles(styles)(ClippedDrawer);
