import React, { useState, useEffect } from "react";
import { Link, withRouter } from "react-router-dom";
import { Nav, Navbar } from "react-bootstrap";

import Routes from "./Routes";
import '../../node_modules/bootstrap/dist/css/bootstrap.css';
import './App.css';
import '../assets/css/styles.css';
import Footer from '../containers/_footer';
import ReduxToastr from 'react-redux-toastr';

function App(props) {
  const [isAuthenticated, userHasAuthenticated] = useState(false);

  const [providerName, setProviderName] = useState("");
  const [practiceName, setPracticeName] = useState("");

  useEffect(() => {
    onLoad();
  }, []);
  
  async function onLoad() {
    try {
      //await Auth.currentSession();
      var userData = JSON.parse( localStorage.getItem('auth') );
      
      if(!userData){
        userHasAuthenticated(false);
      } else {
        if(userData.access_token){
          userHasAuthenticated(true);
          
          setProviderName(userData["user_detail"]["name"]);
          setPracticeName(userData["user_detail"]["practice"]);
          
        } else {
          userHasAuthenticated(false);
        }
      }
      
    }
    catch(e) {
      if (e !== 'No current user') {
        alert(e);
      }
    }  
  }  

  async function handleLogout() {
    //await Auth.signOut();
    userHasAuthenticated(false);
    localStorage.setItem("auth", null);
    props.history.push("./login");
  }

  return (
    <div className="App container">
  	  <Navbar expand="lg" bg="light" variant="light" collapseOnSelect>
        <Navbar.Brand>
            <Link to="./"><img src={process.env.PUBLIC_URL + "/images/stx_clinical_laboratory_logo.png"} alt={"logo"} /></Link>
        </Navbar.Brand>
        <Navbar.Toggle />
        <Navbar.Collapse>
          <Nav className="ml-auto">
    			{isAuthenticated ? (
              <>
                <Nav.Link eventKey="1" as={Link} to="./results">Results</Nav.Link>
                <Nav.Link eventKey="2" as={Link} to="./" onClick={handleLogout} >Logout</Nav.Link>
              </>  			
      			): (
              <>
                <Nav.Link eventKey="1" as={Link} to="./signup">Register</Nav.Link>
                <Nav.Link eventKey="2" as={Link} to="./login">Login</Nav.Link>
    			    </>
    			  )
    			}          
          </Nav>
        </Navbar.Collapse>
      </Navbar>
      <div className="content">
        {isAuthenticated ? (
          <div className="welcome">
            <h4>
            Welcome {providerName}
            <span className="practice">{ practiceName !== "" 
              ? ( " / " + practiceName ) 
              : ( "" ) } 
            </span>
            </h4>
          </div>
        ) : ( "" ) }
        <Routes appProps={{ isAuthenticated, userHasAuthenticated }} />
      </div>
      <ReduxToastr position="top-center" />
      <Footer/>
    </div>
  );
}


export default withRouter(App);
