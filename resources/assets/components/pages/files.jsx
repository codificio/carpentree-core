import React, { Component } from "react";
import { getFiles, deleteFile } from "../../services/filesService";
import { MsgYesNo } from "../common/dialogs";
import Pagination from "../common/pagination";
import Table from "../common/table";
import { paginate } from "../../utils/paginate";
import Typography from "@material-ui/core/Typography";
import _ from "lodash";

const msgYesNoData = {
  open: false,
  title: "",
  text: "",
  item: {}
};

class Files extends Component {
  state = {
    files: [],
    currentPage: 1,
    pageSize: 10,
    sortColumn: { path: "name", order: "asc" },
    columns: [
      { path: "name", label: "Nome", align: "l", format: "text" },
      { path: "type", label: "Tipo", align: "l", format: "text" },
      { path: "size", label: "Dimensione", align: "r", format: "fileSize" }
    ],
    loadingStatus: "Caricamento in corso...",
    editedDocument: {},
    popperShows: {
      edit: false,
      delete: true
    },
    msgYesNoData
  };

  async componentDidMount() {
    const data = await getFiles();
    const loadingStatus =
      data.length === 0 ? "Non ci sono file nel server" : "";
    this.setState({ loadingStatus });
    this.setState({ files: data });
  }

  handleEdit = editedDocument => {
    this.setState({ editedDocument });
    this.props.history.push(`/files/` + editedDocument.id);
  };

  handleDelete = file => {
    this.setState({
      msgYesNoData: {
        open: true,
        title: "Eliminazione file",
        text: "Confermi l'eliminazione del file '" + file.name + "'?",
        item: file
      }
    });
  };

  handlePageChange = page => {
    this.setState({ currentPage: page });
  };

  handleSort = sortColumn => {
    this.setState({ sortColumn });
  };

  handleUploadFileClick = () => {
    this.props.history.push("/filesUpload");
  };

  handleMsgYesNoClickYes = () => {
    const file = this.state.msgYesNoData.item;
    const files = this.state.files.filter(m => m.id !== file.id);
    this.setState({ files });
    this.setState({ msgYesNoData });
    deleteFile(file.name);
  };

  handleMsgYesNoClickNo = () => {
    this.setState({ msgYesNoData });
  };

  getPagedData = () => {
    const { pageSize, currentPage, sortColumn, files: allFiles } = this.state;
    const filtered = allFiles;
    const sorted = _.orderBy(
      filtered,
      [filtered => filtered.name.toUpperCase()],
      [sortColumn.order]
    );
    const Documents = paginate(sorted, currentPage, pageSize);
    return { totalCount: filtered.length, data: Documents };
  };

  render() {
    const { length: count } = this.state.files;
    const {
      pageSize,
      currentPage,
      sortColumn,
      loadingStatus,
      popperShows
    } = this.state;
    const { title, text, open } = this.state.msgYesNoData;

    if (count === 0) return <p>{loadingStatus}</p>;

    const { totalCount, data: Documents } = this.getPagedData();

    return (
      <div>
        <MsgYesNo
          title={title}
          text={text}
          open={open}
          onMsgYesNoClickYes={this.handleMsgYesNoClickYes}
          onMsgYesNoClickNo={this.handleMsgYesNoClickNo}
        />
        <div className="col-12">
          <h1 className="text-secondary c">Gestione File</h1>
        </div>
        <div className="row bg-white px-1 py-3">
          <div className="col-12">
            <Typography variant="body1" gutterBottom align="right">
              {totalCount} record visualizzati.
            </Typography>
            <Table
              data={Documents}
              sortColumn={sortColumn}
              popperShows={popperShows}
              onLike={this.handleLike}
              onDelete={this.handleDelete}
              onEdit={this.handleEdit}
              onSort={this.handleSort}
              columns={this.state.columns}
            />
            <Pagination
              justify="space-between"
              itemsCount={totalCount}
              pageSize={pageSize}
              currentPage={currentPage}
              onPageChange={this.handlePageChange}
            />
          </div>
          <div className="col-12 c">
            <hr />
            <button
              className="btn btn-secondary"
              onClick={this.handleUploadFileClick}
            >
              Carica File
            </button>
            <hr />
          </div>
        </div>
      </div>
    );
  }
}

export default Files;
