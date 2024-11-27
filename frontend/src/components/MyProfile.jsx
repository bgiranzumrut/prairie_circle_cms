import React, { useEffect, useState } from "react";

function MyProfile() {
  const [user, setUser] = useState(null);
  const [events, setEvents] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    const fetchUserProfile = async () => {
      try {
        const response = await fetch(
          "http://localhost/prairie_circle_cms/backend/users/user_profile.php",
          {
            method: "GET",
            credentials: "include", // Include cookies for session
          }
        );

        const data = await response.json();

        if (response.ok) {
          setUser(data.user);
          setEvents(data.events || []);
        } else {
          setError(data.error || "Failed to fetch profile.");
        }
      } catch (err) {
        console.error("Error fetching profile:", err);
        setError("An error occurred while fetching your profile.");
      } finally {
        setLoading(false);
      }
    };

    fetchUserProfile();
  }, []);

  const handleCancelRegistration = async (eventId) => {
    try {
      const response = await fetch(
        "http://localhost/prairie_circle_cms/backend/events/cancel_registration.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          credentials: "include", // Include session credentials
          body: JSON.stringify({ eventId }),
        }
      );
      const data = await response.json();

      if (response.ok) {
        alert(data.message);
        setEvents((prevEvents) =>
          prevEvents.filter((event) => event.id !== eventId)
        );
      } else {
        alert(data.error || "Failed to cancel registration.");
      }
    } catch (err) {
      console.error("Error canceling registration:", err);
      alert("An error occurred. Please try again.");
    }
  };

  return (
    <div>
      <h2>My Profile</h2>
      {loading ? (
        <p>Loading profile...</p>
      ) : error ? (
        <p className="error-message">{error}</p>
      ) : (
        user && (
          <div>
            <p>
              <strong>Name:</strong> {user.name}
            </p>
            <p>
              <strong>Email:</strong> {user.email}
            </p>
            <h3>Registered Events</h3>
            {events.length > 0 ? (
              <ul>
                {events.map((event) => (
                  <li key={event.id}>
                    <h4>{event.title}</h4>
                    <p>{event.description}</p>
                    <button
                      onClick={() => handleCancelRegistration(event.id)}
                      className="cancel-button"
                    >
                      Cancel Registration
                    </button>
                  </li>
                ))}
              </ul>
            ) : (
              <p>No events registered yet.</p>
            )}
          </div>
        )
      )}
    </div>
  );
}

export default MyProfile;
