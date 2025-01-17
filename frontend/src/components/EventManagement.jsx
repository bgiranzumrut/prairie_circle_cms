import React, { useState, useEffect } from "react";
import "./../styles/EventManagement.css";

function EventManagement() {
  const [events, setEvents] = useState([]);
  const [formData, setFormData] = useState({
    id: null,
    title: "",
    description: "",
    category_id: "",
    event_date: "",
    image: null,
  });
  const [categories, setCategories] = useState([]);
  const [message, setMessage] = useState("");

  useEffect(() => {
    fetchEvents();
    fetchCategories();
  }, []);

  const fetchEvents = () => {
    fetch("http://localhost/prairie_circle_cms/backend/events/read.php")
      .then((response) => response.json())
      .then((data) => setEvents(data))
      .catch((err) => console.error("Failed to fetch events:", err));
  };

  const fetchCategories = () => {
    fetch("http://localhost/prairie_circle_cms/backend/categories/read.php")
      .then((response) => response.json())
      .then((data) => setCategories(data))
      .catch((err) => console.error("Failed to fetch categories:", err));
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleFileChange = (e) => {
    setFormData({ ...formData, image: e.target.files[0] });
  };

  const handleSubmit = (e) => {
    e.preventDefault();

    const apiUrl = formData.id
      ? "http://localhost/prairie_circle_cms/backend/events/update.php"
      : "http://localhost/prairie_circle_cms/backend/events/create.php";

    const formDataObj = new FormData();
    formDataObj.append("title", formData.title);
    formDataObj.append("description", formData.description);
    formDataObj.append("category_id", formData.category_id);
    formDataObj.append("event_date", formData.event_date);
    if (formData.image) formDataObj.append("image", formData.image);
    if (formData.id) formDataObj.append("id", formData.id);

    fetch(apiUrl, { method: "POST", body: formDataObj })
      .then((response) => response.json())
      .then((data) => {
        setMessage(data.message || "Operation successful!");
        fetchEvents();
        setFormData({
          id: null,
          title: "",
          description: "",
          category_id: "",
          event_date: "",
          image: null,
        });
      })
      .catch((err) => console.error("Error:", err));
  };

  const handleEdit = (event) => {
    setFormData({
      id: event.id,
      title: event.title,
      description: event.description,
      category_id: event.category_id,
      event_date: event.event_date,
      image: null,
    });
  };

  const handleDelete = (id) => {
    if (!window.confirm("Are you sure you want to delete this event?")) return;

    fetch("http://localhost/prairie_circle_cms/backend/events/delete.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id }),
    })
      .then((response) => response.json())
      .then((data) => {
        setMessage(data.message || "Event deleted successfully!");
        fetchEvents();
      })
      .catch((err) => console.error("Error deleting event:", err));
  };

  return (
    <div className="event-management">
      <h1>Event Management</h1>
      {message && <p>{message}</p>}

      <form onSubmit={handleSubmit}>
        <h2>{formData.id ? "Edit Event" : "Create Event"}</h2>
        <label for="title">Title:</label>
        <input
          id="title"
          type="text"
          name="title"
          value={formData.title}
          onChange={handleInputChange}
          required
        />
        <label for="description">Description:</label>
        <textarea
          id="description"
          name="description"
          value={formData.description}
          onChange={handleInputChange}
          required
        ></textarea>
        <label for="title">Category:</label>
        <select
          id="title"
          name="category_id"
          value={formData.category_id}
          onChange={handleInputChange}
          required
        >
          <option value="">Select a Category</option>
          {categories.map((category) => (
            <option key={category.id} value={category.id}>
              {category.name}
            </option>
          ))}
        </select>
        <label for="event">Event Date:</label>
        <input
          id="event"
          type="date"
          name="event_date"
          value={formData.event_date}
          onChange={handleInputChange}
          required
        />
        <label for="image">Image:</label>
        <input
          id="image"
          type="file"
          name="image"
          onChange={handleFileChange}
        />
        <button type="submit">
          {formData.id ? "Update Event" : "Create Event"}
        </button>
      </form>

      <h2>Events List</h2>
      <table>
        <thead>
          <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Category</th>
            <th>Date</th>
            <th>Image</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {events.map((event) => (
            <tr key={event.id}>
              <td>{event.title}</td>
              <td>{event.description}</td>
              <td>
                {categories.find((cat) => cat.id === event.category_id)?.name ||
                  "Unknown"}
              </td>
              <td>{event.event_date}</td>
              <td>
                {event.image_path && (
                  <img
                    src={`http://localhost/prairie_circle_cms/backend/${event.image_path}`}
                    alt={event.title}
                  />
                )}
              </td>
              <td>
                <button onClick={() => handleEdit(event)}>Edit</button>
                <button onClick={() => handleDelete(event.id)}>Delete</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

export default EventManagement;
