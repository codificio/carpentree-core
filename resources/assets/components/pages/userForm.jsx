import React from "react";
import Joi from "joi-browser";
import Form from "../common/form";
import AddressForm from "../common/addressForm";
import { getItems, getItem, setItem } from "../../services/collectionServices";
import Button from "@material-ui/core/Button";
import Divider from "@material-ui/core/Divider";
import IconMailOutline from "@material-ui/icons/MailOutline";
import { FaPlus } from "react-icons/fa";
import { getMetaValue } from "../../utils/functions";
import { ToastContainer, toast } from "react-toastify";
import SpinnerLoading from "../common/spinnerLoading";

const collectionFatherName = "users";

const emptyAddress = {
  company_name: "",
  referent_name: "",
  address: "",
  street_number: "",
  postal_code: "",
  city: "",
  province: "",
  region: "",
  country: ""
};

const emptyUser = {
  id: 0,
  locale: "",
  first_name: "",
  last_name: "",
  email: "",
  password: "",
  lucky_number: "",
  roles: []
};

class UserForm extends Form {
  state = {
    data: { ...emptyUser },
    addresses: [],
    addressEdited: -1,
    errors: {},
    roles: [],
    loading: true
  };

  schema = {
    first_name: Joi.string()
      .label("Nome")
      .required()
      .label("Nome"),
    last_name: Joi.string()
      .required()
      .label("Cognome"),
    email: Joi.string()
      .required()
      .email()
      .label("Email"),
    password: Joi.string()
      .required()
      .label("Password"),
    roles: Joi.array()
      .required()
      .label("Ruoli"),
    lucky_number: Joi.number().label("Numero fortunato")
  };

  async componentDidMount() {
    // Recupero i ruoli
    const data = await getItems("roles");
    const roles = data.data;
    this.setState({ roles });

    // Recupero l'utente
    if (this.props.match.params.id > 0) {
      const user = await getItem(
        collectionFatherName,
        this.props.match.params.id
      );
      let data = user.data.attributes;
      data.id = user.data.id;
      data.locale = user.data.locale;
      data.lucky_number = getMetaValue(user.data, "lucky_number");

      data.roles = [];
      for (let i = 0; i < user.data.relationships.roles.data.length; i++) {
        data.roles.push(user.data.relationships.roles.data[i].id);
      }

      this.setState({ data, loading: false });
    }
  }

  handleCancel = () => {
    this.props.history.push("/" + collectionFatherName);
  };

  handleGenerateNewPassword = () => {
    console.log("generate new password");
  };

  handleAddNewAddress = () => {
    const addresses = this.state.addresses;
    addresses.push(emptyAddress);
    const addressEdited = addresses.length;
    this.setState({ addresses, addressEdited });
  };

  doSubmit = async () => {
    this.setState({ loading: true });
    const { data } = this.state;
    let dataToSend = {};
    dataToSend.id = data.id;
    dataToSend.locale = data.locale;

    // Attributes
    dataToSend.attributes = { ...data };

    // Relationships
    dataToSend.relationships = {};

    // Meta
    dataToSend.relationships.meta = {};
    dataToSend.relationships.meta.data = [];
    /*dataToSend.relationships.meta.data.push({
        attributes: { key: "lucky_number", value: data.lucky_number }
      });*/

    // Roles
    dataToSend.relationships.roles = {};
    dataToSend.relationships.roles.data = [];
    for (let i = 0; i < data.roles.length; i++) {
      dataToSend.relationships.roles.data.push({ id: data.roles[i] });
    }

    try {
      await setItem(collectionFatherName, dataToSend);
      let toastMessage = "Profilo aggiornato con successo";
      if (data.id == 0) {
        toastMessage = "Utente creato con successo";
      }
      toast.success(toastMessage, {
        position: toast.POSITION.TOP_CENTER
      });
      this.setState({ loading: false });
      //this.props.history.push("/" + collectionFatherName);
    } catch (error) {
      console.log("e", error.response.status);
      toast.error(
        "Errore salvataggio dati. Verifica i dati inseriti, riprova o contatta l'assistenza",
        { position: toast.POSITION.TOP_CENTER }
      );
    }
  };

  render() {
    const { roles, data, addresses, loading } = this.state;
    return (
      <div className="row c">
        <div className="col-12">
          <ToastContainer />
          <h1 className="mb-4 text-secondary">Modifica profilo utente</h1>
        </div>
        {loading && <SpinnerLoading />}
        {!loading && (
          <div className="col-12 bg-white p-5">
            <div className="row m-0">
              <div className="col-4 l">
                <form onSubmit={this.handleSubmit} className="pb-5">
                  <div className="row m-0">
                    <div className="col-12">
                      {this.renderInput("email", "Email")}
                    </div>
                    <div className="col-12">
                      {this.renderInput("first_name", "* Nome")}
                    </div>
                    <div className="col-12">
                      {this.renderInput("last_name", "Cognome")}
                    </div>
                    <div className="col-12">
                      {this.renderSelect("roles", "Ruoli", roles, "multiple")}
                    </div>
                    <div className="col-12">
                      {this.renderInput("lucky_number", "Numero fortunato")}
                    </div>
                    {data.id == 0 && (
                      <div className="col-12">
                        {this.renderPassword("password", "Passsword")}
                      </div>
                    )}
                    <div className="col-12 mt-5">
                      {this.renderSubmitButton(
                        "Salva modifiche al profilo",
                        true,
                        "float-left"
                      )}
                      {/*this.renderCancelButton("Annulla", true, "float-right")*/}
                    </div>
                  </div>
                </form>
              </div>
              {data.id > 0 ? (
                <div className="col-4 c borderLeftThiny">
                  {/*}<AddressForm />*/}
                  {addresses.length > 0 ? (
                    <Divider variant="middle" class="mb-5" />
                  ) : (
                    ""
                  )}
                  <Button
                    variant="outlined"
                    color="default"
                    className="ml-3"
                    onClick={this.handleAddNewAddress}
                  >
                    <Divider />
                    <FaPlus className="mr-2" /> Aggiungi un nuovo indirizzo
                  </Button>
                </div>
              ) : (
                ""
              )}
              {data.id > 0 ? (
                <div className="col-4 l borderLeftThiny">
                  <Button
                    variant="outlined"
                    color="default"
                    className="ml-3"
                    onClick={this.handleGenerateNewPassword}
                  >
                    <IconMailOutline className="mr-2" /> Invita l'utente a
                    definire una nuova passowrd
                  </Button>
                </div>
              ) : (
                ""
              )}
            </div>
          </div>
        )}
      </div>
    );
  }
}

export default UserForm;
