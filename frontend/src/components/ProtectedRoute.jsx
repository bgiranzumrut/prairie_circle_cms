import React from "react";
import { Navigate } from "react-router-dom";

function ProtectedRoute({ children, allowedRoles, userRole }) {
  if (!userRole) {
    // Redirect to SignIn if not logged in
    return <Navigate to="/signin" replace />;
  }
  if (!allowedRoles.includes(userRole)) {
    // Redirect to Home if unauthorized
    return <Navigate to="/" replace />;
  }
  return children;
}

export default ProtectedRoute;
