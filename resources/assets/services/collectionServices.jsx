import http from "./httpService";
import { apiUrl, locale } from "../config.json";
import { httpHeaders } from "../utils/functions";

const apiUrlAdmin = apiUrl + "/api/admin/";

const emptyFilters = {
  currentPage: 1,
  sortColumn: { path: "id", order: "asc" }
};

export async function getItems(collectionName, filters) {
  const userEndpoint = apiUrlAdmin + collectionName;

  if (!filters) {
    filters = { ...emptyFilters };
  }
  const { currentPage, searchQuery } = filters;
  const { path, order } = filters.sortColumn;
  let sort = path;
  if (order == "desc") {
    sort = "-" + sort;
  }
  let url =
    userEndpoint +
    "?page=" +
    currentPage +
    "&sort=" +
    sort +
    "&locale=" +
    locale;
  if (searchQuery) {
    url += "&filter[query]=" + searchQuery;
  }
  const { data } = await http.get(url, httpHeaders());
  data.sortColumn = filters.sortColumn;
  return data;
}

export async function setItem(path, data) {
  const userEndpoint = apiUrlAdmin + path;
  if (data.id > 0) {
    await http.patch(userEndpoint, data, httpHeaders());
  } else {
    await http.post(userEndpoint, data, httpHeaders());
  }
}

export async function getItem(path, id) {
  const userEndpoint = apiUrlAdmin + path;
  try {
    const { data } = await http.get(userEndpoint + "/" + id, httpHeaders());
    console.log("getItem - data", data);
    return data;
  } catch (error) {
    console.log("error", error);
  }
}

export async function deleteItem(path, id) {
  const userEndpoint = apiUrlAdmin + path;
  try {
    const { data } = await http.delete(userEndpoint + "/" + id, httpHeaders());
    console.log("Data", data);
  } catch (error) {
    console.log("error", error);
  }
}
