import { createStore, applyMiddleware, compose, combineReducers } from 'redux';
import createSagaMiddleware from 'redux-saga';
import * as reducers from './reducer';
import { reducer as toastrReducer } from "react-redux-toastr";
import {
  authSubscriber,
} from './auth/authSaga';



const initialState = {};
const enhancers = [];
const sagaMiddleware = createSagaMiddleware();
const middleware = [sagaMiddleware];
const reducer = combineReducers({
  ...reducers,
  toastr: toastrReducer
});
const composedEnhancers = compose(
    applyMiddleware(...middleware),
    ...enhancers
  );
  
const store = createStore(reducer, initialState, composedEnhancers);

/**
 *
 * Run saga subscribers
 *
 */
sagaMiddleware.run(authSubscriber);
export default store;
