import React, { Component } from "react";
import ClippedDrawer from "./components/template/clippedDrawer";
import "./App.css";

//docker-compose up -d nginx mysql workspace

class App extends Component {
  state = {};

  render() {
    return <ClippedDrawer />;
  }
}

export default App;
