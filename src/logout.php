<?php
session_unset();
session_destroy();
header("Location: http://localhost:8081/homepg.php");
