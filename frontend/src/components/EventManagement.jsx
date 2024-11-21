import React, { useState, useEffect } from "react";

function EventManagement() {
  const [events, setEvents] = useState([]);
  const userRole = sessionStorage.getItem("userRole");

  useEffect(() => {
    fetch("http://localhost/prairie_circle_cms/backend/events/read.php", {
      method: "GET",
      credentials: "include",
    })
      .then((response) => response.json())
      .then((data) => setEvents(data))
      .catch((error) => console.error("Error fetching events:", error));
  }, []);

  const handleCreate = () => {
    // Logic for creating an event
  };

  const handleEdit = (eventId) => {
    // Logic for editing an event
  };

  const handleDelete = (eventId) => {
    if (window.confirm("Are you sure you want to delete this event?")) {
      fetch(`http://localhost/prairie_circle_cms/backend/events/delete.php`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ id: eventId }),
      })
        .then((response) => response.json())
        .then(() => {
          setEvents(events.filter((event) => event.id !== eventId));
        })
        .catch((error) => console.error("Error deleting event:", error));
    }
  };

  return (
    <div>
      <h1>Event Management</h1>
      {userRole === "admin" || userRole === "event_coordinator" ? (
        <button onClick={handleCreate}>Create Event</button>
      ) : (
        <p>You do not have permission to create events.</p>
      )}

      <ul>
        {events.map((event) => (
          <li key={event.id}>
            <h3>{event.name}</h3>
            <p>{event.description}</p>
            {userRole === "admin" || userRole === "event_coordinator" ? (
              <>
                <button onClick={() => handleEdit(event.id)}>Edit</button>
                <button onClick={() => handleDelete(event.id)}>Delete</button>
              </>
            ) : null}
          </li>
        ))}
      </ul>
    </div>
  );
}

export default EventManagement;
