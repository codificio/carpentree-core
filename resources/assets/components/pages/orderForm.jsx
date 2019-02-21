import React from "react";
import Joi from "joi-browser";
import Form from "../common/form";
import { getItem, setItem } from "../../services/collectionServices";

const collectionFather = "users";

class UserForm extends Form {
  state = {
    data: {
      date: "",
      state: "",
      customerId: ""
    },
    errors: {}
  };

  async componentDidMount() {
    if (this.props.match.params.id > 0) {
      const user = await getItem(collectionFather, this.props.match.params.id);
      this.setState({ data: user });
    }
  }

  schema = {
    date: Joi.string()
      .required()
      .label("Data ordine"),
    state: Joi.string()
      .required()
      .label("Stato dell'ordine"),
    customerId: Joi.string()
      .required()
      .label("Cliente")
  };

  handleCancel = () => {
    this.props.history.push("/" + collectionFather);
  };

  doSubmit = async () => {
    try {
      await setItem(collectionFather, this.state.data);
      this.props.history.push("/" + collectionFather);
    } catch (error) {}
  };

  render() {
    const { roles } = this.state;
    return (
      <div className="row c">
        <div className="col-12">
          <h1 className="mb-4 text-secondary">Modifica ordine</h1>
        </div>
        <div className="col-12 bg-white p-5">
          <form onSubmit={this.handleSubmit} className="pb-5">
            <div className="row m-0">
              <div className="col-6">
                <div className="row m-0">
                  <div className="col-12">
                    {this.renderInput("date", "Data")}
                  </div>
                  <div className="col-12">
                    {this.renderInput("state", "Stato")}
                  </div>
                  <div className="col-12">
                    {this.renderInput("customerId", "Cliente")}
                  </div>
                  <div className="col-12">
                    {this.renderSubmitButton("Salva", true, "float-left")}
                    {this.renderCancelButton("Annulla", true, "float-right")}
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    );
  }
}

export default UserForm;
