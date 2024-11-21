import React, { useState, useEffect } from "react";

function CategoryManagement() {
  const [categories, setCategories] = useState([]); // List of categories
  const [formData, setFormData] = useState({
    name: "",
    description: "",
    id: null,
  }); // Form data for creating/editing categories
  const [message, setMessage] = useState(""); // Feedback message

  // Fetch categories on component mount
  useEffect(() => {
    fetchCategories();
  }, []);

  const fetchCategories = () => {
    fetch("http://localhost/prairie_circle_cms/backend/categories/read.php")
      .then((response) => response.json())
      .then((data) => setCategories(data))
      .catch((err) => console.error("Failed to fetch categories:", err));
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    const url = formData.id
      ? "http://localhost/prairie_circle_cms/backend/categories/update.php"
      : "http://localhost/prairie_circle_cms/backend/categories/create.php";

    fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(formData),
    })
      .then((response) => response.json())
      .then((data) => {
        setMessage(data.message || "Operation successful!");
        fetchCategories(); // Refresh categories list
        setFormData({ name: "", description: "", id: null }); // Reset form
      })
      .catch((err) => console.error("Error:", err));
  };

  const handleEdit = (category) => {
    setFormData({
      id: category.id,
      name: category.name,
      description: category.description,
    });
  };

  const handleDelete = (id) => {
    if (!window.confirm("Are you sure you want to delete this category?"))
      return;

    fetch("http://localhost/prairie_circle_cms/backend/categories/delete.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id }),
    })
      .then((response) => response.json())
      .then((data) => {
        setMessage(data.message || "Category deleted successfully!");
        fetchCategories(); // Refresh categories list
      })
      .catch((err) => console.error("Error deleting category:", err));
  };

  return (
    <div>
      <h1>Category Management</h1>

      {/* Feedback Message */}
      {message && <p>{message}</p>}

      {/* Form for Creating/Editing Categories */}
      <form onSubmit={handleSubmit}>
        <h2>{formData.id ? "Edit Category" : "Create Category"}</h2>
        <label>
          Name:
          <input
            type="text"
            name="name"
            value={formData.name}
            onChange={handleInputChange}
            required
          />
        </label>
        <label>
          Description:
          <textarea
            name="description"
            value={formData.description}
            onChange={handleInputChange}
            required
          ></textarea>
        </label>
        <button type="submit">
          {formData.id ? "Update Category" : "Create Category"}
        </button>
      </form>

      {/* Categories List */}
      <h2>Categories List</h2>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {categories.map((category) => (
            <tr key={category.id}>
              <td>{category.name}</td>
              <td>{category.description}</td>
              <td>
                <button onClick={() => handleEdit(category)}>Edit</button>
                <button onClick={() => handleDelete(category.id)}>
                  Delete
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

export default CategoryManagement;
