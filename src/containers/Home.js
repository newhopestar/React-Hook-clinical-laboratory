import React, { useEffect } from "react";
import { Link } from "react-router-dom";
import "./Home.css";

export default function Home(props) {
  
  useEffect(() => {
    async function onLoad() {
      if (!props.isAuthenticated) {
        return;
      }
    }

    onLoad();
  }, [props.isAuthenticated]);
  
  function renderLander() {
    return (
      <div className="lander">
        <h2>Welcome to the Clinical Laboratory Provider Portal</h2>
        <div><p className="login-box-msg"><a href="https://www.stxlab.com/">View results prior to Feb 1, 2020</a></p></div>
        <Link to="./login">Sign In</Link>&nbsp;|&nbsp;
        <Link to="./signup">Register</Link>
      </div>      
    );
  }
  
  function renderHome() {
    return (
      <div className="Home lander">
        <h5>Notice:</h5>
        <p>This portal is provided as a tool for you to access your patients' lab results. You are responsible for securing your
        patients' records.</p>
        <p><Link to="./results">View Lab Results</Link></p>
      </div>
    )    
  }
  
  return (
    <div className="Home">
      {props.isAuthenticated ? renderHome() : renderLander()}
    </div>
  );
}