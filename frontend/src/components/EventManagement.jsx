import React, { useState, useEffect } from "react";

function EventManagement() {
  const [events, setEvents] = useState([]);
  const [categories, setCategories] = useState([]);
  const [newEvent, setNewEvent] = useState({
    title: "",
    description: "",
    category_id: "",
    event_date: "",
    status: "upcoming",
    image_path: "",
  });
  const [editingEvent, setEditingEvent] = useState(null);
  const [error, setError] = useState(null);

  // Fetch events and categories
  useEffect(() => {
    fetch("http://localhost/prairie_circle_cms/backend/events/read.php")
      .then((response) => {
        if (!response.ok)
          throw new Error(`HTTP error! Status: ${response.status}`);
        return response.json();
      })
      .then(setEvents)
      .catch((err) => setError(err.message));

    fetch("http://localhost/prairie_circle_cms/backend/categories/read.php")
      .then((response) => {
        if (!response.ok)
          throw new Error(`HTTP error! Status: ${response.status}`);
        return response.json();
      })
      .then(setCategories)
      .catch((err) => setError(err.message));
  }, []);

  // Handle creating a new event
  const handleCreate = (e) => {
    e.preventDefault();
    fetch("http://localhost/prairie_circle_cms/backend/events/create.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(newEvent),
    })
      .then((response) => {
        if (!response.ok) throw new Error("Failed to create event");
        return response.json();
      })
      .then((data) => {
        alert(data.message);
        setEvents([...events, { ...newEvent, id: data.id }]);
        setNewEvent({
          title: "",
          description: "",
          category_id: "",
          event_date: "",
          status: "upcoming",
          image_path: "",
        });
      })
      .catch((err) => setError(err.message));
  };

  // Handle updating an event
  const handleUpdate = (e) => {
    e.preventDefault();
    fetch("http://localhost/prairie_circle_cms/backend/events/update.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(editingEvent),
    })
      .then((response) => {
        if (!response.ok) throw new Error("Failed to update event");
        return response.json();
      })
      .then((data) => {
        alert(data.message);
        setEvents(
          events.map((event) =>
            event.id === editingEvent.id ? editingEvent : event
          )
        );
        setEditingEvent(null);
      })
      .catch((err) => setError(err.message));
  };

  // Handle deleting an event
  const handleDelete = (id) => {
    if (!window.confirm("Are you sure you want to delete this event?")) return;
    fetch("http://localhost/prairie_circle_cms/backend/events/delete.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id }),
    })
      .then((response) => {
        if (!response.ok) throw new Error("Failed to delete event");
        return response.json();
      })
      .then((data) => {
        alert(data.message);
        setEvents(events.filter((event) => event.id !== id));
      })
      .catch((err) => setError(err.message));
  };

  return (
    <div>
      <h1>Event Management</h1>
      {error && <p>Error: {error}</p>}

      {/* Create New Event */}
      <form onSubmit={handleCreate}>
        <input
          type="text"
          value={newEvent.title}
          onChange={(e) => setNewEvent({ ...newEvent, title: e.target.value })}
          placeholder="Title"
          required
        />
        <textarea
          value={newEvent.description}
          onChange={(e) =>
            setNewEvent({ ...newEvent, description: e.target.value })
          }
          placeholder="Description"
          required
        ></textarea>
        <select
          value={newEvent.category_id}
          onChange={(e) =>
            setNewEvent({ ...newEvent, category_id: e.target.value })
          }
          required
        >
          <option value="">Select Category</option>
          {categories.map((cat) => (
            <option key={cat.id} value={cat.id}>
              {cat.name}
            </option>
          ))}
        </select>
        <input
          type="date"
          value={newEvent.event_date}
          onChange={(e) =>
            setNewEvent({ ...newEvent, event_date: e.target.value })
          }
          required
        />
        <select
          value={newEvent.status}
          onChange={(e) => setNewEvent({ ...newEvent, status: e.target.value })}
        >
          <option value="upcoming">Upcoming</option>
          <option value="ongoing">Ongoing</option>
          <option value="completed">Completed</option>
        </select>
        <button type="submit">Create Event</button>
      </form>

      {/* List of Events */}
      <div>
        {events.map((event) => (
          <div key={event.id}>
            {editingEvent && editingEvent.id === event.id ? (
              <form onSubmit={handleUpdate}>
                <input
                  type="text"
                  value={editingEvent.title}
                  onChange={(e) =>
                    setEditingEvent({ ...editingEvent, title: e.target.value })
                  }
                  required
                />
                <textarea
                  value={editingEvent.description}
                  onChange={(e) =>
                    setEditingEvent({
                      ...editingEvent,
                      description: e.target.value,
                    })
                  }
                  required
                ></textarea>
                <select
                  value={editingEvent.category_id}
                  onChange={(e) =>
                    setEditingEvent({
                      ...editingEvent,
                      category_id: e.target.value,
                    })
                  }
                  required
                >
                  <option value="">Select Category</option>
                  {categories.map((cat) => (
                    <option key={cat.id} value={cat.id}>
                      {cat.name}
                    </option>
                  ))}
                </select>
                <input
                  type="date"
                  value={editingEvent.event_date}
                  onChange={(e) =>
                    setEditingEvent({
                      ...editingEvent,
                      event_date: e.target.value,
                    })
                  }
                  required
                />
                <select
                  value={editingEvent.status}
                  onChange={(e) =>
                    setEditingEvent({ ...editingEvent, status: e.target.value })
                  }
                >
                  <option value="upcoming">Upcoming</option>
                  <option value="ongoing">Ongoing</option>
                  <option value="completed">Completed</option>
                </select>
                <button type="submit">Save</button>
                <button type="button" onClick={() => setEditingEvent(null)}>
                  Cancel
                </button>
              </form>
            ) : (
              <div>
                <h3>{event.title}</h3>
                <p>{event.description}</p>
                <p>Category: {event.category_name}</p>
                <p>Date: {event.event_date}</p>
                <p>Status: {event.status}</p>
                <button onClick={() => setEditingEvent(event)}>Edit</button>
                <button onClick={() => handleDelete(event.id)}>Delete</button>
              </div>
            )}
          </div>
        ))}
      </div>
    </div>
  );
}

export default EventManagement;
