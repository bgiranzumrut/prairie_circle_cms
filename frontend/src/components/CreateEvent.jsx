import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";

function CreateEvent() {
  const [formData, setFormData] = useState({
    title: "",
    description: "",
    category_id: "",
    event_date: "",
    status: "upcoming",
  }); // Form state
  const [categories, setCategories] = useState([]); // Categories list
  const [message, setMessage] = useState(""); // Message for user feedback
  const navigate = useNavigate();

  useEffect(() => {
    // Check user role from sessionStorage
    const userRole = sessionStorage.getItem("userRole");
    console.log("Retrieved user role from sessionStorage:", userRole);

    // Restrict access if role is unauthorized
    if (userRole !== "admin" && userRole !== "event_coordinator") {
      setMessage("Access denied. Only authorized users can create events.");
      console.log("Access denied. Current userRole:", userRole);
      return;
    }

    // Fetch categories if user is authorized
    fetch("http://localhost/prairie_circle_cms/backend/categories/read.php")
      .then((response) => {
        if (!response.ok) throw new Error("Failed to fetch categories");
        return response.json();
      })
      .then((data) => {
        console.log("Fetched categories:", data); // Debug log
        setCategories(data); // Populate categories dropdown
      })
      .catch((error) => {
        console.error("Error fetching categories:", error.message);
        setMessage("Failed to load categories.");
      });
  }, []);

  const handleChange = (e) => {
    // Update form fields
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();

    // Send create event request to backend
    fetch("http://localhost/prairie_circle_cms/backend/events/create.php", {
      method: "POST",
      credentials: "include", // Include session cookies
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(formData),
    })
      .then((response) => {
        console.log("Response from create event API:", response); // Debug log
        if (!response.ok) throw new Error("Failed to create event");
        return response.json();
      })
      .then((data) => {
        console.log("Create event response data:", data); // Debug log
        setMessage(data.message || "Event created successfully!");
        navigate("/events"); // Redirect to events page
      })
      .catch((error) => {
        console.error("Error creating event:", error.message);
        setMessage(error.message);
      });
  };

  // Restrict form access for unauthorized users
  if (message.includes("Access denied")) {
    return <p>{message}</p>;
  }

  return (
    <div>
      <h2>Create New Event</h2>
      <form onSubmit={handleSubmit}>
        <label>
          Event Title:
          <input
            type="text"
            name="title"
            value={formData.title}
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
            required
          ></textarea>
        </label>
        <label>
          Category:
          <select
            name="category_id"
            value={formData.category_id}
            onChange={handleChange}
            required
          >
            <option value="">Select a Category</option>
            {categories.map((category) => (
              <option key={category.id} value={category.id}>
                {category.name}
              </option>
            ))}
          </select>
        </label>
        <label>
          Event Date:
          <input
            type="date"
            name="event_date"
            value={formData.event_date}
            onChange={handleChange}
            required
          />
        </label>
        <label>
          Status:
          <select
            name="status"
            value={formData.status}
            onChange={handleChange}
            required
          >
            <option value="upcoming">Upcoming</option>
            <option value="ongoing">Ongoing</option>
            <option value="completed">Completed</option>
          </select>
        </label>
        <button type="submit">Create Event</button>
      </form>
      {message && <p>{message}</p>}
    </div>
  );
}

export default CreateEvent;
