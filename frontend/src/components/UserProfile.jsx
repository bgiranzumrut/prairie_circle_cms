import React, { useState, useEffect } from "react";
import { useParams } from "react-router-dom";
import "./../styles/UserProfile.css";

function UserProfile() {
  const { userId } = useParams(); // Get userId from URL parameters
  const [userName, setUserName] = useState(""); // Store user's name
  const [userEvents, setUserEvents] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    // Fetch the user's details (name and events)
    const fetchUserDetails = async () => {
      try {
        const response = await fetch(
          `http://localhost/prairie_circle_cms/backend/users/user_events.php?userId=${userId}`
        );
        const data = await response.json();

        if (response.ok) {
          setUserName(data.userName || `User ${userId}`);
          setUserEvents(data.events || []);
        } else {
          setError(data.error || "Failed to fetch user details.");
        }
      } catch (err) {
        console.error("Error fetching user details:", err);
        setError("An error occurred while fetching user details.");
      } finally {
        setLoading(false);
      }
    };

    fetchUserDetails();
  }, [userId]);

  return (
    <div className="user-profile">
      <h2>Profile of {userName}</h2>
      <p>
        Learn more about {userName}’s activities, events, and contributions.
      </p>

      {/* User Info Section */}
      <div className="user-info">
        <p>
          <strong>Name:</strong> {userName}
        </p>
        <p>
          <strong>Email:</strong> alice@example.com
        </p>
      </div>

      {/* Events Section */}
      <h3>Events Joined by {userName}</h3>
      {loading ? (
        <p>Loading events...</p>
      ) : error ? (
        <p className="error-message">{error}</p>
      ) : userEvents.length > 0 ? (
        <ul>
          {userEvents.map((event) => (
            <li key={event.id}>
              <h4>{event.title}</h4>
              <p>
                <strong>
                  <i className="fas fa-calendar-alt"></i> Date:
                </strong>{" "}
                {event.event_date}
              </p>
              <p>
                <strong>
                  <i className="fas fa-info-circle"></i> Description:
                </strong>{" "}
                {event.description}
              </p>
              <p>
                <strong>
                  <i className="fas fa-tag"></i> Category:
                </strong>{" "}
                {event.category_name}
              </p>
              <button>See Event</button>
            </li>
          ))}
        </ul>
      ) : (
        <p className="no-events">No events joined yet.</p>
      )}
    </div>
  );
}

export default UserProfile;
