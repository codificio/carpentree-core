import React, { Component } from "react";
import TableData from '../common/tableData';

class Orders extends Component {
  state = {
    columns: [
      { path: "id", label: "Id", align: "r", format: "text" },
      { path: "date", label: "Data", align: "c", format: "date" },
      { path: "state", label: "Stato", align: "l", format: "text" },
      { path: "customer", label: "Cliente", align: "l", format: "text" },
      { path: "cost", label: "Costo", align: "r", format: "currency" },
    ],
    editedOrder: {},
  };


  render() {
    const { columns } = this.state;
    const { history } = this.props;
    
    return (
      <TableData 
        pageTitle= 'Ordini'
        collectionName= 'orders'
        itemLabel= 'ordine'
        columns = { columns }
        history = { history }
      />
    );
  }
}

export default Orders;
