import React, { useState, useEffect } from "react";
import Form from 'react-bootstrap/Form'
import { Button, ButtonToolbar } from "react-bootstrap";
import LoaderButton from "../components/LoaderButton";
import OrderDetails from "./OrderDetails";
import './ResultsByDate.css';

export default function ResultsByDate() {

  const [isLoading, setIsLoading] = useState(false);
  const [isError, setIsError] = useState(false);
  const [data, setData] = useState([]);

  const [npi, setNPI] = useState("");

  const [desiredDate, setDesiredDate] = useState("");  
  const [status, setStatus] = useState("Completed|ReCompleted");
  const [lastName, setLastName] = useState("");
  const [firstName, setFirstName] = useState("");
  const [patientId, setPatientId] = useState("");
  const [params, setParams] = useState({ status: "Completed|ReCompleted"});
  
  const [order, setOrderDetails] = useState([]);
  
  useEffect(() => {
    let isCancelled = false;
    const fetchData = async() => {
      setIsError(false);
      setIsLoading(true);

      var userData = JSON.parse( localStorage.getItem('auth') );
      if(userData.user_detail && userData.user_detail.npi) {
        setNPI(userData.user_detail.npi);  
      }
      
      
      try {
        if(!isCancelled)
        {
          const querystring = require('querystring');
          let queryStr = querystring.stringify(params);
          var url = process.env.REACT_APP_API + '/api.php?n=' + npi + '&m=Orders';
          let response = await fetch(url, {
            method: 'post',
            body: queryStr,
            headers:{
              'Authorization': `Bearer ${userData.access_token}`,
           }
          });
          let responseJson = await response.json();
          setData(responseJson);
        }
       
      } catch (error) {
        if(!isCancelled)
        {
          setIsError(true);
        }
      }
      setIsLoading(false);
    };
    
    fetchData();
    return () => {
      isCancelled = true;
    };
  }, [params, npi, ]);
  
  
  function getOrderDetails(e) {
    const order = e.currentTarget.getAttribute('data-item');
    console.log('We need to get the details for ', order);
    
    setOrderDetails(order);
  }
  
  function getImage(status) {
    const statusOptions = ["Created", "Processing", "ReCompleted", "Completed"];
    
    if(statusOptions.indexOf(status) !== -1) {
      return "./images/order-" + status.toLowerCase() + ".svg";
    } else {
      return "./images/order-unknown.svg";
    }
  }
  
  function handleReset(e) {
    e.preventDefault();
    
    setStatus("");
    setDesiredDate("");
    setLastName("");
    setFirstName("");
    setPatientId("");
    setParams([]);
  }
  
  function handleSubmit(event) {
    event.preventDefault();

    var _param = [];
    
    if(desiredDate) { 
      _param["order-date"] = dateToYMD( Date.parse(desiredDate) );
    }
    if(status) {
      _param["status"] = status;
    }
    if(lastName) {
      _param["last-name"] = lastName;
    }
    if(firstName) {
      _param["first-name"] = firstName;
    }
    if(patientId) {
      _param["patient-id"] = patientId;
    }
    setParams(_param);    
    setOrderDetails("");
  }
  

  function dateToYMD(_date) {
    
      console.log("parsed: " + _date);
      var date = new Date(_date);
      console.log("date: " + date);

      var d = date.getDate()+1;
      var m = date.getMonth() + 1; //Month from 0 to 11
      var y = date.getFullYear();
      return '' + y + '' + (m<=9 ? '0' + m : m) + '' + (d <= 9 ? '0' + d : d);
  }
  
  
  return (
    <div className="Home">
      <form onSubmit={handleSubmit}>
        <div className="d-flex flex-row justify-content-start flex-wrap flex-lg-nowrap">
          <Form.Group className="p-1">
            <Form.Label>Status</Form.Label>
            <Form.Control
              as="select"
              defaultValue="Completed|ReCompleted"
              onChange={event => setStatus(event.target.value)}
            >
              <option value="">All</option>
              <option value="Created">Created</option>
              <option value="Processing">Processing</option>
              <option value="Completed|ReCompleted">Completed</option>
            </Form.Control>
          </Form.Group>
        
          <Form.Group className="p-1 searchDate">
            <Form.Label>Search Date</Form.Label>
            <Form.Control
              type="date"
              format='yyyyMMdd'
              value={desiredDate}
              onChange={event => setDesiredDate(event.target.value)}
            />
          </Form.Group>
          <Form.Group className="p-1 searchLName flex-shrink-1">
            <Form.Label>Last Name</Form.Label>
            <Form.Control
              type="text"
              value={lastName}
              onChange={event => setLastName(event.target.value)}
            />
          </Form.Group>
          <Form.Group className="p-1 searchFName flex-shrink-1">
            <Form.Label>First Name</Form.Label>
            <Form.Control
              type="text"
              value={firstName}
              onChange={event => setFirstName(event.target.value)}
            />
          </Form.Group>
          <Form.Group className="p-1 searchAccount flex-shrink-1">
            <Form.Label>Account</Form.Label>
            <Form.Control
              type="text"
              value={patientId}
              onChange={event => setPatientId(event.target.value)}
            />
          </Form.Group>
          <Form.Group className="p-1 buttons flex-sm-grow-1 flex-sm-fill">
            <Form.Label>&nbsp;</Form.Label>
            <ButtonToolbar>
              <LoaderButton
                type="submit"
                size="large"
                isLoading={isLoading}
              >
                Search
              </LoaderButton>{' '}
              <Button
                variant="secondary"
                type="reset"
                onClick={handleReset}
                size="large">Reset</Button>
            </ButtonToolbar>
          </Form.Group>
        </div>
      </form>
      
      <div>
        { isError 
          ? <div className="container">Sorry, there are no orders available ...</div>
          : isLoading ? <div className="lander">Loading ...</div>
              : (<div className="row">
                  <div className="col-md-7 orders">

                    <table className="table table-striped table-hover results">
                      <thead className="thead-dark">
                        <tr>
                          <td>Order #</td>
                          <td>Date</td>
                          <td>Patient</td>
                          <td>Account</td>
                          <td>Status</td>
                        </tr>            
                      </thead>
                      
                      <tbody>
                        {data && data.map((item, index) => (
                        <tr key={`Requisition-key-${index}`} data-item={item.DetailUri} onClick={getOrderDetails}
                            className = {order === item.DetailUri ? "activeRow" : ""}
                        >
                          <td>{item.Requisition}</td>
                          <td>{item.Date}</td>
                          <td>{item.Patient}</td>
                          <td>{item.Account}</td>
                          <td><img className="pdf" 
                          src={ getImage(item.Status) } alt={item.Status} title={item.Status} /></td>
                        </tr>              
                        ))}
                      </tbody>
                    </table>
    
                  </div>
                  <div className="col-md-5 details">
                     <OrderDetails  order={order} />
                  </div>
                </div>)
        }
      </div>
    </div>
  );
}