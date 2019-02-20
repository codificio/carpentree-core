import http from "./httpService";
import { apiUrl } from "../config.json";

const fileEndPoint = apiUrl + "/files";

export async function getFiles() {
  const { data } = await http.get(fileEndPoint);
  const dataCloned = [...data];
  const dataLimited = dataCloned.slice(0, 100);
  return dataLimited;
}

export async function getFile(id) {
  try {
    const { data } = await http.get(fileEndPoint);
    const file = data.find(u => u.id == id);
    return file;
  } catch (error) {}
}

export async function deleteFile(name) {
  try {
    const data = {
      name: name
    };
    await http.delete(fileEndPoint, { data });
  } catch (error) {}
}
