import React, { Component } from "react";
import _ from "lodash";
import TablePopper from "./tablePopper";
import { dateTimeToDate, bytesToSize } from "../../utils/functions";

class TableBody extends Component {
  renderCell = (item, column) => {
    const tmpItem = { ...item };
    switch (column.format) {
      case "text":
        break;
      case "date":
        tmpItem[column.path] = dateTimeToDate(tmpItem[column.path]);
        break;
      case "fileSize":
        tmpItem[column.path] = bytesToSize(tmpItem[column.path]);
        break;
      case "currency":
        const curr = tmpItem[column.path];
        if (!isFinite(curr)) {
          curr = 0;
        }
        tmpItem[column.path] = curr.toFixed(2) + " EUR";
        break;
    }
    if (column.content) return column.content(tmpItem);
    return _.get(tmpItem, column.path);
  };

  createKey = (tmpItem, column) => {
    return tmpItem.id + (column.path || column.key);
  };

  render() {
    const { data, columns, onEdit, onDelete } = this.props;

    return (
      <tbody>
        {data.map(item => (
          <tr key={item.id}>
            {columns.map(column => (
              <td key={this.createKey(item, column)} className={column.align}>
                {this.renderCell(item.attributes, column)}
              </td>
            ))}
            <td className="r">
              <TablePopper
                popperShows={this.props.popperShows}
                item={item}
                onEdit={onEdit}
                onDelete={onDelete}
              />
            </td>
          </tr>
        ))}
      </tbody>
    );
  }
}

export default TableBody;
