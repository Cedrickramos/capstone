<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to home page
echo "<script>alert('Are you sure you want to logout?.'); 
window.location.href = 'index.php';
</script>";
// header("Location: index.php");
exit();
?>
