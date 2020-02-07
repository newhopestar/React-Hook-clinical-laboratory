/**
 *
 * 
 * @author ikram
 * @since 31/1/2020
 */

import { createActions } from 'redux-actions';

const {
  login,
  loginFailed,
  loginSucceed,
  getStoredUser,
  getUserFromApi,
  getUserFromApiFailed,
  getUserFromApiSucceed,
  logout
} = createActions({
  LOGIN: (email, password) => ({ email, password }),
  LOGIN_FAILED: error => ({ error }),
  LOGIN_SUCCEED: token => ({ token }),
  GET_STORED_USER: () => ({}),
  GET_USER_FROM_API: () => ({}),
  GET_USER_FROM_API_FAILED: error => ({ error }),
  GET_USER_FROM_API_SUCCEED: user => ({ user }),
  LOGOUT: () => ({})
});

export {
  login,
  loginFailed,
  loginSucceed,
  getStoredUser,
  getUserFromApi,
  getUserFromApiFailed,
  getUserFromApiSucceed,
  logout
};
