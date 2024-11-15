import React, { useEffect, useState } from "react";

function UserList() {
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [page, setPage] = useState(0);

  useEffect(() => {
    fetchUsers();
  }, []);

  const fetchUsers = () => {
    setLoading(true);
    fetch("http://localhost/prairie_circle_cms/backend/users/read.php")
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        setUsers(data);
        setLoading(false);
      })
      .catch((err) => {
        setError("Failed to fetch users");
        setLoading(false);
      });
  };

  const nextPage = () => setPage((prev) => prev + 1);
  const prevPage = () => setPage((prev) => Math.max(prev - 1, 0));

  const deleteUser = (userId) => {
    if (!window.confirm("Are you sure you want to delete this user?")) return;

    fetch(`http://localhost/prairie_circle_cms/backend/users/delete.php`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id: userId }),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Failed to delete user");
        }
        return response.json();
      })
      .then((data) => {
        if (data.message) {
          alert(data.message);
          fetchUsers(); // Refresh the user list
        } else {
          alert("Deletion failed: " + (data.error || "Unknown error"));
        }
      })
      .catch((err) => {
        alert("Failed to delete user: " + err.message);
      });
  };

  if (loading) return <p>Loading...</p>;
  if (error) return <p>{error}</p>;

  return (
    <div>
      <h1>Users</h1>
      <ul>
        {users.map((user) => (
          <li key={user.id}>
            {user.name} ({user.email}){" "}
            <button onClick={() => deleteUser(user.id)}>Delete</button>
          </li>
        ))}
      </ul>
    </div>
  );
}

export default UserList;
