import React, { useState, useEffect } from "react"; // Import hooks

function EventsPage() {
  const [events, setEvents] = useState([]);
  const [categories, setCategories] = useState([]);
  const [selectedCategory, setSelectedCategory] = useState("");
  useEffect(() => {
    fetch("http://localhost/prairie_circle_cms/backend/categories/read.php")
      .then((response) => response.json())
      .then((data) => setCategories(data));
  }, []);

  const fetchEvents = (categoryId) => {
    fetch(
      `http://localhost/prairie_circle_cms/backend/events/read.php?id=${categoryId}`
    )
      .then((response) => response.json())
      .then((data) => setEvents(data));
  };

  const handleCategoryChange = (e) => {
    const categoryId = e.target.value;
    setSelectedCategory(categoryId);
    fetchEvents(categoryId);
  };

  return (
    <div>
      <h1>Events</h1>
      <select onChange={handleCategoryChange}>
        <option value="">All Categories</option>
        {categories.map((category) => (
          <option key={category.id} value={category.id}>
            {category.name}
          </option>
        ))}
      </select>
      <ul>
        {events.map((event) => (
          <li key={event.id}>
            {event.title} - {event.description} ({event.event_date})
          </li>
        ))}
      </ul>
    </div>
  );
}

export default EventsPage;
