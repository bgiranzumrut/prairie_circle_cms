import React, { useContext } from "react";
import { useNavigate } from "react-router-dom";
import { UserContext } from "../context/UserContext";
import "./../styles/Header.css";

function Header() {
  const navigate = useNavigate();
  const { user, handleLogout } = useContext(UserContext); // Access user role and logout functionality from context

  const handleSignOut = () => {
    handleLogout();
    navigate("/"); // Redirect to the home page after logout
  };

  return (
    <header className="header">
      <div className="logo">
        <img
          src="/img/logo.png"
          alt="Prairie Circle Logo"
          className="home-logo"
        />
      </div>
      <div className="name">
        <h1>Prairie Circle CMS 🌟</h1>
      </div>
      <div className="nav">
        <nav>
          <ul className="nav-list">
            <li>
              <a href="/">Home</a>
            </li>
            <li>
              <a href="/users">Users</a>
            </li>
            {user.name && ( // Show "My Profile" link for logged-in users
              <li>
                <a href="/profile">My Profile</a>
              </li>
            )}
            {(user.role === "admin" || user.role === "event_coordinator") && (
              <li>
                <a href="/event-management">Event Management</a>
              </li>
            )}
            {user.role === "admin" && (
              <li>
                <a href="/category-management">Category Management</a>
              </li>
            )}
          </ul>
        </nav>
      </div>
      <div className="auth-section">
        {user.name ? (
          <div className="welcome">
            <h3>Welcome, {user.name}! </h3>
            <button
              onClick={handleSignOut}
              className="signout-button"
              aria-label="Sign Out"
            >
              Sign Out
            </button>
          </div>
        ) : (
          <div>
            <button onClick={() => navigate("/signin")}>Sign In</button>
            <button onClick={() => navigate("/signup")}>Sign Up</button>
          </div>
        )}
      </div>
    </header>
  );
}

export default Header;
