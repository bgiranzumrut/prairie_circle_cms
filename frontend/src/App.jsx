import React, { useContext } from "react";
import { Routes, Route } from "react-router-dom";
import Header from "./components/Header";
import Footer from "./components/Footer";
import HomePage from "./components/HomePage";
import UserList from "./components/UserList";
import EventsPage from "./components/EventsPage";
import CategoriesPage from "./components/CategoriesPage";
import SignIn from "./components/SignIn";
import SignUp from "./components/SignUp";
import EventManagement from "./components/EventManagement";
import CategoryManagement from "./components/CategoryManagement";
import ProtectedRoute from "./components/ProtectedRoute";
import UserProfile from "./components/UserProfile";
import EventRegistration from "./components/EventRegistration";
import MyProfile from "./components/MyProfile";
import { UserProvider, UserContext } from "./context/UserContext"; // Import UserProvider and UserContext

function App() {
  const { user, handleLogin, handleLogout } = useContext(UserContext); // Access context

  return (
    <div>
      {/* Pass user state and logout handler to Header */}
      <Header userName={user.name} onLogout={handleLogout} />

      <Routes>
        {/* Public Routes */}
        <Route
          path="/"
          element={<HomePage userName={user.name} userId={user.id} />}
        />
        <Route path="/signin" element={<SignIn onLogin={handleLogin} />} />
        <Route path="/signup" element={<SignUp />} />

        {/* Protected Routes */}
        <Route
          path="/users"
          element={
            <ProtectedRoute
              allowedRoles={["admin", "event_coordinator", "registered_user"]}
              userRole={user.role}
            >
              <UserList />
            </ProtectedRoute>
          }
        />
        <Route path="/events/:id" element={<EventsPage />} />
        <Route
          path="/categories"
          element={
            <ProtectedRoute
              allowedRoles={["admin", "event_coordinator"]}
              userRole={user.role}
            >
              <CategoriesPage />
            </ProtectedRoute>
          }
        />
        <Route
          path="/event-management"
          element={
            <ProtectedRoute
              allowedRoles={["admin", "event_coordinator"]}
              userRole={user.role}
            >
              <EventManagement />
            </ProtectedRoute>
          }
        />
        <Route
          path="/category-management"
          element={
            <ProtectedRoute allowedRoles={["admin"]} userRole={user.role}>
              <CategoryManagement />
            </ProtectedRoute>
          }
        />
        <Route path="/profile/:userId" element={<UserProfile />} />
        <Route
          path="/register/:id"
          element={<EventRegistration userId={user.id} />}
        />
        <Route path="/profile" element={<MyProfile />} />
      </Routes>

      <Footer />
    </div>
  );
}

// Wrap App with UserProvider
export default function AppWithProvider() {
  return (
    <UserProvider>
      <App />
    </UserProvider>
  );
}
