import http from "./httpService";
import { apiUrl } from "../config.json";

export async function getItems(collectionName, filters) {
  const userEndpoint = apiUrl + "/" + collectionName;
  const { currentPage, pageSize, searchQuery} = filters;
  const { path, order} = filters.sortColumn;
  let url = userEndpoint + "?currentPage=" + currentPage + "&pageSize=" + pageSize + "&sortColumn=" + path + "&sortOrder=" + order;
  if(searchQuery) { url += "&searchQuery=" + searchQuery; }
  try {
    console.log("getItems url", url);
    const { data } = await http.get(url);
    console.log("getItems data", data);
    return data;  
  } catch (error) {
    console.log("error", error);
  }
  
}

export async function getItem(collectionName, id) {
  const url = apiUrl + "/" + collectionName + "/" + id;
  try {
    const { data } = await http.get(url);
    console.log("Data", data);
    return data;
  } catch (error) {
    console.log("error", error);
  }
}

export async function setItem(collectionName, data) {
  const userEndpoint = apiUrl + "/" + collectionName;
  try {
    await http.post(userEndpoint + "/" + data.id, { data });
  } catch (error) {
    console.log("error", error);
  }
}

export async function deleteItem(collectionName, id) {
  const userEndpoint = apiUrl + "/" + collectionName;
  try {
    await http.delete(userEndpoint + "/" + id);
  } catch (error) {
    console.log("error", error);
  }
}
