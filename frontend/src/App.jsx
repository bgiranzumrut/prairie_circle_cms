import React, { useState } from "react";
import { Routes, Route } from "react-router-dom";
import Header from "./components/Header";
import Footer from "./components/Footer";
import HomePage from "./components/HomePage";
import UserList from "./components/UserList";
import EventsPage from "./components/EventsPage";
import CategoriesPage from "./components/CategoriesPage";
import SignIn from "./components/SignIn";
import SignUp from "./components/SignUp";
import EventManagement from "./components/EventManagement";
import CategoryManagement from "./components/CategoryManagement";

function App() {
  const [userName, setUserName] = useState(
    sessionStorage.getItem("userName") || null
  );
  const [userRole, setUserRole] = useState(
    sessionStorage.getItem("userRole") || null
  );

  const handleLogin = (name, role) => {
    setUserName(name);
    setUserRole(role);
    sessionStorage.setItem("userName", name);
    sessionStorage.setItem("userRole", role);
  };

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
        <Route path="/users" element={<UserList />} />
        <Route path="/events" element={<EventsPage />} />
        <Route path="/categories" element={<CategoriesPage />} />
        <Route path="/signin" element={<SignIn onLogin={handleLogin} />} />
        <Route path="/signup" element={<SignUp />} />
        <Route path="/event-management" element={<EventManagement />} />
        {userRole === "admin" && (
          <Route path="/category-management" element={<CategoryManagement />} />
        )}
      </Routes>
      <Footer />
    </div>
  );
}

export default App;
