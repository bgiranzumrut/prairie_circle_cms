import React, { useState, useEffect } from "react";
import Header from "./Header"; // Import Header component
import Footer from "./Footer"; // Import Footer component
import "./../styles/HomePage.css"; // Import styles for the HomePage
import { useNavigate } from "react-router-dom"; // For navigation

function HomePage() {
  const [events, setEvents] = useState([]); // State for events
  const navigate = useNavigate(); // For navigating to Sign In/Sign Up forms

  // Fetch events when the component mounts
  useEffect(() => {
    fetch("http://localhost/prairie_circle_cms/backend/events/read.php")
      .then((response) => response.json())
      .then((data) => setEvents(data))
      .catch((error) => console.error("Error fetching events:", error));
  }, []);

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

        {/* Upcoming Events Section */}
        <section className="events">
          <h2>Upcoming Events</h2>
          <ul>
            {events.length > 0 ? (
              events.map((event) => (
                <li key={event.id}>
                  <h3>{event.title}</h3>
                  <p>{event.description}</p>
                  <p>Date: {event.event_date}</p>
                </li>
              ))
            ) : (
              <p>No upcoming events.</p>
            )}
          </ul>
        </section>

        {/* Features Section */}
        <section className="features">
          <h2>Upcoming Features</h2>
          <ul>
            <li>Dynamic event management</li>
            <li>Role-based user authentication</li>
            <li>Interactive community dashboards</li>
          </ul>
        </section>
      </main>
    </div>
  );
}

export default HomePage;
