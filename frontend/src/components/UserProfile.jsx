import React, { useState, useEffect } from "react";
import { useParams } from "react-router-dom";

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
    <div>
      <h2>User Profile</h2>
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
                <strong>Date:</strong> {event.event_date}
              </p>
              <p>
                <strong>Description:</strong> {event.description}
              </p>
              <p>
                <strong>Category:</strong> {event.category_name}
              </p>
            </li>
          ))}
        </ul>
      ) : (
        <p>No events joined yet.</p>
      )}
    </div>
  );
}

export default UserProfile;
