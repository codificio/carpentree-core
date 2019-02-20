import React, { Fragment } from "react";
import Http from "../../services/httpService";
import { getProgressCompleted } from "../../utils/functions";
import Table from "./table";
import Form from "./form";
import Dropzone from "./dropzone";
import Joi from "joi-browser";
import LinearProgress from "@material-ui/core/LinearProgress";

class Uploader extends Form {
  state = {
    sortColumn: { path: "name", order: "asc" },
    data: { id: 0, name: "", type: "", size: "" },
    errors: {},
    files: [],
    filesToShow: [],
    columns: [
      { path: "name", label: "Nome", align: "l", format: "text" },
      { path: "type", label: "Tipo", align: "c", format: "text" },
      { path: "size", label: "Dimensione", align: "r", format: "fileSize" }
    ],
    progressLoaded: 0,
    progressTotal: 0,
    popperShows: {
      edit: false,
      delete: true
    }
  };

  schema = {
    id: Joi.string(),
    name: Joi.number().label("Nome"),
    type: Joi.string().label("Tipo"),
    size: Joi.string().label("Dimensione")
  };

  handleDelete = file => {
    const files = this.state.files.filter(m => m.id !== file.id);
    this.setState({ files });
  };

  handleOnDrop = (acceptedFiles, rejectedFiles) => {
    const filesToShow = [];
    let i = -1;
    acceptedFiles.map((item, key) => {
      i += 1;
      const file = {};
      file.id = i;
      file.name = item.name;
      file.type = item.type;
      file.size = item.size;
      filesToShow.push(file);
    });

    this.setState({ files: acceptedFiles });
    this.setState({ filesToShow });
  };

  handleUploadFiles = async () => {
    const fd = new FormData();
    const files = [...this.state.files];
    files.map(file => {
      fd.append(file.name, file, file.name);
    });
    await Http.post(this.props.apiUrl, fd, {
      onUploadProgress: progressEvent => {
        this.setState({ progressLoaded: progressEvent.loaded });
        this.setState({ progressTotal: progressEvent.total });
      }
    });
    this.props.onUpload();
  };

  render() {
    const {
      files,
      filesToShow,
      sortColumn,
      progressLoaded,
      progressTotal,
      popperShows
    } = this.state;
    const { filesAccepted, dropzoneClass } = this.props;

    return (
      <div className="row">
        <div className="col-12">
          <h1 className="text-secondary">Caricamento file</h1>
          <Dropzone
            filesAccepted={filesAccepted}
            dropzoneClass={dropzoneClass}
            textOut="Clicca o trascina i file in questa zona"
            textOver="Trascina qui..."
            multiple={true}
            onDrop={this.handleOnDrop}
          />
          {files.length > 0 && (
            <Fragment>
              <div className="row">
                <div className="col-12">
                  <Table
                    data={filesToShow}
                    sortColumn={sortColumn}
                    columns={this.state.columns}
                    onDelete={this.handleDelete}
                    popperShows={popperShows}
                  />
                </div>
                <div className="col-12 c">
                  <button
                    className="btn btn-danger m-4"
                    onClick={this.handleUploadFiles}
                    disabled={progressLoaded > 0}
                  >
                    Upload files
                  </button>
                </div>
                <div className="col-12">
                  {progressLoaded > 0 && (
                    <LinearProgress
                      color="secondary"
                      variant="determinate"
                      value={getProgressCompleted(
                        progressLoaded,
                        progressTotal
                      )}
                    />
                  )}
                </div>
              </div>
            </Fragment>
          )}
        </div>
      </div>
    );
  }
}

export default Uploader;
