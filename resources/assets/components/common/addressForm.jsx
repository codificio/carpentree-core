import React, { Component } from "react";
import Form from "./form";

class AddressForm extends Form {
  state = {
    data: {
      referent_name: "",
      company_name: "",
      address: "",
      street_number: "",
      postal_code: "",
      city: ""
    }
  };
  render() {
    const { data } = this.state;
    return (
      <form onSubmit={this.handleSubmit} className="pb-5">
        <div className="row m-0">
          <div className="col-12">
            {this.renderInput("referent_name", "Nome referente")}
          </div>
          <div className="col-12">
            {this.renderInput("company_name", "Presso")}
          </div>
          <div className="col-12">
            {this.renderInput("address", "Indirizzo")}
          </div>
          <div className="col-6">
            {this.renderInput("street_number", "Civico")}
          </div>
          <div className="col-6">{this.renderInput("postal_code", "CAP")}</div>
          <div className="col-12">{this.renderInput("city", "Comune")}</div>
          <div className="col-12 mt-3">
            {this.renderSubmitButton("Salva", true, "float-left")}
            {this.renderCancelButton("Annulla", true, "float-right")}
          </div>
        </div>
      </form>
    );
  }
}

export default AddressForm;
