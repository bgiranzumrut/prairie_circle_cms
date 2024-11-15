import React, { useState } from "react";
import { useNavigate } from "react-router-dom";

function SignIn({ onLogin }) {
  const [formData, setFormData] = useState({ email: "", password: "" });
  const [message, setMessage] = useState("");
  const navigate = useNavigate();

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    fetch("http://localhost/prairie_circle_cms/backend/users/login.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(formData),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.message) {
          onLogin(data.name);
          setMessage(`Welcome, ${data.name}!`);
          navigate("/"); // Redirect to home
        } else if (data.error) {
          setMessage(data.error);
        }
      })
      .catch(() => setMessage("An error occurred. Please try again."));
  };

  return (
    <div>
      <h2>Sign In</h2>
      <form onSubmit={handleSubmit}>
        <input
          type="email"
          name="email"
          placeholder="Email"
          value={formData.email}
          onChange={handleChange}
        />
        <input
          type="password"
          name="password"
          placeholder="Password"
          value={formData.password}
          onChange={handleChange}
        />
        <button type="submit">Sign In</button>
      </form>
      <h3>{message}</h3>
    </div>
  );
}

export default SignIn;
