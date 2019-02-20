import React, { Component } from "react";
import Pagination from "../common/pagination";
import SearchBox from "../common/searchBox";
import { MsgYesNo } from "../common/dialogs";
import { getItems, deleteItem } from "../../services/collectionServices";
import Table from "../common/table";
import _ from "lodash";
import Typography from "@material-ui/core/Typography";
import { collectionsPageSize } from '../../config.json';
import SearchIcon from '@material-ui/icons/Search';
import Cloud from '@material-ui/icons/Cloud';
import Cached from '@material-ui/icons/Cached';

const msgYesNoData = {
  open: false,
  title: "",
  text: "",
  item: {}
};

class TableData extends Component {
  state = {
    pageTitle: '',
    collectionName: '',
    itemLabel: '',
    collection: {
      name: "???",
      currentPage: 1,
      pageSize: collectionsPageSize,
      sortColumn: { path: "id", order: "asc" },
      totalItems: 0,
      data: []
    },
    pageLoading: true,
    searchQuery: '', 
    columns: [],
    editedRecord: {},
    popperShows: {
      edit: true,
      delete: true
    },
    msgYesNoData,
  };


  async componentDidMount() {
    const { searchQuery, collection } = this.state;
    const { columns, collectionName, pageTitle, itemLabel } = this.props;
    this.setState({ columns, collectionName, pageTitle, itemLabel });
    const filters = {...collection, data:[], searchQuery };
    const collectionFiltered = await getItems(collectionName, filters);
    this.setState({ collection: collectionFiltered, pageLoading: false });
  }

  handleDelete = item => {
    this.setState({
      msgYesNoData: {
        open: true,
        title: "Eliminazione recprd",
        text: "Confermi l'eliminazione del record '" + item.name + "'?",
        item
      }
    });
  };

  handleMsgYesNoClickYes = () => {
    const { collection } = this.state
    const item = this.state.msgYesNoData.item;
    const collectionUpdated = collection.data.filter(i => i.id !== item.id);
    this.setState({ collection: collectionUpdated });
    this.setState({ msgYesNoData });
    deleteItem(item.id);
  };

  handleMsgYesNoClickNo = () => {
    this.setState({ msgYesNoData });
  };

  handleEdit = editedRecord => {
    this.props.history.push('/'+this.state.collectionName+'/' + editedRecord.id);
  };

  handlePageChange = async currentPage => {
    const { collectionName } = this.state;
    const filters = {...this.state.collection, data: [], currentPage};
    const collection = await getItems(collectionName, filters);
    this.setState({ collection });
  };

  handleSort = async sortColumn => {
    const { collectionName } = this.state;
    const filters = {...this.state.collection, data: [], sortColumn};
    const collection = await getItems(collectionName, filters);
    this.setState({ collection });
  };

  handleSearch = async searchQuery => {
    const { collectionName } = this.state;
    const filters = {...this.state.collection, data: [], searchQuery};
    const collection = await getItems(collectionName, filters);
    this.setState({ searchQuery });
    this.setState({ collection });
  };


  handleNew = () => {
    this.props.history.push("/"+this.state.collectionName+"/0");
  };

  render() {
    const { collection, pageTitle, itemLabel, pageLoading } = this.state;
    const { totalItems, pageSize, currentPage, sortColumn } = collection;
    const { popperShows, searchQuery } = this.state;
    const { title, text, open } = this.state.msgYesNoData;

    return (
      <div>
        <MsgYesNo
          title={title}
          text={text}
          open={open}
          onMsgYesNoClickYes={this.handleMsgYesNoClickYes}
          onMsgYesNoClickNo={this.handleMsgYesNoClickNo}
        />
        <div className="col-12 ">
          <h1 className="text-secondary c">{ pageTitle }</h1>
        </div>
        <div className="row bg-white px-1 py-3">
          <div className="col-4">
            <SearchBox value={searchQuery} onChange={this.handleSearch} />
          </div>
          <div className="col-8">
            <Typography variant="body1" gutterBottom align="right">
              {totalItems} record visualizzati.
            </Typography>
          </div>
          {  pageLoading &&
            <div className="col-12 c my-4">
              <Cached className="mr-2"/> Caricamento in corso...
            </div>
          }
          { collection.data.length === 0 && ! pageLoading &&
            <div className="col-12 c my-4 text-danger">
              { searchQuery === '' ? <Cloud className="mr-2"/> : <SearchIcon className="mr-2" />}
              { searchQuery === '' ? 'Nessun record nel database...' : 'Il filtro di ricerca impostato non ha restituito risultati...' }
            </div>
          }
          { collection.data.length > 0 &&
          <div className="col-12">
            <Table
              data={collection.data}
              sortColumn={sortColumn}
              popperShows={popperShows}
              onLike={this.handleLike}
              onDelete={this.handleDelete}
              onEdit={this.handleEdit}
              onSort={this.handleSort}
              columns={this.state.columns}
            />
            {
              totalItems &&
              <Pagination
                itemsCount={totalItems}
                pageSize={pageSize}
                currentPage={currentPage}
                onPageChange={this.handlePageChange}
              />
            }
            
          </div>
          }
        </div>
        <div className="col-12 c">
          <hr />
          <button className="btn btn-secondary" onClick={this.handleNew}>
            { 'Nuovo '+ itemLabel }
          </button>
          <hr />
        </div>
      </div>
    );
  }
}

export default TableData;
