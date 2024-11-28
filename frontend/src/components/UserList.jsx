import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import "./../styles/UserList.css";

function UserList() {
  const [users, setUsers] = useState([]);
  const userRole = sessionStorage.getItem("userRole"); // Get the logged-in user's role

  useEffect(() => {
    fetch("http://localhost/prairie_circle_cms/backend/users/read.php", {
      method: "GET",
      credentials: "include",
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => setUsers(data))
      .catch((error) => console.error("Fetch error:", error));
  }, []);

  return (
    <div className="user-list">
      <h1>Our Community Members</h1>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          {users.map((user) => (
            <tr key={user.id}>
              <td>{user.name}</td>
              <td>{user.email}</td>
              <td>
                <Link to={`/profile/${user.id}`}>See Profile</Link>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

export default UserList;
