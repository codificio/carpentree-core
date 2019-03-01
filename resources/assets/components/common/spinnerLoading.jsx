import React, { Component } from "react";
import { PulseLoader } from "react-spinners";

class SpinnerLoading extends Component {
  state = {};
  render() {
    return (
      <div className="col-12 c my-4">
        <PulseLoader
          sizeUnit={"px"}
          size={15}
          color={"#123abc"}
          loading={true}
        />
      </div>
    );
  }
}

export default SpinnerLoading;
