import React from "react";
import { Route, Switch } from "react-router-dom";
import AppliedRoute from "../components/AppliedRoute";
import AuthenticatedRoute from "../components/AuthenticatedRoute";
import UnauthenticatedRoute from "../components/UnauthenticatedRoute";
import Home from "../containers/Home";
import Results from "../containers/Results";
import Settings from "../containers/Settings";
import Login from "../containers/Login";
import Signup from "../containers/Signup";
import NotFound from "../containers/NotFound";

export default function Routes({ appProps }) {
  return (
    <Switch>
      <AppliedRoute path="/" exact component={Home} appProps={appProps} />
      <UnauthenticatedRoute path="/login" exact component={Login} appProps={appProps} />
      <UnauthenticatedRoute path="/signup" exact component={Signup} appProps={appProps} />
      <AuthenticatedRoute path="/results" exact component={Results} appProps={appProps} />
      <AuthenticatedRoute path="/settings" exact component={Settings} appProps={appProps} />
      { /* Finally, catch all unmatched routes */ }
      <Route component={NotFound} />
    </Switch>
  );
}
