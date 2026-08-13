<?php

$conn = new mysqli('db', 'admin', 'event123', 'event_db');

// Check connection
if ($conn->connect_errno) {
  echo "Failed to connect to MySQL: " . $conn->connect_error;
  exit();
}
