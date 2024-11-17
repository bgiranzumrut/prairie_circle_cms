import React, { useState } from "react";
import { Routes, Route } from "react-router-dom";
import Header from "./components/Header";
import Footer from "./components/Footer";
import HomePage from "./components/HomePage";
import UsersPage from "./components/UsersPage";
import EventsPage from "./components/EventsPage";
import CategoriesPage from "./components/CategoriesPage";
import SignIn from "./components/SignIn";
import SignUp from "./components/SignUp";
import AdminPanel from "./components/AdminPanel";

function App() {
  const [userName, setUserName] = useState(
    sessionStorage.getItem("userName") || null
  );
  const [userRole, setUserRole] = useState(
    sessionStorage.getItem("userRole") || null
  );

  // Handle login
  const handleLogin = (name, role) => {
    setUserName(name);
    setUserRole(role);
    sessionStorage.setItem("userName", name);
    sessionStorage.setItem("userRole", role);
  };

  // Handle logout
  const handleLogout = () => {
    setUserName(null);
    setUserRole(null);
    sessionStorage.clear();
  };

  return (
    <div>
      <Header userName={userName} userRole={userRole} onLogout={handleLogout} />
      <Routes>
        <Route path="/" element={<HomePage />} />
        <Route path="/users" element={<UsersPage />} />
        <Route path="/events" element={<EventsPage />} />
        <Route path="/categories" element={<CategoriesPage />} />
        <Route path="/SignIn" element={<SignIn onLogin={handleLogin} />} />
        <Route path="/SignUp" element={<SignUp />} />

        <Route path="/admin" element={<AdminPanel />} />
      </Routes>
      <Footer />
    </div>
  );
}

export default App;
