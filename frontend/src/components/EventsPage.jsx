import React, { useState, useEffect, useContext } from "react";
import { useParams } from "react-router-dom";
import { UserContext } from "../context/UserContext";
import "./../styles/EventsPage.css";

function EventsPage() {
  const { id } = useParams(); // Retrieve the event ID from the URL
  const { user } = useContext(UserContext); // Access user context
  const [event, setEvent] = useState(null); // State to store event details
  const [loading, setLoading] = useState(true); // Loading state
  const [error, setError] = useState(null); // Error state

  // States for comments
  const [comments, setComments] = useState([]); // Event comments
  const [newComment, setNewComment] = useState(""); // New comment input
  const [captchaInput, setCaptchaInput] = useState(""); // CAPTCHA input
  const [captchaImageUrl, setCaptchaImageUrl] = useState(""); // CAPTCHA image URL
  const [commentError, setCommentError] = useState(""); // Error message for comments

  useEffect(() => {
    // Fetch event details by ID
    fetch(
      `http://localhost/prairie_circle_cms/backend/events/read.php?id=${id}`
    )
      .then((response) => {
        if (!response.ok) {
          throw new Error("Failed to fetch event details"); // Handle non-200 responses
        }
        return response.json();
      })
      .then((data) => {
        if (data && data.length > 0) {
          setEvent(data[0]); // Set event if data exists (assuming API returns an array)
        } else {
          setError("Event not found"); // Handle no matching event
        }
        setLoading(false);
      })
      .catch((error) => {
        setError(error.message || "Failed to fetch event details"); // Handle fetch error
        setLoading(false);
      });

    // Fetch comments for the event
    fetchComments();

    // Generate CAPTCHA image URL
    generateCaptcha();
  }, [id]); // Dependency array ensures fetch triggers on ID change

  // Function to fetch comments
  const fetchComments = () => {
    fetch(
      `http://localhost/prairie_circle_cms/backend/events/get_comments.php?event_id=${id}`
    )
      .then((response) => response.json())
      .then((data) => setComments(data))
      .catch((error) => console.error("Error fetching comments:", error));
  };

  // Function to generate new CAPTCHA image
  const generateCaptcha = () => {
    // Append a timestamp to prevent caching
    setCaptchaImageUrl(
      `http://localhost/prairie_circle_cms/backend/captcha.php?${Date.now()}`
    );
  };

  // Handle new comment submission
  const handleCommentSubmit = (e) => {
    e.preventDefault();

    if (!user?.id) {
      alert("You need to log in to post a comment.");
      return;
    }

    // Validate inputs
    if (!newComment.trim()) {
      setCommentError("Comment cannot be empty.");
      return;
    }
    if (!captchaInput.trim()) {
      setCommentError("CAPTCHA is required.");
      return;
    }

    // Prepare data for submission
    const commentData = {
      event_id: id,
      user_id: user.id,
      comment: newComment,
      captcha: captchaInput,
    };

    // Send comment to backend
    fetch(
      "http://localhost/prairie_circle_cms/backend/events/add_comment.php",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        credentials: "include",
        body: JSON.stringify(commentData),
      }
    )
      .then((response) => response.json())
      .then((data) => {
        if (data.error) {
          setCommentError(data.error);
        } else {
          setNewComment("");
          setCaptchaInput("");
          setCommentError("");
          fetchComments(); // Refresh comments
          generateCaptcha(); // Generate new CAPTCHA
        }
      })
      .catch((error) => {
        console.error("Error submitting comment:", error);
        setCommentError("An error occurred. Please try again.");
      });
  };

  if (loading) {
    return <p>Loading event details...</p>; // Display while loading
  }

  if (error) {
    return <p>Error: {error}</p>; // Display error if any
  }

  if (!event) {
    return <p>Event not found.</p>; // Handle missing event
  }

  return (
    <div className="event-page">
      <div className="event-details">
        <h1>{event.title}</h1>
        <div className="event-content">
          {/* Image on the left */}
          {event.image_path ? (
            <img
              src={`http://localhost/prairie_circle_cms/backend/${event.image_path}`}
              alt={event.title}
              className="event-image"
            />
          ) : (
            <div>No Image Available</div>
          )}

          {/* Details on the right */}
          <div className="event-info">
            <p>
              <strong>Date:</strong> {event.event_date}
            </p>
            <p>
              <strong>Status:</strong>{" "}
              {event.status.charAt(0).toUpperCase() + event.status.slice(1)}
            </p>
            <p>
              <strong>Category:</strong> {event.category_name}
            </p>
            <p>{event.description}</p>
            <button className="register-button">Register</button>
          </div>
        </div>
      </div>

      {/* Comments Section */}
      <div className="comments-section">
        <h2>Comments</h2>
        {comments.length > 0 ? (
          <ul className="comments-list">
            {comments.map((comment) => (
              <li key={comment.id} className="comment-item">
                <p>
                  <strong>{comment.user_name}</strong> says:
                </p>
                <p>{comment.comment}</p>
                <p className="comment-date">
                  Posted on: {new Date(comment.created_at).toLocaleString()}
                </p>
              </li>
            ))}
          </ul>
        ) : (
          <p>No comments yet. Be the first to comment!</p>
        )}

        {/* Comment Form */}
        {user?.id ? (
          <form className="comment-form" onSubmit={handleCommentSubmit}>
            <h3>Leave a Comment</h3>
            {commentError && <p className="error-message">{commentError}</p>}
            <textarea
              value={newComment}
              onChange={(e) => setNewComment(e.target.value)}
              placeholder="Your comment here..."
              required
            ></textarea>
            <div className="captcha-section">
              <img
                src={captchaImageUrl}
                alt="CAPTCHA"
                className="captcha-image"
                onClick={generateCaptcha} // Refresh CAPTCHA on click
                title="Click to refresh CAPTCHA"
              />
              <input
                type="text"
                value={captchaInput}
                onChange={(e) => setCaptchaInput(e.target.value)}
                placeholder="Enter CAPTCHA"
                required
              />
            </div>
            <button type="submit" className="submit-comment-button">
              Submit Comment
            </button>
          </form>
        ) : (
          <p>
            <em>You must be logged in to post a comment.</em>
          </p>
        )}
      </div>
    </div>
  );
}

export default EventsPage;
