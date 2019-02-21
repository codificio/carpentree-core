import React from "react";
import Http from "../../services/httpService";
import { apiUrl, noImageUrl } from "../../config.json";
import Joi from "joi-browser";
import Form from "../common/form";
import Dropzone from "../common/dropzone";
import { getProgressCompleted } from "../../utils/functions";
import { getItem, setItem } from "../../services/collectionServices";
import { getCompanies } from "../../services/companyServices";
import HTML5Backend from "react-dnd-html5-backend";
import { DragDropContext } from "react-dnd";
import ThumbnailDraggable from "../common/thumbnailDraggable";
import LinearProgress from "@material-ui/core/LinearProgress";
import { FaTrashAlt, FaChevronLeft, FaBookmark } from "react-icons/fa";
import Cached from "@material-ui/icons/Cached";
const update = require("immutability-helper");

const collectionFather = "products";

const emptyProduct = {
  id: 0,
  companyId: 0,
  media: [],
  model: "",
  width: "",
  height: "",
  depth: "",
  color: "",
  price: "",
  expeditionMode: ""
};

class ProductForm extends Form {
  state = {
    data: emptyProduct,
    errors: {},
    companies: [],
    imageForeground: noImageUrl,
    imageForeground_index: 0,
    progressLoaded: 0,
    progressTotal: 0,
    loading: true
  };

  async productLoad(productId) {
    const product = await getItem(collectionFather, productId);
    this.setState({ data: product, loading: false });
    if (product.media && product.media.length > 0) {
      const imageForeground = product.media[product.imageForeground];
      this.setState({ imageForeground });
    }
  }

  async componentDidMount() {
    const companies = [...(await getCompanies()).data];
    this.setState({ companies });
    const productId = this.props.match.params.id;
    if (productId > 0) {
      this.productLoad(productId);
    }
  }

  schema = {
    id: Joi.string(),
    companyId: Joi.number().label("Marca"),
    model: Joi.string()
      .required()
      .label("Modello"),
    width: Joi.number()
      .required()
      .label("Larghezza"),
    height: Joi.number()
      .required()
      .label("Altezza"),
    depth: Joi.number()
      .required()
      .label("Profondità"),
    color: Joi.string()
      .required()
      .label("Colore"),
    price: Joi.number()
      .required()
      .label("Prezzo"),
    expeditionMode: Joi.string()
      .required()
      .label("Spedizione")
  };

  handleCancel = () => {
    this.props.history.push("/products");
  };

  handleChangeimageForeground = (imageForeground, imageForeground_index) => {
    this.setState({ imageForeground });
    this.setState({ imageForeground_index });
  };

  handleOnDrop = async (acceptedFiles, rejectedFiles) => {
    const { data } = this.state;
    const fd = new FormData();
    acceptedFiles.map(file => {
      fd.append(file.name, file, file.name);
    });
    await Http.post(apiUrl + "/product/" + data.id + "/images/add", fd, {
      onUploadProgress: progressEvent => {
        this.setState({ progressLoaded: progressEvent.loaded });
        this.setState({ progressTotal: progressEvent.total });
      }
    });
    this.productLoad(data.id);
  };

  handleNewImageForeground = async () => {
    const { data, imageForeground_index } = this.state;
    await Http.post(
      apiUrl +
        "/product/" +
        data.id +
        "/images/foreground/" +
        imageForeground_index
    );
    this.setState({ imageForeground_index: 0 });
    const productId = this.props.match.params.id;
    this.productLoad(productId);
  };

  handleRemoveImage = async () => {
    const { data, imageForeground_index } = this.state;
    await Http.post(
      apiUrl + "/product/" + data.id + "/images/delete/" + imageForeground_index
    );
    this.setState({ imageForeground_index: 0 });
    const productId = this.props.match.params.id;
    this.productLoad(productId);
  };

  handleDropThumbnail = async (dragIndex, hoverIndex) => {
    const { data } = this.state;
    await Http.post(
      apiUrl +
        "/product/" +
        data.id +
        "/images/exchange/" +
        dragIndex +
        "|" +
        hoverIndex
    );

    const { media } = this.state.data;
    const dragMedia = media[dragIndex];

    this.setState(
      update(this.state, {
        data: {
          media: {
            $splice: [[dragIndex, 1], [hoverIndex, 0, dragMedia]]
          }
        }
      })
    );
  };

  doSubmit = () => {
    try {
      setItem(collectionFather, this.state.data).then();
      this.props.history.push("/products");
    } catch (error) {}
  };

  render() {
    const {
      companies,
      data,
      imageForeground,
      progressLoaded,
      progressTotal,
      imageForeground_index,
      loading
    } = this.state;

    return (
      <div className="row c">
        <div className="col-12">
          <h1 className="mb-4 text-secondary">Modifica prodotto</h1>
        </div>
        {loading && (
          <div className="col-12 c my-4">
            <Cached className="mr-2" /> Caricamento in corso...
          </div>
        )}
        {!loading && (
          <div className="col-12 bg-white p-5">
            <div className="row">
              <div className="col-md-12 col-lg-5">
                <div className="row">
                  <div className="col-sm-12 col-md-2 p-0 ">
                    <div className="row justify-content-center">
                      {data.media &&
                        data.media.map((img, img_index) => (
                          <ThumbnailDraggable
                            key={img}
                            img={img}
                            imageForeground={imageForeground}
                            img_index={img_index}
                            onClick={() =>
                              this.handleChangeimageForeground(img, img_index)
                            }
                            dropThumbnail={this.handleDropThumbnail}
                          />
                        ))}
                    </div>
                    {(progressLoaded == 0 ||
                      progressLoaded === progressTotal) &&
                      data.id > 0 && (
                        <div className="row px-3 justify-content-center">
                          <Dropzone
                            filesAccepted="image/jpeg, image/png"
                            dropzoneClass="dropzoneMinimized"
                            textOut=""
                            textOver=""
                            multiple={true}
                            onDrop={this.handleOnDrop}
                          />
                        </div>
                      )}
                    {progressLoaded > 0 && progressLoaded < progressTotal && (
                      <div className="mt-5 px-2 justify-content-center">
                        <LinearProgress
                          color="secondary"
                          variant="determinate"
                          value={getProgressCompleted(
                            progressLoaded,
                            progressTotal
                          )}
                        />
                      </div>
                    )}
                  </div>
                  <div className="col-sm-12 col-md-10">
                    <div className="squareBox boxWithBorder">
                      <div>
                        {imageForeground !== "" &&
                          imageForeground !== noImageUrl && (
                            <div
                              style={{
                                position: "absolute",
                                zIndex: "100",
                                color: "#fff",
                                top: "8px",
                                right: "10px",
                                padding: "11px 16px",
                                background: "rgba(200,200,200,0.5)"
                              }}
                              className="c-pointer thumbnailSpanButton"
                            >
                              <FaTrashAlt onClick={this.handleRemoveImage} />
                            </div>
                          )}
                        {imageForeground !== "" &&
                          imageForeground !== noImageUrl && (
                            <div
                              style={{
                                position: "absolute",
                                zIndex: "100",
                                color: "#fff",
                                top: "8px",
                                left: "10px",
                                padding: "11px 16px",
                                background: "rgba(200,200,200,0.5)"
                              }}
                              className="c-pointer thumbnailSpanButton"
                              onClick={this.handleNewImageForeground}
                            >
                              {imageForeground_index == 0 ? (
                                <FaBookmark className="text-primary" />
                              ) : (
                                <FaBookmark />
                              )}
                            </div>
                          )}
                        <img
                          src={imageForeground}
                          className="c-pointer zoomSpanButton squareBoxContent imageFillDiv "
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div className="col-md-12 col-lg-7">
                <form onSubmit={this.handleSubmit} className="pb-5">
                  <div className="row m-0">
                    <div className="col-6">
                      {this.renderSelect("companyId", "Marca", companies)}
                    </div>
                    <div className="col-6">
                      {this.renderInput("color", "Colore")}
                    </div>
                    <div className="col-12">
                      {this.renderInput("model", "Modello")}
                    </div>
                    <div className="col-3">
                      {this.renderInput("width", "Larghezza")}
                    </div>
                    <div className="col-3">
                      {this.renderInput("height", "Altezza")}
                    </div>
                    <div className="col-3">
                      {this.renderInput("depth", "Profondità")}
                    </div>
                    <div className="col-3">
                      {this.renderInput("price", "Prezzo")}
                    </div>
                    <div className="col-12">
                      {this.renderInput("expeditionMode", "Spedizione")}
                    </div>
                    <div className="col-12">
                      {this.renderSubmitButton("Salva", true, "float-left")}
                      {this.renderCancelButton("Annulla", true, "float-right")}
                    </div>
                    {data.id === 0 && (
                      <div className="col-12 pt-5 l text-danger">
                        <FaChevronLeft /> Per caricare immagini è necessario
                        inserire prima i dati del prodotto a cliccare su{" "}
                        <strong>Salva</strong>
                      </div>
                    )}
                  </div>
                </form>
              </div>
            </div>
          </div>
        )}
      </div>
    );
  }
}

export default DragDropContext(HTML5Backend)(ProductForm);
