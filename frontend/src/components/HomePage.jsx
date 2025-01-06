import React, { useContext, useEffect, useState } from "react";
import "./../styles/HomePage.css";
import { useNavigate } from "react-router-dom";
import { UserContext } from "../context/UserContext";

function HomePage() {
  const navigate = useNavigate();
  const { user } = useContext(UserContext); // Access user context
  const [events, setEvents] = useState([]);
  const [categories, setCategories] = useState([]);
  const [sortBy, setSortBy] = useState("event_date");
  const [order, setOrder] = useState("ASC");
  const [titleFilter, setTitleFilter] = useState("");
  const [statusFilter, setStatusFilter] = useState("");
  const [categoryFilter, setCategoryFilter] = useState("");

  // Fetch events
  useEffect(() => {
    const queryParams = new URLSearchParams({
      sort: sortBy,
      order,
      title: titleFilter,
      status: statusFilter,
      category: categoryFilter,
    });

    fetch(
      `http://localhost/prairie_circle_cms/backend/events/read.php?${queryParams.toString()}`
    )
      .then((response) => response.json())
      .then((data) => setEvents(data))
      .catch((error) => console.error("Error fetching events:", error));
  }, [sortBy, order, titleFilter, statusFilter, categoryFilter]);

  // Fetch categories
  useEffect(() => {
    fetch("http://localhost/prairie_circle_cms/backend/categories/read.php")
      .then((response) => response.json())
      .then((data) => setCategories(data))
      .catch((error) => console.error("Error fetching categories:", error));
  }, []);

  // Handle registration
  const handleRegisterClick = (eventId) => {
    if (!user?.id) {
      alert("You need to log in to register for an event.");
      return;
    }

    fetch(
      "http://localhost/prairie_circle_cms/backend/events/event_registration.php",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        credentials: "include",
        body: JSON.stringify({ userId: user.id, eventId }),
      }
    )
      .then((response) => response.json())
      .then((data) => {
        if (data.error) {
          alert(data.error); // Display the error message from the backend
        } else {
          alert(data.message || "Successfully registered!");
          setEvents((prevEvents) =>
            prevEvents.map((event) =>
              event.id === eventId ? { ...event, isRegistered: true } : event
            )
          );
        }
      })
      .catch((error) => {
        console.error("Error during registration:", error);
        alert("An error occurred. Please try again.");
      });
  };

  return (
    <div className="home-page">
      <main className="content">
        <section className="welcome">
          <h2>Welcome to Prairie Circle</h2>
          <p>
            Explore our community-driven platform designed for events, user
            management, and interactive dashboards.
          </p>
        </section>
        <section className="filter-sort-events">
          <div className="filters">
            <input
              type="text"
              placeholder="Search by title"
              value={titleFilter}
              onChange={(e) => setTitleFilter(e.target.value)}
              className="filter-input"
            />
            <select
              value={statusFilter}
              onChange={(e) => setStatusFilter(e.target.value)}
              className="filter-select"
              aria-label="Filter by Status"
            >
              <option value="">All Statuses</option>
              <option value="upcoming">Upcoming</option>
              <option value="ongoing">Ongoing</option>
              <option value="completed">Completed</option>
            </select>
            <select
              value={categoryFilter}
              onChange={(e) => setCategoryFilter(e.target.value)}
              className="filter-select"
              aria-label="Filter by Category"
            >
              <option value="">All Categories</option>
              {categories.map((category) => (
                <option key={category.id} value={category.id}>
                  {category.name}
                </option>
              ))}
            </select>
            <select
              value={order}
              onChange={(e) => setOrder(e.target.value)}
              className="filter-select"
              aria-label="Sort Order"
            >
              <option value="ASC">Dates closest to now</option>
              <option value="DESC">Dates farthest from now</option>
            </select>
          </div>
        </section>
        <section className="events">
          <ul className="event-list">
            {events.length > 0 ? (
              events.map((event) => (
                <li key={event.id} className="event-item">
                  {event.image_path && (
                    <img
                      src={`http://localhost/prairie_circle_cms/backend/${event.image_path}`}
                      alt={event.title}
                      className="event-image"
                    />
                  )}
                  <h3>{event.title}</h3>
                  <p>{event.description}</p>
                  <p>
                    <strong>Date:</strong> {event.event_date}
                  </p>
                  <p>
                    <strong>Category:</strong>{" "}
                    {event.category_name || "Uncategorized"}
                  </p>
                  <p>
                    <strong>Status:</strong>{" "}
                    <span className={`status-tag ${event.status}`}>
                      {event.status.charAt(0).toUpperCase() +
                        event.status.slice(1)}
                    </span>
                  </p>
                  <div className="event-buttons">
                    <button
                      onClick={() => navigate(`/events/${event.id}`)}
                      className="details-button"
                    >
                      View Details
                    </button>
                    <button
                      onClick={() => handleRegisterClick(event.id)}
                      disabled={event.isRegistered}
                      className="register-button"
                    >
                      {event.isRegistered ? "Registered" : "Register"}
                    </button>
                  </div>
                </li>
              ))
            ) : (
              <p>No events found.</p>
            )}
          </ul>
        </section>
      </main>
    </div>
  );
}

export default HomePage;
