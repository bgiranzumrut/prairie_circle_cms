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

  const handleLogin = (name) => {
    console.log("Logging in with name:", name); // Debug
    setUserName(name);
    sessionStorage.setItem("userName", name);
  };

  const handleLogout = () => {
    setUserName(null);
    sessionStorage.clear();
  };

  return (
    <div>
      <Header userName={userName} onLogout={handleLogout} />
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
