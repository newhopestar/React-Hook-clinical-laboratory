import React, { useState } from "react";
import Form from 'react-bootstrap/Form'
import LoaderButton from "../components/LoaderButton";
import "./Login.css";
import { connect } from 'react-redux';
import { login } from '../services/auth/authActions';
import { bindActionCreators } from "redux";
import { toastr } from 'react-redux-toastr';

function Login(props) {
  const [isLoading, setIsLoading] = useState(false);
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  
  function validateForm() {
    return email.length > 0 && password.length > 0;
  }
	async function handleSubmit(event) {
    event.preventDefault();

    setIsLoading(true);	
    
    let formdata = new FormData();

    formdata.append('email', email);
    formdata.append('password', password);
      await fetch(process.env.REACT_APP_API + "/user/login", {
        method: 'POST',
        body: formdata
      })
      .then((response) => response.json())
      .then((data) => {
        setIsLoading(false);	
        if(data.status === "fail")
        {
          toastr.error(data.message);
          return;
        }
        if (data.access_token!==null){
          props.userHasAuthenticated(true);
          localStorage.setItem("auth", JSON.stringify(data));
          toastr.success(data.message)
        }
      })
	}  

  return (
    <div className="Login">
      <h1>Login</h1>
      <form onSubmit={handleSubmit}>
        <Form.Group controlId="email" size="large">
          <Form.Label>Email/ID</Form.Label>
          <Form.Control
            autoFocus
            type="text"
            onChange={(e)=>setEmail(e.target.value)}
            value = {email}
          />
        </Form.Group>
        <Form.Group controlId="password" size="large">
          <Form.Label>Password</Form.Label>
          <Form.Control
            onChange={(e)=>setPassword(e.target.value)}
            type="password"
            value = {password}
          />
        </Form.Group>
        <LoaderButton
          block
          type="submit"
          size="large"
          isLoading={isLoading}
          disabled={!validateForm()}
        >
          Login
        </LoaderButton>
      </form>
    </div>
  );
}

export default connect(
  state=> ({
    ...state.default.auth
  }),
  dispatch => ({
    authActions: bindActionCreators({login}, dispatch)
  })
)(Login)