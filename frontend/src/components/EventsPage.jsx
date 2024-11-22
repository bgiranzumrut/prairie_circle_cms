import React, { useState, useEffect } from "react";
import { useParams } from "react-router-dom";
import Header from "./Header";
import Footer from "./Footer";
import "./../styles/EventsPage.css";

function EventsPage() {
  const { id } = useParams(); // Retrieve the event ID from the URL
  const [event, setEvent] = useState(null); // State to store event details
  const [loading, setLoading] = useState(true); // Loading state
  const [error, setError] = useState(null); // Error state

  useEffect(() => {
    // Fetch event details by ID
    fetch(
      `http://localhost/prairie_circle_cms/backend/events/read.php?id=${id}`
    )
      .then((response) => {
        if (!response.ok) {
          throw new Error("Failed to fetch event details"); // Handle non-200 responses
        }
        return response.json();
      })
      .then((data) => {
        if (data && data.length > 0) {
          setEvent(data[0]); // Set event if data exists (assuming API returns an array)
        } else {
          setError("Event not found"); // Handle no matching event
        }
        setLoading(false);
      })
      .catch((error) => {
        setError(error.message || "Failed to fetch event details"); // Handle fetch error
        setLoading(false);
      });
  }, [id]); // Dependency array ensures fetch triggers on ID change

  if (loading) {
    return <p>Loading event details...</p>; // Display while loading
  }

  if (error) {
    return <p>Error: {error}</p>; // Display error if any
  }

  if (!event) {
    return <p>Event not found.</p>; // Handle missing event
  }

  return (
    <div>
      <div className="event-details">
        <h1>{event.title}</h1>
        <div className="event-content">
          {/* Image on the left */}
          {event.image_path ? (
            <img
              src={`http://localhost/prairie_circle_cms/backend/${event.image_path}`}
              alt={event.title}
              className="event-image"
            />
          ) : (
            <div>No Image Available</div>
          )}

          {/* Details on the right */}
          <div className="event-info">
            <p>
              <strong>Date:</strong> {event.event_date}
            </p>
            <p>
              <strong>Status:</strong> {event.status}
            </p>
            <p>
              <strong>Category:</strong> {event.category_name}
            </p>
            <p>{event.description}</p>
            <button className="register-button">Register</button>
          </div>
        </div>
      </div>
    </div>
  );
}

export default EventsPage;
