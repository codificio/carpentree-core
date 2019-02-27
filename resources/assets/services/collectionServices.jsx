import http from "./httpService";
import { apiUrl, locale } from "../config.json";
import { httpHeaders } from "../utils/functions";

const apiUrlAdmin = apiUrl + "/api/admin/";

export async function getItems(collectionName, filters) {
  const userEndpoint = apiUrlAdmin + collectionName;
  const { currentPage, pageSize, searchQuery } = filters;
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
  try {
    const { data } = await http.get(url, httpHeaders());
    data.sortColumn = filters.sortColumn;
    console.log("data", data);
    return data;
  } catch (error) {}
}

export async function setItem(path, formData) {
  const userEndpoint = apiUrlAdmin + path;
  let data = {};
  data.attributes = formData;
  data.relationshps = [];
  try {
    const url = userEndpoint;
    if (formData.id) {
      url += "/" + formData.id;
    }
    await http.post(url, data, httpHeaders());
  } catch (error) {
    console.log("error", error);
  }
}

export async function getItem(path, id) {
  const userEndpoint = apiUrlAdmin + path;
  try {
    const { data } = await http.get(userEndpoint + "/" + id, httpHeaders());
    console.log("data", data);
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
