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
      .then((response) => {
        console.log("Login response status:", response.status); // Debug: Check status
        if (!response.ok) throw new Error("Login failed");
        return response.json();
      })
      .then((data) => {
        console.log("Full backend response data:", data); // Debugging the full response object
        if (data.message) {
          console.log("Name:", data.name); // Log the name
          console.log("Role:", data.role); // Log the role
          sessionStorage.setItem("userRole", data.role);
          const userRole = sessionStorage.getItem("userRole");
          console.log(
            "Retrieved userRole immediately after setting:",
            userRole
          );

          sessionStorage.setItem("userName", data.name);
          const userName = sessionStorage.getItem("userName");
          console.log(
            "Retrieved userName immediately after setting:",
            userName
          );

          onLogin(data.name, data.role); // Pass the name, role to the parent
          setMessage(`Welcome, ${data.name}!`);
          setTimeout(() => navigate("/"), 200);
        } else if (data.error) {
          setMessage(data.error);
        }
      })
      .catch((error) => {
        console.error("Error during login:", error.message); // Debug: Error
        setMessage("An error occurred. Please try again.");
      });
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
          required
        />
        <input
          type="password"
          name="password"
          placeholder="Password"
          value={formData.password}
          onChange={handleChange}
          required
        />
        <button type="submit">Sign In</button>
      </form>
      <h3>{message}</h3>
    </div>
  );
}

export default SignIn;
