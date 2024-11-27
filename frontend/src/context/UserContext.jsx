import React, { createContext, useState } from "react";

// Create the context
export const UserContext = createContext();

// Create a provider component
export const UserProvider = ({ children }) => {
  const [user, setUser] = useState({
    id: sessionStorage.getItem("userId") || null,
    name: sessionStorage.getItem("userName") || null,
    role: sessionStorage.getItem("userRole") || "guest",
  });

  /**
   * Handles user login.
   * Updates state and sessionStorage with the provided user details.
   * @param {string} id - User ID
   * @param {string} name - User Name
   * @param {string} role - User Role
   */
  const handleLogin = (id, name, role) => {
    const userData = { id, name, role };

    setUser(userData);

    // Store user details in sessionStorage
    sessionStorage.setItem("userId", id);
    sessionStorage.setItem("userName", name);
    sessionStorage.setItem("userRole", role);
  };

  /**
   * Handles user logout.
   * Clears user state and sessionStorage.
   */
  const handleLogout = () => {
    setUser({ id: null, name: null, role: "guest" });

    // Clear sessionStorage
    sessionStorage.removeItem("userId");
    sessionStorage.removeItem("userName");
    sessionStorage.removeItem("userRole");
  };

  return (
    <UserContext.Provider value={{ user, handleLogin, handleLogout }}>
      {children}
    </UserContext.Provider>
  );
};
