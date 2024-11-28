import React from "react";
import { Navigate } from "react-router-dom";

function ProtectedRoute({ children, isAuthenticated, allowedRoles, userRole }) {
  console.log("isAuthenticated:", isAuthenticated);
  console.log("userRole:", userRole);

  if (!isAuthenticated) {
    console.log("Redirecting to SignIn");
    return <Navigate to="/SignIn" />;
  }

  if (!allowedRoles.includes(userRole)) {
    console.log("User role not allowed. Redirecting to Home");
    return <Navigate to="/SignIn" />;
  }

  return children;
}

export default ProtectedRoute;
