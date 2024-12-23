<?php
$ftp_server = ""; // Replace with your FTP server
$ftp_user = "  ";    // Replace with your username
$ftp_pass = "";    // Replace with your password
$ftp_dir = "/";  // Adjust if needed

$local_file = $_FILES['file']['tmp_name'];
$remote_file = $ftp_dir . '/' . basename($_FILES['file']['name']);

// Debug: Check file upload success
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    die("Error: File upload failed. Error code: " . $_FILES['file']['error']);
}

// Debug: Check local file exists
if (!file_exists($local_file)) {
    die("Error: The local file does not exist: " . $local_file);
}

// Connect to FTP server
$conn_id = ftp_connect($ftp_server);
if (!$conn_id) {
    die("Error: Could not connect to FTP server.");
}

// Login to FTP server
$login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);
if (!$login_result) {
    die("Error: FTP login failed.");
}

// Debug: Current FTP directory
$current_dir = ftp_pwd($conn_id);
echo "Current FTP Directory: " . $current_dir;

// Debug: Check and change to remote directory
if (!ftp_chdir($conn_id, $ftp_dir)) {
    die("Error: Cannot change to the remote directory: " . $ftp_dir . " | Current FTP Directory: " . $current_dir);
}

// Enable passive mode if needed
ftp_pasv($conn_id, true);

// Upload the file
if (ftp_put($conn_id, $remote_file, $local_file, FTP_BINARY)) {
    echo "File uploaded successfully!";
} else {
    die("Error: FTP upload failed. Check permissions or directory paths.");
}

// Close the connection
ftp_close($conn_id);
?>