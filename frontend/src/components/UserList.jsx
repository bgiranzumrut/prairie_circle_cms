import React, { useState, useEffect } from "react";

function UserList() {
  const [users, setUsers] = useState([]);
  const [currentPage, setCurrentPage] = useState(1);
  const [usersPerPage] = useState(5); // Number of users per page

  useEffect(() => {
    fetch("http://localhost/prairie_circle_cms/backend/users/read.php", {
      method: "GET",
      credentials: "include", // Include session credentials
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => {
        setUsers(data); // Update the state with fetched data
      })
      .catch((error) => {
        console.error("Fetch error:", error);
      });
  }, []); // Empty dependency array means this will run once when the component mounts

  // Get current users for the current page
  const indexOfLastUser = currentPage * usersPerPage;
  const indexOfFirstUser = indexOfLastUser - usersPerPage;
  const currentUsers = users.slice(indexOfFirstUser, indexOfLastUser);

  // Change page
  const paginate = (pageNumber) => setCurrentPage(pageNumber);

  // Handle edit and delete
  const [editingUser, setEditingUser] = useState(null); // Track the user being edited
  const [isEditing, setIsEditing] = useState(false); // Control the modal visibility

  const handleEdit = (id) => {
    const userToEdit = users.find((user) => user.id === id);
    if (userToEdit) {
      setEditingUser(userToEdit);
      setIsEditing(true); // Show modal
    }
  };

  const handleUpdate = (updatedUser) => {
    fetch("http://localhost/prairie_circle_cms/backend/users/update.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(updatedUser),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => {
        alert(data.message); // Display success message
        setUsers(
          users.map((user) =>
            user.id === updatedUser.id ? { ...user, ...updatedUser } : user
          )
        );
        setIsEditing(false); // Close modal
      })
      .catch((error) => {
        console.error("Error updating user:", error);
      });
  };

  const handleDelete = (id) => {
    if (window.confirm("Are you sure you want to delete this user?")) {
      fetch("http://localhost/prairie_circle_cms/backend/users/delete.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ id }),
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
          }
          return response.json();
        })
        .then((data) => {
          alert(data.message); // Display success message
          setUsers(users.filter((user) => user.id !== id)); // Update state
        })
        .catch((error) => {
          console.error("Error deleting user:", error);
        });
    }
  };

  return (
    <div>
      {isEditing && (
        <div className="modal">
          <h2>Edit User</h2>
          <form
            onSubmit={(e) => {
              e.preventDefault();
              handleUpdate(editingUser);
            }}
          >
            <input
              type="text"
              value={editingUser.name}
              onChange={(e) =>
                setEditingUser({ ...editingUser, name: e.target.value })
              }
              required
            />
            <input
              type="email"
              value={editingUser.email}
              onChange={(e) =>
                setEditingUser({ ...editingUser, email: e.target.value })
              }
              required
            />

            <button
              type="button"
              onClick={(e) => {
                e.preventDefault(); // To prevent page reload
                handleUpdate(editingUser); // Save logic here
              }}
            >
              Save
            </button>
            <button type="button" onClick={() => setIsEditing(false)}>
              Cancel
            </button>
          </form>
        </div>
      )}

      <h1>Users</h1>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {currentUsers.map((user) => (
            <tr key={user.id}>
              <td>{user.name}</td>
              <td>{user.email}</td>
              <td>{user.role}</td>
              <td>
                <button onClick={() => handleEdit(user.id)}>Edit</button>
                <button onClick={() => handleDelete(user.id)}>Delete</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      {/* Pagination */}
      <div>
        {Array.from(
          { length: Math.ceil(users.length / usersPerPage) }, // Total pages
          (_, index) => (
            <button key={index + 1} onClick={() => paginate(index + 1)}>
              {index + 1}
            </button>
          )
        )}
      </div>
    </div>
  );
}

export default UserList;
