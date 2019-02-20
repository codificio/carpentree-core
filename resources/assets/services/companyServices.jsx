import http from "./httpService";
import { apiUrl } from "../config.json";

const companyEndpoint = apiUrl + "/companies";

export async function getCompanies() {
  const { data } = await http.get(companyEndpoint);
  return data;
}

export async function getCompany(id) {
  try {
    const { data } = await http.get(companyEndpoint + "/" + id);
    const company = data.find(u => u.id == id);
    return company;
  } catch (error) {}
}

export async function setCompany(data) {
  try {
    await http.post(companyEndpoint + "/" + data.id, { data });
  } catch (error) {}
}

export async function deleteCompany(id) {
  try {
    await http.delete(companyEndpoint + "/" + id);
  } catch (error) {}
}
