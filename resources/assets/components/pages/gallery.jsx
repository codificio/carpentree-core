import React, { Component } from "react";
import _ from "lodash";
import PropTypes from "prop-types";
import { withStyles } from "@material-ui/core/styles";
import { getImages, deleteImage } from "../../services/galleryService";
import { MsgYesNo } from "../common/dialogs";
import Pagination from "../common/pagination";
import Table from "../common/table";
import { paginate } from "../../utils/paginate";
import Typography from "@material-ui/core/Typography";
import GridList from "@material-ui/core/GridList";
import GridListTile from "@material-ui/core/GridListTile";
import GridListTileBar from "@material-ui/core/GridListTileBar";
import IconButton from "@material-ui/core/IconButton";
import StarBorderIcon from "@material-ui/icons/StarBorder";

const styles = theme => ({
  root: {
    display: "flex",
    flexWrap: "wrap",
    justifyContent: "space-around",
    overflow: "hidden",
    backgroundColor: theme.palette.background.paper
  },
  gridList: {
    width: "100%",

    // Promote the list into his own layer on Chrome. This cost memory but helps keeping high FPS.
    transform: "translateZ(0)"
  },
  titleBar: {
    background:
      "linear-gradient(to bottom, rgba(0,0,0,0.7) 0%, " +
      "rgba(0,0,0,0.3) 70%, rgba(0,0,0,0) 100%)"
  },
  icon: {
    color: "white"
  }
});

const msgYesNoData = {
  open: false,
  title: "",
  text: "",
  item: {}
};

class Gallery extends Component {
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
    editedItem: {},
    popperShows: {
      edit: false,
      delete: true
    },
    msgYesNoData
  };

  async componentDidMount() {
    const data = await getImages();
    const loadingStatus =
      data.length === 0 ? "Non ci sono immagini nel server" : "";
    this.setState({ loadingStatus });
    this.setState({ files: data });
  }

  handleDelete = file => {
    this.setState({
      msgYesNoData: {
        open: true,
        title: "Eliminazione immagine",
        text: "Confermi l'eliminazione dell'immagine '" + file.name + "'?",
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
    this.props.history.push("/galleryUpload");
  };

  handleMsgYesNoClickYes = () => {
    const file = this.state.msgYesNoData.item;
    const files = this.state.files.filter(m => m.id !== file.id);
    this.setState({ files });
    this.setState({ msgYesNoData });
    deleteImage(file.name);
  };

  getFileFullName = fileName => {
    return "https://www.skeinet.com/carpentree/images/" + fileName;
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
    const { classes } = this.props;
    const { length: count } = this.state.files;
    const {
      pageSize,
      currentPage,
      sortColumn,
      loadingStatus,
      popperShows,
      files
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
          <h1 className="text-secondary c">Galleria</h1>
        </div>
        <div className="row bg-white px-1 py-3">
          <div className="col-12">
            <div className={classes.root}>
              <GridList cellHeight={210} className={classes.gridList} cols={6}>
                {files.map(file => (
                  <GridListTile key={file.id}>
                    <img
                      src={this.getFileFullName(file.name)}
                      alt={file.name}
                    />
                  </GridListTile>
                ))}
              </GridList>
            </div>
          </div>
          <div className="col-12 c">
            <hr />
            <button
              className="btn btn-secondary"
              onClick={this.handleUploadFileClick}
            >
              Carica Immagine
            </button>
            <hr />
          </div>
        </div>
      </div>
    );
  }
}

Gallery.propTypes = {
  classes: PropTypes.object.isRequired
};

export default withStyles(styles)(Gallery);
