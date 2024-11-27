import React, { useState } from "react";

function EventRegistration({ userId, eventId }) {
  const [message, setMessage] = useState("");
  const [loading, setLoading] = useState(false);
  const [isRegistered, setIsRegistered] = useState(false); // Track registration status

  const handleRegister = async () => {
    if (!userId) {
      setMessage("Please log in to register for this event.");
      return;
    }

    setLoading(true);
    setMessage(""); // Clear any previous messages

    try {
      const response = await fetch(
        "http://localhost/prairie_circle_cms/backend/events/event_registration.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ userId, eventId }),
        }
      );

      const data = await response.json();
      if (response.ok) {
        setMessage(data.message || "Successfully registered for the event.");
        setIsRegistered(true); // Mark as registered
      } else {
        setMessage(data.error || "Something went wrong. Please try again.");
      }
    } catch (error) {
      console.error("Error during registration:", error);
      setMessage("An error occurred. Please try again.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div>
      <h3>Register for Event</h3>
      {message && (
        <p className={isRegistered ? "success-message" : "error-message"}>
          {message}
        </p>
      )}
      <button
        onClick={handleRegister}
        disabled={loading || isRegistered} // Disable button if loading or already registered
      >
        {isRegistered ? "Registered" : loading ? "Registering..." : "Register"}
      </button>
    </div>
  );
}

export default EventRegistration;
