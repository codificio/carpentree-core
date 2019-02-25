import React, { Component } from "react";
import { PulseLoader } from "react-spinners";

class SpinnerLoading extends Component {
  state = {};
  render() {
    return (
      <PulseLoader sizeUnit={"px"} size={15} color={"#123abc"} loading={true} />
    );
  }
}

export default SpinnerLoading;
