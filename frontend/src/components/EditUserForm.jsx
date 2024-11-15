import React, { useState } from "react";

function EditUserForm({ user, onSave, onCancel }) {
  const [formData, setFormData] = useState({
    name: user.name,
    email: user.email,
    role: user.role,
  });

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    fetch(`http://localhost/prairie_circle_cms/backend/users/update.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ ...formData, id: user.id }),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Failed to update user");
        }
        return response.json();
      })
      .then((data) => {
        alert("User updated successfully!");
        onSave(); // Refresh the user list
      })
      .catch((error) => {
        alert("Error updating user: " + error.message);
      });
  };

  return (
    <form onSubmit={handleSubmit}>
      <label>
        Name:
        <input
          type="text"
          name="name"
          value={formData.name}
          onChange={handleInputChange}
        />
      </label>
      <label>
        Email:
        <input
          type="email"
          name="email"
          value={formData.email}
          onChange={handleInputChange}
        />
      </label>
      <label>
        Role:
        <select name="role" value={formData.role} onChange={handleInputChange}>
          <option value="admin">Admin</option>
          <option value="event_coordinator">Event Coordinator</option>
          <option value="registered_user">Registered User</option>
        </select>
      </label>
      <button type="submit">Save</button>
      <button type="button" onClick={onCancel}>
        Cancel
      </button>
    </form>
  );
}

export default EditUserForm;
