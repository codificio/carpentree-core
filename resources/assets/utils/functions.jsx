import Moment from "moment";
Moment.locale("it");

export function bytesToSize(bytes) {
  var sizes = ["Bytes", "KB", "MB", "GB", "TB"];
  if (bytes == 0) return "0 Byte";
  var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
  return Math.round(bytes / Math.pow(1024, i), 2) + " " + sizes[i];
}

export function getProgressCompleted(loaded, total) {
  return Math.round((loaded / total) * 100);
}

export function dateTimeToDate(originalDate) {
  let date = new Date(originalDate);
  date = Moment(date).format("DD MMM Y");
  if (date == "Invalid date") {
    date = "";
  }
  return date;
}

export function httpHeaders() {
  var token = localStorage.getItem("token");
  var config = {
    headers: { Authorization: "Bearer " + token }
  };
  return config;
}
