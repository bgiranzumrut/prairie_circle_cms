import React from "react"; // Import React
import { useNavigate } from "react-router-dom"; // Import navigation hook
import "./../styles/Header.css"; // Correct CSS import

function Header({ userRole, userName, onLogout }) {
  const navigate = useNavigate();

  const handleSignOut = () => {
    onLogout(); // Logout user
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
          </ul>
        </nav>
      </div>
      <div className="auth-section">
        {userName ? (
          <div className="welcome">
            <h3>
              Welcome, {userName}! {userRole}
            </h3>
            <button onClick={handleSignOut}>Sign Out</button>
          </div>
        ) : (
          <div>
            <button onClick={() => navigate("/SignIn")}>Sign In</button>
            <button onClick={() => navigate("/SignUp")}>Sign Up</button>
          </div>
        )}
      </div>
    </header>
  );
}

export default Header;
