import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";

function CreateEvent() {
  const [formData, setFormData] = useState({
    title: "",
    description: "",
    category_id: "",
    event_date: "",
    image: null, // Image file
  }); // Form state
  const [categories, setCategories] = useState([]); // Categories list
  const [message, setMessage] = useState(""); // Message for user feedback
  const navigate = useNavigate();
  const userRole = sessionStorage.getItem("userRole"); // Fetch user role

  useEffect(() => {
    // Restrict access to event creation if role is unauthorized
    if (userRole !== "admin" && userRole !== "event_coordinator") {
      setMessage("Access denied. Only authorized users can create events.");
      return;
    }

    // Fetch categories if user is authorized
    fetch("http://localhost/prairie_circle_cms/backend/categories/read.php")
      .then((response) => {
        if (!response.ok) throw new Error("Failed to fetch categories");
        return response.json();
      })
      .then((data) => setCategories(data))
      .catch((error) => {
        console.error("Error fetching categories:", error.message);
        setMessage("Failed to load categories.");
      });
  }, [userRole]);

  const handleChange = (e) => {
    // Update form fields
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleFileChange = (e) => {
    // Handle file input for image
    setFormData({ ...formData, image: e.target.files[0] });
  };

  const handleSubmit = (e) => {
    e.preventDefault();

    const formDataObj = new FormData();
    formDataObj.append("title", formData.title);
    formDataObj.append("description", formData.description);
    formDataObj.append("category_id", formData.category_id);
    formDataObj.append("event_date", formData.event_date);
    if (formData.image) {
      formDataObj.append("image", formData.image); // Add image file
    }

    // Send create event request to backend
    fetch("http://localhost/prairie_circle_cms/backend/events/create.php", {
      method: "POST",
      credentials: "include", // Include session cookies
      body: formDataObj,
    })
      .then((response) => {
        if (!response.ok) throw new Error("Failed to create event");
        return response.json();
      })
      .then((data) => {
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

      {/* Form for creating events */}
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
          Image:
          <input
            type="file"
            name="image"
            accept="image/*"
            onChange={handleFileChange}
          />
        </label>
        <button type="submit">Create Event</button>
      </form>

      {/* Feedback Message */}
      {message && <p>{message}</p>}
    </div>
  );
}

export default CreateEvent;
