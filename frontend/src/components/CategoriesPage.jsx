import React, { useState, useEffect } from "react";

function CategoriesPage() {
  const [categories, setCategories] = useState([]);

  useEffect(() => {
    fetch(
      "http://localhost/prairie_circle_cms/backend/categories/categories.php"
    )
      .then((response) => response.json())
      .then((data) => setCategories(data))
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
