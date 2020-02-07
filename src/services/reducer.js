import { combineReducers } from 'redux';

/** Import service reducers */
import authReducer from './auth/authReducer';

const servicesReducer = combineReducers({
  auth: authReducer
});

export default combineReducers({
  services: servicesReducer
});
