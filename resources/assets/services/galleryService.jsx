import http from "./httpService";
import { apiUrl } from "../config.json";

const imageEndPoint = apiUrl + "/images";

export async function getImages() {
  const { data } = await http.get(imageEndPoint);
  const dataCloned = [...data];
  const dataLimited = dataCloned.slice(0, 100);
  return dataLimited;
}

export async function getImage(id) {
  try {
    const { data } = await http.get(imageEndPoint);
    const image = data.find(u => u.id == id);
    return image;
  } catch (error) {}
}

export async function deleteImage(name) {
  try {
    const data = {
      name: name
    };
    await http.delete(imageEndPoint, { data });
  } catch (error) {}
}
