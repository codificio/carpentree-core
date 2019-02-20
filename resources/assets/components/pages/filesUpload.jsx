import React, { Component } from "react";
import Uploader from "../common/uploader";
import { apiUrl } from "../../config.json";

class FilesUpload extends Component {
  state = {};

  handleUploaded = () => {
    this.props.history.push("/files");
  };

  render() {
    return (
      <Uploader
        apiUrl={apiUrl + "/files"}
        filesAccepted=""
        dropzoneClass="dropzone"
        onUpload={this.handleUploaded}
      />
    );
  }
}

export default FilesUpload;
