import React, { Component } from "react";
import Uploader from "../common/uploader";
import { apiUrl } from "../../config.json";

class GalleryUpload extends Component {
  state = {};

  handleUploaded = () => {
    this.props.history.push("/gallery");
  };

  render() {
    return (
      <Uploader
        apiUrl={apiUrl + "/images"}
        filesAccepted="image/jpeg, image/png"
        dropzoneClass="dropzone"
        onUpload={this.handleUploaded}
      />
    );
  }
}

export default GalleryUpload;
