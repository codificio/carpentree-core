import React from "react";
import Joi from "joi-browser";
import Form from "../common/form";
import AddressForm from "../common/addressForm";
import { getItem, setItem } from "../../services/collectionServices";
import Button from "@material-ui/core/Button";
import Divider from "@material-ui/core/Divider";
import IconMailOutline from "@material-ui/icons/MailOutline";
import { FaPlus } from "react-icons/fa";

const collectionFather = "users";
const roles = [
  {
    id: 1,
    name: "Amministratore"
  },
  {
    id: 2,
    name: "Supervisore"
  },
  {
    id: 3,
    name: "Operatore"
  }
];
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

class UserForm extends Form {
  state = {
    data: {
      first_name: "",
      last_name: "",
      email: "",
      password: "",
      roleId: 0,
      roles: []
    },
    addresses: [],
    addressEdited: -1,
    errors: {},
    roles
  };

  async componentDidMount() {
    if (this.props.match.params.id > 0) {
      const user = await getItem(collectionFather, this.props.match.params.id);
      this.setState({ data: user });
    }
  }

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
      .label("Ruoli")
  };

  handleCancel = () => {
    this.props.history.push("/" + collectionFather);
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
    try {
      await setItem("user", this.state.data);
      this.props.history.push("/" + collectionFather);
    } catch (error) {}
  };

  render() {
    const { roles, data, addresses } = this.state;
    return (
      <div className="row c">
        <div className="col-12">
          <h1 className="mb-4 text-secondary">Modifica utente</h1>
        </div>
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
                    {this.renderPassword("password", "Passsword")}
                  </div>

                  <div className="col-12 mt-3">
                    {this.renderSubmitButton("Salva", true, "float-left")}
                    {this.renderCancelButton("Annulla", true, "float-right")}
                  </div>
                </div>
              </form>
            </div>
            {data.id > 0 ? (
              <div className="col-4 c borderLeftThiny">
                <AddressForm />
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
      </div>
    );
  }
}

export default UserForm;
