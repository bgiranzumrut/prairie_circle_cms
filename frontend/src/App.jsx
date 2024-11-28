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

  const isAuthenticated = !!user?.role; // Ensure user authentication status is accurate

  return (
    <div>
      <Header userName={user?.name} onLogout={handleLogout} />

      <Routes>
        {/* Public Routes */}
        <Route
          path="/"
          element={<HomePage userName={user?.name} userId={user?.id} />}
        />
        <Route path="/signin" element={<SignIn onLogin={handleLogin} />} />
        <Route path="/signup" element={<SignUp />} />
        <Route path="/events/:id" element={<EventsPage />} />
        {/* Protected Routes */}
        <Route
          path="/users"
          element={
            <ProtectedRoute
              isAuthenticated={isAuthenticated}
              allowedRoles={["admin", "event_coordinator", "registered_user"]}
              userRole={user?.role}
            >
              <UserList />
            </ProtectedRoute>
          }
        />
        <Route
          path="/categories"
          element={
            <ProtectedRoute
              isAuthenticated={isAuthenticated}
              allowedRoles={["admin", "event_coordinator"]}
              userRole={user?.role}
            >
              <CategoriesPage />
            </ProtectedRoute>
          }
        />
        <Route
          path="/event-management"
          element={
            <ProtectedRoute
              isAuthenticated={isAuthenticated}
              allowedRoles={["admin", "event_coordinator"]}
              userRole={user?.role}
            >
              <EventManagement />
            </ProtectedRoute>
          }
        />
        <Route
          path="/category-management"
          element={
            <ProtectedRoute
              isAuthenticated={isAuthenticated}
              allowedRoles={["admin"]}
              userRole={user?.role}
            >
              <CategoryManagement />
            </ProtectedRoute>
          }
        />
        <Route
          path="/profile/:userId"
          element={
            <ProtectedRoute
              isAuthenticated={isAuthenticated}
              allowedRoles={["admin", "event_coordinator", "registered_user"]}
              userRole={user?.role}
            >
              <UserProfile />
            </ProtectedRoute>
          }
        />
        <Route
          path="/register/:id"
          element={
            <ProtectedRoute
              isAuthenticated={isAuthenticated}
              allowedRoles={["admin", "event_coordinator", "registered_user"]}
              userRole={user?.role}
            >
              <EventRegistration userId={user?.id} />
            </ProtectedRoute>
          }
        />
        <Route
          path="/profile"
          element={
            <ProtectedRoute
              isAuthenticated={isAuthenticated}
              allowedRoles={["admin", "event_coordinator", "registered_user"]}
              userRole={user?.role}
            >
              <MyProfile />
            </ProtectedRoute>
          }
        />
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
