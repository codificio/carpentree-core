import React, { Component } from "react";
import ReactDropzone from "react-dropzone";
import CreateNewFolderIcom from "@material-ui/icons/CreateNewFolder";
import { FaPlus } from "react-icons/fa";
import classNames from "classnames";

class Dropzone extends Component {
  state = {};
  render() {
    const {
      filesAccepted,
      textOut,
      textOver,
      onDrop,
      multiple,
      dropzoneClass
    } = this.props;

    return (
      <ReactDropzone accept={filesAccepted} onDrop={onDrop} multiple={multiple}>
        {({ getRootProps, getInputProps, isDragActive }) => {
          return (
            <div
              {...getRootProps()}
              className={classNames(dropzoneClass, {
                "dropzone--isActive": isDragActive
              })}
            >
              <input {...getInputProps()} />
              {isDragActive ? (
                <span style={{ color: "darkGrey" }}>{textOver}</span>
              ) : (
                <span style={{ color: "darkGrey" }}>
                  <CreateNewFolderIcom className="mt-3" />
                  <p>{textOut}</p>
                </span>
              )}
            </div>
          );
        }}
      </ReactDropzone>
    );
  }
}

export default Dropzone;
