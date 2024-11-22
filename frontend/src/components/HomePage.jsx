import React, { useState, useEffect } from "react";
import Header from "./Header"; // Import Header component
import Footer from "./Footer"; // Import Footer component
import "./../styles/HomePage.css"; // Import styles for the HomePage
import { useNavigate } from "react-router-dom"; // For navigation

function HomePage() {
  const [events, setEvents] = useState([]); // State for events
  const [categories, setCategories] = useState([]); // State for categories
  const [sortBy, setSortBy] = useState("event_date"); // Sorting criteria
  const [order, setOrder] = useState(""); // Sorting order (default to empty)
  const [titleFilter, setTitleFilter] = useState(""); // Filter by title
  const [statusFilter, setStatusFilter] = useState(""); // Filter by status
  const [categoryFilter, setCategoryFilter] = useState(""); // Filter by category
  const navigate = useNavigate(); // Navigation hook

  // Fetch events and categories when filters or sorting change
  useEffect(() => {
    const queryParams = new URLSearchParams({
      sort: sortBy,
      order: order || "ASC", // Fallback to ASC if empty
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

  // Fetch categories for dropdown
  useEffect(() => {
    fetch("http://localhost/prairie_circle_cms/backend/categories/read.php")
      .then((response) => response.json())
      .then((data) => setCategories(data))
      .catch((error) => console.error("Error fetching categories:", error));
  }, []);

  const handleJoin = (eventId) => {
    console.log(`User wants to join event ID: ${eventId}`);
    // Implement the join functionality later
  };

  return (
    <div className="home-page">
      {/* Main Content */}
      <main className="content">
        {/* Welcome Section */}
        <section className="welcome">
          <h2>Welcome to Prairie Circle CMS</h2>
          <p>
            Explore our community-driven platform designed for events, user
            management, and interactive dashboards.
          </p>
        </section>

        {/* Filter and Sort Events Section */}
        <section className="filter-sort-events">
          <div className="filters">
            {/* Filter by Title */}
            <input
              type="text"
              placeholder="Search by title"
              value={titleFilter}
              onChange={(e) => setTitleFilter(e.target.value)}
              className="filter-input"
            />

            {/* Filter by Status */}
            <select
              value={statusFilter}
              onChange={(e) => setStatusFilter(e.target.value)}
              className="filter-select"
            >
              <option value="">All Statuses</option>
              <option value="upcoming">Upcoming</option>
              <option value="ongoing">Ongoing</option>
              <option value="completed">Completed</option>
            </select>

            {/* Filter by Category */}
            <select
              value={categoryFilter}
              onChange={(e) => setCategoryFilter(e.target.value)}
              className="filter-select"
            >
              <option value="">All Categories</option>
              {categories.map((category) => (
                <option key={category.id} value={category.id}>
                  {category.name}
                </option>
              ))}
            </select>

            {/* Sort Order */}
            <select
              value={order}
              onChange={(e) => setOrder(e.target.value)}
              className="filter-select"
            >
              <option value="">Order by Date</option>
              <option value="ASC">Dates closest to now</option>
              <option value="DESC">Dates farthest to now</option>
            </select>
          </div>
        </section>

        {/* Events Section */}
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
                    <button onClick={() => handleJoin(event.id)}>
                      Register
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
