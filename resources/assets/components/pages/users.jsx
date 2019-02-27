import React, { Component } from "react";
import TableData from "../common/tableData";

class Users extends Component {
  state = {
    columns: [
      { path: "first_name", label: "Nome", align: "l", format: "text" },
      { path: "last_name", label: "Cognome", align: "l", format: "text" },
      { path: "email", label: "Email", align: "l", format: "text" }
    ],
    editedUser: {}
  };

  render() {
    const { columns } = this.state;
    const { history } = this.props;

    return (
      <TableData
        pageTitle="Utenti"
        collectionName="users"
        path="user"
        itemLabel="utente"
        columns={columns}
        history={history}
      />
    );
  }
}

export default Users;
