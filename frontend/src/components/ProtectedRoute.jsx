import React from "react";
import { Navigate } from "react-router-dom";

function ProtectedRoute({ children, allowedRoles, userRole }) {
  if (!userRole) {
    return <Navigate to="/signin" replace />; // Redirect to login if not logged in
  }
  if (!allowedRoles.includes(userRole)) {
    return <Navigate to="/" replace />; // Redirect to home if unauthorized
  }
  return children;
}

export default ProtectedRoute;
