import React from "react";
import { useNavigate } from "react-router-dom";
import "./../styles/Header.css";

function Header({ userRole, userName, onLogout }) {
  const navigate = useNavigate();

  const handleSignOut = () => {
    onLogout();
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
            <li>
              <a href="/events">Events</a>
            </li>
            <li>
              <a href="/categories">Categories</a>
            </li>
            {(userRole === "admin" || userRole === "event_coordinator") && (
              <li>
                <a href="/event-management">Event Management</a>
              </li>
            )}
            {userRole === "admin" && (
              <li>
                <a href="/category-management">Category Management</a>
              </li>
            )}
          </ul>
        </nav>
      </div>
      <div className="auth-section">
        {userName ? (
          <div className="welcome">
            <h3>
              Welcome, {userName}! ({userRole})
            </h3>
            <button onClick={handleSignOut}>Sign Out</button>
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
