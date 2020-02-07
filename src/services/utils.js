import axios from 'axios';

// import store from './store';
export const wrapRequest = func => {
  return async (...args) => {
    const res = await func(...args);
    if (
      res &&
      res.status &&
      res.status !== 200 &&
      res.status !== 201 &&
      res.status !== 204
    ) {
      throw res;
    } else {
      return res.data;
    }
  };
};

export const xapi = () => {
/*
  let token = null;
  let tokenType = null;
  if (store.getState().default.services.auth.token) {
    token = store.getState().default.services.auth.token.access_token;
    tokenType = store.getState().default.services.auth.token.token_type;
  }
*/

  let headers = {
    'X-Requested-With': 'XMLHttpRequest',
    Accept: 'application/json',
    charset: 'UTF-8'
  };

  // if (token) {
  //   headers = {
  //     ...headers,
  //     Authorization: `${tokenType} ${token}`
  //   };
  // }

  let xapi = axios.create({
    baseURL: process.env.REACT_APP_API,
    headers: headers
  });
  return xapi;
};
