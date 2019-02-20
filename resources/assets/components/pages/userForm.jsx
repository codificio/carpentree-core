import React from "react";
import Joi from "joi-browser";
import Form from "../common/form";
import { getItem, setItem } from "../../services/collectionServices";

const collectionFather= 'users';

class UserForm extends Form {
  state = {
    data: { 
      first_name: "", 
      last_name: "", 
      email: "" ,
      roleId: 0,
      roles: []
    },
    errors: {},
    
    roles: [
      {
        id: 1,
        name: 'Amministratore'
      },
      {
        id: 2,
        name: 'Supervisore'
      },
      {
        id: 3,
        name: 'Operatore'
      },
    ]
  };

  async componentDidMount() {
    if (this.props.match.params.id > 0) {
      const user = await getItem(collectionFather, this.props.match.params.id);
      this.setState({ data: user });
    }
  }

  schema = {
    first_name: Joi.string().label("Nome")
      .required()
      .label("Nome"),
    last_name: Joi.string()
      .required()
      .label("Cognome"),  
    email: Joi.string()
      .required()
      .email()
      .label("Email"),
    roles: Joi.array()
      .required()
      .label("Ruoli")  
  };

  handleCancel = () => {
    this.props.history.push("/"+collectionFather);
  };

  doSubmit = async () => {
    try {
      await setItem(collectionFather, this.state.data);
      this.props.history.push("/"+collectionFather);
    } catch (error) {}
  };

  render() {
    const { roles } = this.state;
    return (
      <div className="row c">
        <div className="col-12">
          <h1 className="mb-4 text-secondary">Modifica utente</h1>
        </div>
        <div className="col-12 bg-white p-5">
          <form onSubmit={this.handleSubmit} className="pb-5">
           <div className="row m-0">
              <div className="col-6">
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
                    {this.renderButton("Salva", true, "float-left")}
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
