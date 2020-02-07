import React, { useState } from "react";
import {  withRouter } from "react-router-dom";
import {
  FormText,
  Form
} from "react-bootstrap";
import LoaderButton from "../components/LoaderButton";
import { useFormFields } from "../libs/hooksLib";
import "./Signup.css";
import { toastr } from 'react-redux-toastr';


function Signup(props) {
  const [fields, handleFieldChange] = useFormFields({
    name: "",
    practice: "",
    npi: "",
    email: "",
    password: "",
    confirmPassword: "",
    confirmationCode: ""
  });
  const [newUser, setNewUser] = useState(null);
  const [isLoading, setIsLoading] = useState(false);

  function validateForm() {
    return (
      fields.name.length > 0 &&
      fields.npi.length > 0 &&
      fields.email.length > 0 &&
      fields.password.length > 0 &&
      fields.password === fields.confirmPassword
    );
  }

  async function handleSubmit(event) {
    event.preventDefault();
   
    setIsLoading(true);
    let formdata = new FormData();

    formdata.append('name', fields.name);
    formdata.append('practice', fields.practice);
    formdata.append('email', fields.email);
    formdata.append('password', fields.password);
    formdata.append('confirm_password', fields.confirmPassword);
    formdata.append('npi', fields.npi);
    
      await fetch(process.env.REACT_APP_API + "/user/create_user", {
        method: 'POST',
        body: formdata
      })
      .then((response) => response.json())
      .then((data) => {
         if(data.status === "fail")
         {
           toastr.error(data.message);
           return;
         } else {
           toastr.success(data.message);
           setNewUser("New");
         }
          setIsLoading(false);
          
      })
      setIsLoading(false);
  }

  
  function validateConfirmationForm() {
    return fields.confirmationCode.length > 0;
  }

  async function handleConfirmationSubmit() {
     await fetch(`${process.env.REACT_APP_API}/api/user/verify_user?code=${fields.confirmationCode}`).then((response) => response.json()).then((data) => {
     if(data.status === "success")
     {
      toastr.success(data.message);
      props.history.push("./login");
     } else {
      toastr.error("Verification code is incorrect!");
      return;
     }
    })
  }

  function renderConfirmationForm() {
    return (
      <Form >
        <Form.Group controlId="confirmationCode" size="large">
          <Form.Label>Confirmation Code</Form.Label>
          <Form.Control
            autoFocus
            type="tel"
            onChange={handleFieldChange}
            value={fields.confirmationCode}
          />
          <FormText>Please check your email for the code.</FormText>
        </Form.Group>
        <LoaderButton
          block
          size="large"
          isLoading={isLoading}
          disabled={!validateConfirmationForm()}
          onClick = {()=>{
            handleConfirmationSubmit();
          }}
        >
          Verify
        </LoaderButton>
      </Form>
    );
  }
  console.log("newUser", newUser)
  
  
  function renderForm() {
    return (
      <form onSubmit={handleSubmit}>
        <Form.Group controlId="email" size="large">
          <Form.Label>Email</Form.Label>
          <Form.Control
            autoFocus
            type="email"
            value={fields.email}
            onChange={handleFieldChange}
          />
        </Form.Group>
        <Form.Group controlId="password" size="large">
          <Form.Label>Password</Form.Label>
          <Form.Control
            type="password"
            value={fields.password}
            onChange={handleFieldChange}
          />
        </Form.Group>
        <Form.Group controlId="confirmPassword" size="large">
          <Form.Label>Confirm Password</Form.Label>
          <Form.Control
            type="password"
            onChange={handleFieldChange}
            value={fields.confirmPassword}
          />
        </Form.Group>
        <Form.Group controlId="name" size="large">
          <Form.Label>Name</Form.Label>
          <Form.Control
            type="text"
            value={fields.name}
            onChange={handleFieldChange}
          />
        </Form.Group>
        <Form.Group controlId="practice" size="large">
          <Form.Label>Practice</Form.Label>
          <Form.Control
            type="text"
            value={fields.practice}
            onChange={handleFieldChange}
          />
        </Form.Group>
        <Form.Group controlId="npi" size="large">
          <Form.Label>NPI #</Form.Label>
          <Form.Control
            type="text"
            value={fields.npi}
            onChange={handleFieldChange}
          />
        </Form.Group>
      
        <LoaderButton
          block
          type="submit"
          size="large"
          isLoading={isLoading}
          disabled={!validateForm()}
        >
          Signup
        </LoaderButton>
      </form>
    );
  }
  // if(newUser !== null)
  // {
  //   props.history.push("./login")
  // }
  return (
    <div className="Signup">
      {newUser === null ? renderForm() : renderConfirmationForm()}
    </div>
  );
}

export default withRouter(Signup);