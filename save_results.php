<?php
// Include database connection
include 'db.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = isset($_POST['username']) ? $_POST['username'] : 'Anonymous';
    $email = isset($_POST['email']) ? $_POST['email'] : 'anonymous@example.com';
    $iqScore = isset($_POST['iq_score']) ? intval($_POST['iq_score']) : 100;
    $correctAnswers = isset($_POST['correct_answers']) ? intval($_POST['correct_answers']) : 0;
    $totalQuestions = isset($_POST['total_questions']) ? intval($_POST['total_questions']) : 10;
    
    // Sanitize inputs
    $username = $conn->real_escape_string($username);
    $email = $conn->real_escape_string($email);
    
    // Insert user if not exists
    $userQuery = "INSERT INTO users (username, email) VALUES ('$username', '$email')";
    $conn->query($userQuery);
    
    // Get user ID
    $userId = $conn->insert_id;
    
    // If user already exists, get their ID
    if ($userId === 0) {
        $getUserQuery = "SELECT user_id FROM users WHERE email = '$email' LIMIT 1";
        $userResult = $conn->query($getUserQuery);
        
        if ($userResult->num_rows > 0) {
            $userData = $userResult->fetch_assoc();
            $userId = $userData['user_id'];
        }
    }
    
    // Insert results
    $resultsQuery = "INSERT INTO results (user_id, score, total_questions, iq_score) 
                    VALUES ($userId, $correctAnswers, $totalQuestions, $iqScore)";
    
    if ($conn->query($resultsQuery) === TRUE) {
        // Success
        $response = [
            'success' => true,
            'message' => 'Results saved successfully',
            'result_id' => $conn->insert_id
        ];
    } else {
        // Error
        $response = [
            'success' => false,
            'message' => 'Error saving results: ' . $conn->error
        ];
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Not a POST request
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: application/json');
    
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
}

// Close connection
$conn->close();
?>
