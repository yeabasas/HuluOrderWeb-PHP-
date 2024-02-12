<?php
session_start();
session_unset();
session_destroy();
header('location:index.php');

?> 

<script>
    // app.js
document.addEventListener("DOMContentLoaded", function () {
  // Function to handle sign out button click
  document.getElementById("logout").addEventListener("click", function () {
    signOut();
  });

  // Set a timeout to automatically sign out after 30 minutes of inactivity
  const inactivityTimeout = 30 * 60 * 1000; // 30 minutes in milliseconds
  let inactivityTimer;

  function resetInactivityTimer() {
    clearTimeout(inactivityTimer);
    inactivityTimer = setTimeout(function () {
      signOut();
      
    }, inactivityTimeout);
  }

  // Event listeners for user activity
  document.addEventListener("mousemove", resetInactivityTimer);
  document.addEventListener("keypress", resetInactivityTimer);
});

// Function to simulate signing out
function signOut() {
  // You can make an API call to the server to handle the sign-out logic
  // For simplicity, we'll just reload the page here
  location.reload();
}
</script>