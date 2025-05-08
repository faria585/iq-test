<?php
// Include database connection
include 'db.php';

// Fetch questions from database
$query = "SELECT q.question_id, q.question_text, q.question_type, q.difficulty_level, q.image_path 
          FROM questions q 
          ORDER BY q.question_id";
$result = $conn->query($query);

$questions = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $questionId = $row['question_id'];
        
        // Fetch answers for this question
        $answersQuery = "SELECT answer_id, answer_text, is_correct 
                         FROM answers 
                         WHERE question_id = $questionId";
        $answersResult = $conn->query($answersQuery);
        
        $answers = [];
        if ($answersResult->num_rows > 0) {
            while($answerRow = $answersResult->fetch_assoc()) {
                $answers[] = $answerRow;
            }
        }
        
        $row['answers'] = $answers;
        $questions[] = $row;
    }
}

// Convert questions array to JSON for JavaScript
$questionsJson = json_encode($questions);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IQ Test - Questions</title>
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: #333;
        }
        
        /* Header Styles */
        header {
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 1.5rem 0;
            text-align: center;
        }
        
        .logo {
            font-size: 2.5rem;
            font-weight: 700;
            color: #4a6cf7;
            margin-bottom: 0.5rem;
        }
        
        .tagline {
            font-size: 1.1rem;
            color: #666;
        }
        
        /* Main Content Styles */
        main {
            flex: 1;
            padding: 3rem 1.5rem;
            max-width: 900px;
            margin: 0 auto;
            width: 100%;
        }
        
        /* Quiz Container */
        .quiz-container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            padding: 2.5rem;
            margin-bottom: 2rem;
        }
        
        .quiz-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .quiz-header h1 {
            font-size: 2.2rem;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .quiz-header p {
            color: #666;
            font-size: 1.1rem;
        }
        
        /* Progress Bar */
        .progress-container {
            margin-bottom: 2rem;
        }
        
        .progress-text {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        
        .progress-text span {
            font-size: 0.9rem;
            color: #666;
        }
        
        .progress-bar {
            height: 8px;
            background-color: #e0e0e0;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background-color: #4a6cf7;
            width: 0%;
            transition: width 0.3s ease;
        }
        
        /* Question Styles */
        .question-container {
            margin-bottom: 2rem;
        }
        
        .question-text {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }
        
        .question-type {
            display: inline-block;
            background-color: #f0f4ff;
            color: #4a6cf7;
            font-size: 0.8rem;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            margin-bottom: 1rem;
        }
        
        .question-image {
            max-width: 100%;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        /* Answer Options */
        .answer-options {
            display: grid;
            gap: 1rem;
        }
        
        .answer-option {
            background-color: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 1rem 1.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }
        
        .answer-option:hover {
            background-color: #f0f4ff;
            border-color: #d0d9ff;
        }
        
        .answer-option.selected {
            background-color: #e0e7ff;
            border-color: #4a6cf7;
        }
        
        .answer-text {
            font-size: 1.1rem;
            color: #333;
        }
        
        /* Navigation Buttons */
        .quiz-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }
        
        .nav-btn {
            background-color: #4a6cf7;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .nav-btn:hover {
            background-color: #3a5bd9;
        }
        
        .nav-btn:disabled {
            background-color: #c3cfe2;
            cursor: not-allowed;
        }
        
        .nav-btn.prev-btn {
            background-color: #f8f9fa;
            color: #333;
            border: 1px solid #ddd;
        }
        
        .nav-btn.prev-btn:hover {
            background-color: #e9ecef;
        }
        
        /* Timer */
        .timer-container {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .timer {
            font-size: 1.5rem;
            font-weight: 700;
            color: #4a6cf7;
        }
        
        /* Footer Styles */
        footer {
            background-color: #fff;
            padding: 2rem 0;
            text-align: center;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.05);
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        .footer-content p {
            color: #666;
            font-size: 1rem;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .quiz-container {
                padding: 2rem 1.5rem;
            }
            
            .quiz-header h1 {
                font-size: 1.8rem;
            }
            
            .question-text {
                font-size: 1.2rem;
            }
            
            .answer-text {
                font-size: 1rem;
            }
        }
        
        @media (max-width: 480px) {
            .quiz-header h1 {
                font-size: 1.6rem;
            }
            
            .nav-btn {
                padding: 0.7rem 1.2rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">IQ Master</div>
        <div class="tagline">Discover Your Cognitive Potential</div>
    </header>
    
    <main>
        <div class="quiz-container">
            <div class="quiz-header">
                <h1>IQ Assessment Test</h1>
                <p>Answer each question to the best of your ability</p>
            </div>
            
            <div class="timer-container">
                <div class="timer" id="timer">20:00</div>
            </div>
            
            <div class="progress-container">
                <div class="progress-text">
                    <span id="question-number">Question 1 of 10</span>
                    <span id="progress-percentage">10% Complete</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" id="progress-fill"></div>
                </div>
            </div>
            
            <div id="question-container" class="question-container">
                <!-- Question content will be loaded dynamically -->
            </div>
            
            <div class="quiz-navigation">
                <button id="prev-btn" class="nav-btn prev-btn" disabled>Previous</button>
                <button id="next-btn" class="nav-btn next-btn">Next</button>
            </div>
        </div>
    </main>
    
    <footer>
        <div class="footer-content">
            <p>&copy; 2023 IQ Master. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Get questions from PHP
        const questions = <?php echo $questionsJson; ?>;
        
        // Variables to track quiz state
        let currentQuestionIndex = 0;
        let userAnswers = new Array(questions.length).fill(null);
        let timerInterval;
        let timeLeft = 20 * 60; // 20 minutes in seconds
        
        // DOM elements
        const questionContainer = document.getElementById('question-container');
        const questionNumber = document.getElementById('question-number');
        const progressPercentage = document.getElementById('progress-percentage');
        const progressFill = document.getElementById('progress-fill');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const timerElement = document.getElementById('timer');
        
        // Initialize quiz
        function initQuiz() {
            // Check if user info exists in sessionStorage
            if (!sessionStorage.getItem('username') || !sessionStorage.getItem('email')) {
                // Redirect back to index if user info is missing
                window.location.href = 'index.php';
                return;
            }
            
            // Load first question
            loadQuestion(currentQuestionIndex);
            
            // Start timer
            startTimer();
            
            // Add event listeners
            prevBtn.addEventListener('click', goToPreviousQuestion);
            nextBtn.addEventListener('click', goToNextQuestion);
        }
        
        // Load question
        function loadQuestion(index) {
            const question = questions[index];
            
            // Update question number and progress
            questionNumber.textContent = `Question ${index + 1} of ${questions.length}`;
            const percent = ((index + 1) / questions.length) * 100;
            progressPercentage.textContent = `${Math.round(percent)}% Complete`;
            progressFill.style.width = `${percent}%`;
            
            // Create question HTML
            let questionHTML = `
                <div class="question-type">${capitalizeFirstLetter(question.question_type)}</div>
                <div class="question-text">${question.question_text}</div>
            `;
            
            // Add image if available
            if (question.image_path) {
                questionHTML += `<img src="${question.image_path}" alt="Question Image" class="question-image">`;
            }
            
            // Add answer options
            questionHTML += '<div class="answer-options">';
            
            question.answers.forEach((answer, answerIndex) => {
                const isSelected = userAnswers[index] === answerIndex;
                questionHTML += `
                    <div class="answer-option ${isSelected ? 'selected' : ''}" data-index="${answerIndex}">
                        <div class="answer-text">${answer.answer_text}</div>
                    </div>
                `;
            });
            
            questionHTML += '</div>';
            
            // Set question HTML
            questionContainer.innerHTML = questionHTML;
            
            // Add click event listeners to answer options
            const answerOptions = document.querySelectorAll('.answer-option');
            answerOptions.forEach(option => {
                option.addEventListener('click', selectAnswer);
            });
            
            // Update navigation buttons
            prevBtn.disabled = index === 0;
            
            if (index === questions.length - 1) {
                nextBtn.textContent = 'Finish Test';
            } else {
                nextBtn.textContent = 'Next';
            }
        }
        
        // Select answer
        function selectAnswer(e) {
            const selectedOption = e.currentTarget;
            const answerIndex = parseInt(selectedOption.dataset.index);
            
            // Update user answers
            userAnswers[currentQuestionIndex] = answerIndex;
            
            // Update UI
            const answerOptions = document.querySelectorAll('.answer-option');
            answerOptions.forEach(option => {
                option.classList.remove('selected');
            });
            
            selectedOption.classList.add('selected');
        }
        
        // Go to previous question
        function goToPreviousQuestion() {
            if (currentQuestionIndex > 0) {
                currentQuestionIndex--;
                loadQuestion(currentQuestionIndex);
            }
        }
        
        // Go to next question
        function goToNextQuestion() {
            if (currentQuestionIndex < questions.length - 1) {
                currentQuestionIndex++;
                loadQuestion(currentQuestionIndex);
            } else {
                // End of quiz
                finishQuiz();
            }
        }
        
        // Finish quiz
        function finishQuiz() {
            // Stop timer
            clearInterval(timerInterval);
            
            // Calculate score
            let correctAnswers = 0;
            
            for (let i = 0; i < questions.length; i++) {
                const question = questions[i];
                const userAnswerIndex = userAnswers[i];
                
                if (userAnswerIndex !== null) {
                    const selectedAnswer = question.answers[userAnswerIndex];
                    if (selectedAnswer.is_correct) {
                        correctAnswers++;
                    }
                }
            }
            
            // Calculate IQ score (simplified formula)
            const baseIQ = 100;
            const maxScore = questions.length;
            const iqScore = Math.round(baseIQ + (correctAnswers / maxScore) * 50);
            
            // Store results in sessionStorage
            sessionStorage.setItem('correctAnswers', correctAnswers);
            sessionStorage.setItem('totalQuestions', questions.length);
            sessionStorage.setItem('iqScore', iqScore);
            sessionStorage.setItem('timeSpent', (20 * 60) - timeLeft);
            
            // Redirect to results page
            window.location.href = 'results.php';
        }
        
        // Timer functions
        function startTimer() {
            timerInterval = setInterval(updateTimer, 1000);
        }
        
        function updateTimer() {
            timeLeft--;
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                finishQuiz();
            }
            
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            // Change color when time is running out
            if (timeLeft < 60) {
                timerElement.style.color = '#ff4d4d';
            }
        }
        
        // Helper function to capitalize first letter
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
        
        // Initialize quiz when page loads
        window.addEventListener('load', initQuiz);
    </script>
</body>
</html>
