import React from "react";
import TableHeader from "./tableHeader";
import TableBody from "./tableBody";

const Table = ({ columns, sortColumn, popperShows, onSort, data, onEdit, onDelete }) => {
  return (
    <table className="table">
      <TableHeader columns={columns} sortColumn={sortColumn} onSort={onSort} />
      <TableBody
        popperShows={popperShows}
        columns={columns}
        data={data}
        onEdit={onEdit}
        onDelete={onDelete}
      />
    </table>
  );
};

export default Table;
