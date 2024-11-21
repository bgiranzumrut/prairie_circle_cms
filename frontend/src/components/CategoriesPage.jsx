import React, { useState, useEffect } from "react";

function CategoriesPage() {
  const [categories, setCategories] = useState([]);
  useEffect(() => {
    fetch("http://localhost/prairie_circle_cms/backend/categories/read.php")
      .then((response) => response.text()) // Log raw response
      .then((data) => {
        console.log("Raw response:", data); // Inspect the response format
        setCategories(JSON.parse(data)); // Parse only if it's valid JSON
      })
      .catch((error) => console.error("Error fetching categories:", error));
  }, []);

  return (
    <div>
      <h1>Categories</h1>
      <ul>
        {categories.map((category) => (
          <li key={category.id}>
            {category.name} - {category.description}
          </li>
        ))}
      </ul>
    </div>
  );
}

export default CategoriesPage;
