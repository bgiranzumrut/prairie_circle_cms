import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";

function CreateCategory() {
  const [formData, setFormData] = useState({
    name: "",
    description: "",
  }); // Form state
  const [message, setMessage] = useState(""); // Message for user feedback
  const navigate = useNavigate();

  useEffect(() => {
    // Check user role from sessionStorage
    const userRole = sessionStorage.getItem("userRole");
    console.log("Retrieved user role from sessionStorage:", userRole);

    // Restrict access if role is not admin
    if (userRole !== "admin") {
      setMessage("Access denied. Only administrators can create categories.");
      console.log("Access denied. Current userRole:", userRole);
    }
  }, []);

  const handleChange = (e) => {
    // Update form fields
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();

    // Send create category request to backend
    fetch("http://localhost/prairie_circle_cms/backend/categories/create.php", {
      method: "POST",
      credentials: "include", // Include session cookies
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(formData),
    })
      .then((response) => {
        console.log("Response from create category API:", response); // Debug log
        if (!response.ok) throw new Error("Failed to create category");
        return response.json();
      })
      .then((data) => {
        console.log("Create category response data:", data); // Debug log
        setMessage(data.message || "Category created successfully!");
        navigate("/categories"); // Redirect to categories page
      })
      .catch((error) => {
        console.error("Error creating category:", error.message);
        setMessage(error.message);
      });
  };

  // Restrict form access for unauthorized users
  if (message.includes("Access denied")) {
    return <p>{message}</p>;
  }

  return (
    <div>
      <h2>Create New Category</h2>
      <form onSubmit={handleSubmit}>
        <label>
          Category Name:
          <input
            type="text"
            name="name"
            value={formData.name}
            onChange={handleChange}
            required
          />
        </label>
        <label>
          Description:
          <textarea
            name="description"
            value={formData.description}
            onChange={handleChange}
          ></textarea>
        </label>
        <button type="submit">Create Category</button>
      </form>
      {message && <p>{message}</p>}
    </div>
  );
}

export default CreateCategory;
