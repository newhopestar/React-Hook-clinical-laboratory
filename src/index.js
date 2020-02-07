import "react-app-polyfill/ie11";
import "react-app-polyfill/stable";
import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import { BrowserRouter as Router } from 'react-router-dom';
import './index.css';
import App from './app/App';
import * as serviceWorker from './serviceWorker';
import store from './services/store'
import 'react-redux-toastr/lib/css/react-redux-toastr.min.css';

ReactDOM.render(
  <Router basename='/providers'>
    <Provider store = {store}>
       <App />
    </Provider>
  </Router>,
  document.getElementById('root')
);

// If you want your app to work offline and load faster, you can change
// unregister() to register() below. Note this comes with some pitfalls.
// Learn more about service workers: https://bit.ly/CRA-PWA
serviceWorker.unregister();
