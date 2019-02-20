  import React, { Component } from "react";
  import TableData from '../common/tableData';
  
  class Products extends Component {
    state = {
      columns: [
        { path: "id", label: "Id", align: "r", format: "text" },
        { path: "company.name", label: "Marca", align: "l", format: "text" },
        { path: "model", label: "Modello", align: "l", format: "text" },
        { path: "width", label: "Larghezza", align: "r", format: "text" },
        { path: "height", label: "Altezza", align: "r", format: "text" }
      ],
      editedProduct: {},
    };
  
  
    render() {
      const { columns } = this.state;
      const { history } = this.props;
      
      return (
        <TableData 
          pageTitle= 'Prodotti'
          collectionName= 'products'
          itemLabel= 'prodotto'
          columns = { columns }
          history = { history }
        />
      );
    }
  }
  
export default Products;
