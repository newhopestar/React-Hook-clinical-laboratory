import React from "react";
import "./NotFound.css";

export default function NotFound() {
  return (
    <div className="NotFound">
      <h3>Sorry, page not found!</h3>
      <p>URL: {process.env.PUBLIC_URL}</p>
    </div>
  );
}