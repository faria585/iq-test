<?php
// Include database connection
include 'db.php';

// Check if user completed the test
if (!isset($_SESSION)) {
    session_start();
}

// Get user data from sessionStorage (will be accessed via JavaScript)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IQ Test Results</title>
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
        
        /* Results Container */
        .results-container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            padding: 3rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .results-header {
            margin-bottom: 2.5rem;
        }
        
        .results-header h1 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 1rem;
        }
        
        .results-header p {
            font-size: 1.2rem;
            color: #666;
        }
        
        /* Score Display */
        .score-display {
            margin-bottom: 3rem;
        }
        
        .iq-score {
            font-size: 5rem;
            font-weight: 700;
            color: #4a6cf7;
            margin-bottom: 1rem;
            line-height: 1;
        }
        
        .score-label {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 1.5rem;
        }
        
        .score-category {
            display: inline-block;
            font-size: 1.3rem;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            margin-bottom: 1rem;
        }
        
        /* Score category colors */
        .category-below-average {
            background-color: #ffe0e0;
            color: #d32f2f;
        }
        
        .category-average {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        .category-above-average {
            background-color: #e8f5e9;
            color: #388e3c;
        }
        
        .category-superior {
            background-color: #fff8e1;
            color: #f57c00;
        }
        
        .category-genius {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }
        
        /* Performance Stats */
        .performance-stats {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .stat-item {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            min-width: 150px;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #4a6cf7;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #666;
        }
        
        /* Cognitive Profile */
        .cognitive-profile {
            margin-bottom: 3rem;
        }
        
        .section-title {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .profile-description {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #555;
            text-align: left;
            margin-bottom: 2rem;
        }
        
        /* Strengths and Weaknesses */
        .strengths-weaknesses {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .strengths, .weaknesses {
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: left;
        }
        
        .strengths h3, .weaknesses h3 {
            font-size: 1.3rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }
        
        .strengths h3 {
            color: #388e3c;
        }
        
        .weaknesses h3 {
            color: #d32f2f;
        }
        
        .strengths h3::before, .weaknesses h3::before {
            content: '';
            display: inline-block;
            width: 24px;
            height: 24px;
            margin-right: 8px;
            background-size: contain;
            background-repeat: no-repeat;
        }
        
        .strengths h3::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23388e3c'%3E%3Cpath d='M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z'/%3E%3C/svg%3E");
        }
        
        .weaknesses h3::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23d32f2f'%3E%3Cpath d='M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z'/%3E%3C/svg%3E");
        }
        
        .strengths ul, .weaknesses ul {
            list-style-type: none;
        }
        
        .strengths li, .weaknesses li {
            font-size: 1rem;
            margin-bottom: 0.8rem;
            padding-left: 1.5rem;
            position: relative;
        }
        
        .strengths li::before, .weaknesses li::before {
            content: '';
            position: absolute;
            left: 0;
            top: 6px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }
        
        .strengths li::before {
            background-color: #388e3c;
        }
        
        .weaknesses li::before {
            background-color: #d32f2f;
        }
        
        /* Recommendations */
        .recommendations {
            background-color: #f0f4ff;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 3rem;
            text-align: left;
        }
        
        .recommendations h3 {
            font-size: 1.5rem;
            color: #4a6cf7;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .recommendations p {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #555;
            margin-bottom: 1rem;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .action-btn {
            display: inline-block;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .primary-btn {
            background-color: #4a6cf7;
            color: white;
        }
        
        .primary-btn:hover {
            background-color: #3a5bd9;
            transform: translateY(-3px);
        }
        
        .secondary-btn {
            background-color: #f8f9fa;
            color: #333;
            border: 1px solid #ddd;
        }
        
        .secondary-btn:hover {
            background-color: #e9ecef;
            transform: translateY(-3px);
        }
        
        /* Share Section */
        .share-section {
            margin-bottom: 2rem;
        }
        
        .share-section h3 {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 1rem;
        }
        
        .share-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
        
        .share-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f8f9fa;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .share-btn:hover {
            transform: translateY(-3px);
        }
        
        .share-btn.facebook {
            background-color: #1877f2;
            color: white;
        }
        
        .share-btn.twitter {
            background-color: #1da1f2;
            color: white;
        }
        
        .share-btn.linkedin {
            background-color: #0077b5;
            color: white;
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
            .results-container {
                padding: 2rem 1.5rem;
            }
            
            .iq-score {
                font-size: 4rem;
            }
            
            .performance-stats {
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }
            
            .stat-item {
                width: 100%;
                max-width: 300px;
            }
            
            .strengths-weaknesses {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-btn {
                width: 100%;
                text-align: center;
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
        <div class="results-container">
            <div class="results-header">
                <h1>Your IQ Test Results</h1>
                <p>Based on your performance, we've analyzed your cognitive abilities</p>
            </div>
            
            <div class="score-display">
                <div class="iq-score" id="iq-score">--</div>
                <div class="score-label">Your IQ Score</div>
                <div class="score-category" id="score-category">Calculating...</div>
            </div>
            
            <div class="performance-stats">
                <div class="stat-item">
                    <div class="stat-value" id="correct-answers">--</div>
                    <div class="stat-label">Correct Answers</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-value" id="accuracy">--</div>
                    <div class="stat-label">Accuracy</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-value" id="time-spent">--</div>
                    <div class="stat-label">Time Spent</div>
                </div>
            </div>
            
            <div class="cognitive-profile">
                <h2 class="section-title">Your Cognitive Profile</h2>
                <p class="profile-description" id="profile-description">
                    Loading your cognitive profile...
                </p>
            </div>
            
            <div class="strengths-weaknesses">
                <div class="strengths">
                    <h3>Your Strengths</h3>
                    <ul id="strengths-list">
                        <li>Loading strengths...</li>
                    </ul>
                </div>
                
                <div class="weaknesses">
                    <h3>Areas for Improvement</h3>
                    <ul id="weaknesses-list">
                        <li>Loading areas for improvement...</li>
                    </ul>
                </div>
            </div>
            
            <div class="recommendations">
                <h3>Recommendations for Improvement</h3>
                <div id="recommendations-content">
                    <p>Loading recommendations...</p>
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="index.php" class="action-btn primary-btn">Take Test Again</a>
                <a href="#" id="download-btn" class="action-btn secondary-btn">Download Results</a>
            </div>
            
            <div class="share-section">
                <h3>Share Your Results</h3>
                <div class="share-buttons">
                    <a href="#" class="share-btn facebook">f</a>
                    <a href="#" class="share-btn twitter">t</a>
                    <a href="#" class="share-btn linkedin">in</a>
                </div>
            </div>
        </div>
    </main>
    
    <footer>
        <div class="footer-content">
            <p>&copy; 2023 IQ Master. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Function to get data from sessionStorage
        function getSessionData(key, defaultValue) {
            const value = sessionStorage.getItem(key);
            return value !== null ? value : defaultValue;
        }
        
        // Function to save user results to database via AJAX
        function saveResultsToDatabase() {
            const username = getSessionData('username', 'Anonymous');
            const email = getSessionData('email', 'anonymous@example.com');
            const iqScore = parseInt(getSessionData('iqScore', '100'));
            const correctAnswers = parseInt(getSessionData('correctAnswers', '0'));
            const totalQuestions = parseInt(getSessionData('totalQuestions', '10'));
            
            // Create form data
            const formData = new FormData();
            formData.append('username', username);
            formData.append('email', email);
            formData.append('iq_score', iqScore);
            formData.append('correct_answers', correctAnswers);
            formData.append('total_questions', totalQuestions);
            
            // Send AJAX request
            fetch('save_results.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Results saved:', data);
            })
            .catch(error => {
                console.error('Error saving results:', error);
            });
        }
        
        // Function to determine IQ category
        function getIQCategory(iqScore) {
            if (iqScore < 85) {
                return {
                    category: 'Below Average',
                    class: 'category-below-average'
                };
            } else if (iqScore >= 85 && iqScore < 115) {
                return {
                    category: 'Average',
                    class: 'category-average'
                };
            } else if (iqScore >= 115 && iqScore < 130) {
                return {
                    category: 'Above Average',
                    class: 'category-above-average'
                };
            } else if (iqScore >= 130 && iqScore < 145) {
                return {
                    category: 'Superior',
                    class: 'category-superior'
                };
            } else {
                return {
                    category: 'Genius',
                    class: 'category-genius'
                };
            }
        }
        
        // Function to generate cognitive profile
        function generateCognitiveProfile(iqScore) {
            if (iqScore < 85) {
                return "Your test results indicate that you may benefit from additional cognitive training. Your processing speed and problem-solving abilities show potential for improvement with regular practice and learning exercises.";
            } else if (iqScore >= 85 && iqScore < 115) {
                return "Your cognitive abilities fall within the average range, demonstrating balanced skills across different types of reasoning. You show solid problem-solving capabilities and can effectively process information at a standard pace.";
            } else if (iqScore >= 115 && iqScore < 130) {
                return "Your above-average cognitive abilities indicate strong analytical thinking and problem-solving skills. You demonstrate good pattern recognition and can process complex information efficiently.";
            } else if (iqScore >= 130 && iqScore < 145) {
                return "Your superior cognitive abilities showcase exceptional logical reasoning and analytical thinking. You excel at recognizing complex patterns and solving problems with a high degree of efficiency and accuracy.";
            } else {
                return "Your exceptional cognitive abilities place you in the genius category. You demonstrate remarkable pattern recognition, logical reasoning, and problem-solving capabilities that far exceed the average. Your ability to process and analyze complex information is outstanding.";
            }
        }
        
        // Function to generate strengths based on IQ score
        function generateStrengths(iqScore) {
            const baseStrengths = [
                "Pattern recognition",
                "Logical reasoning"
            ];
            
            if (iqScore >= 100) {
                baseStrengths.push("Problem-solving abilities");
            }
            
            if (iqScore >= 115) {
                baseStrengths.push("Analytical thinking");
                baseStrengths.push("Quick information processing");
            }
            
            if (iqScore >= 130) {
                baseStrengths.push("Abstract reasoning");
                baseStrengths.push("Complex problem solving");
            }
            
            return baseStrengths;
        }
        
        // Function to generate weaknesses based on IQ score
        function generateWeaknesses(iqScore) {
            let weaknesses = [];
            
            if (iqScore < 100) {
                weaknesses = [
                    "Complex problem solving",
                    "Abstract reasoning",
                    "Processing speed",
                    "Analytical thinking"
                ];
            } else if (iqScore >= 100 && iqScore < 115) {
                weaknesses = [
                    "Advanced pattern recognition",
                    "Complex abstract reasoning",
                    "Rapid information processing"
                ];
            } else if (iqScore >= 115 && iqScore < 130) {
                weaknesses = [
                    "Very complex problem solving",
                    "Advanced abstract reasoning"
                ];
            } else {
                weaknesses = [
                    "Potential overthinking of simple problems",
                    "Balancing analytical with creative thinking"
                ];
            }
            
            return weaknesses;
        }
        
        // Function to generate recommendations based on IQ score
        function generateRecommendations(iqScore) {
            let recommendations = "";
            
            if (iqScore < 100) {
                recommendations = `
                    <p>Based on your test results, here are some recommendations to enhance your cognitive abilities:</p>
                    <p>1. <strong>Regular Brain Training:</strong> Engage in daily puzzles, logic games, and brain teasers to improve your problem-solving skills.</p>
                    <p>2. <strong>Reading Comprehension:</strong> Read diverse materials and practice summarizing what you've read to enhance your verbal reasoning.</p>
                    <p>3. <strong>Number Games:</strong> Practice with sudoku, math puzzles, and number sequences to strengthen your numerical reasoning.</p>
                    <p>4. <strong>Pattern Recognition Exercises:</strong> Work with visual patterns, sequences, and categorization tasks to improve your pattern recognition abilities.</p>
                `;
            } else if (iqScore >= 100 && iqScore < 115) {
                recommendations = `
                    <p>Your average IQ score indicates balanced cognitive abilities. Here are recommendations to further enhance your skills:</p>
                    <p>1. <strong>Advanced Problem Solving:</strong> Challenge yourself with more complex puzzles and logical reasoning tasks.</p>
                    <p>2. <strong>Critical Thinking:</strong> Practice analyzing arguments, identifying assumptions, and evaluating evidence in everyday situations.</p>
                    <p>3. <strong>Memory Enhancement:</strong> Work on memory techniques like visualization, association, and spaced repetition.</p>
                    <p>4. <strong>Learning New Skills:</strong> Regularly engage in learning new subjects or skills to maintain cognitive flexibility.</p>
                `;
            } else if (iqScore >= 115 && iqScore < 130) {
                recommendations = `
                    <p>Your above-average IQ score demonstrates strong cognitive abilities. To further excel, consider these recommendations:</p>
                    <p>1. <strong>Complex Problem Solving:</strong> Seek out challenging puzzles, strategic games, and complex reasoning tasks.</p>
                    <p>2. <strong>Advanced Learning:</strong> Explore advanced topics in areas that interest you to leverage your strong analytical abilities.</p>
                    <p>3. <strong>Creative Thinking:</strong> Balance your analytical strengths with creative exercises to develop more flexible thinking.</p>
                    <p>4. <strong>Teach Others:</strong> Explaining concepts to others can deepen your understanding and refine your cognitive processes.</p>
                `;
            } else {
                recommendations = `
                    <p>Your exceptional IQ score indicates superior cognitive abilities. To maximize your potential, consider these recommendations:</p>
                    <p>1. <strong>Intellectual Challenges:</strong> Continuously seek out the most challenging intellectual problems in your areas of interest.</p>
                    <p>2. <strong>Interdisciplinary Learning:</strong> Connect knowledge across different domains to develop unique insights and perspectives.</p>
                    <p>3. <strong>Creative Problem Solving:</strong> Apply your analytical strengths to creative endeavors and innovation.</p>
                    <p>4. <strong>Mentorship:</strong> Consider mentoring others to share your knowledge while reinforcing your own understanding.</p>
                    <p>5. <strong>Balance:</strong> Ensure you balance intellectual pursuits with emotional intelligence and social connections.</p>
                `;
            }
            
            return recommendations;
        }
        
        // Function to format time
        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            return `${minutes}m ${remainingSeconds}s`;
        }
        
        // Function to populate results page
        function populateResults() {
            // Get data from sessionStorage
            const iqScore = parseInt(getSessionData('iqScore', '100'));
            const correctAnswers = parseInt(getSessionData('correctAnswers', '0'));
            const totalQuestions = parseInt(getSessionData('totalQuestions', '10'));
            const timeSpent = parseInt(getSessionData('timeSpent', '0'));
            
            // Calculate accuracy
            const accuracy = Math.round((correctAnswers / totalQuestions) * 100);
            
            // Update DOM elements
            document.getElementById('iq-score').textContent = iqScore;
            document.getElementById('correct-answers').textContent = `${correctAnswers}/${totalQuestions}`;
            document.getElementById('accuracy').textContent = `${accuracy}%`;
            document.getElementById('time-spent').textContent = formatTime(timeSpent);
            
            // Set IQ category
            const iqCategory = getIQCategory(iqScore);
            const categoryElement = document.getElementById('score-category');
            categoryElement.textContent = iqCategory.category;
            categoryElement.className = `score-category ${iqCategory.class}`;
            
            // Set cognitive profile
            document.getElementById('profile-description').textContent = generateCognitiveProfile(iqScore);
            
            // Set strengths
            const strengthsList = document.getElementById('strengths-list');
            strengthsList.innerHTML = '';
            generateStrengths(iqScore).forEach(strength => {
                const li = document.createElement('li');
                li.textContent = strength;
                strengthsList.appendChild(li);
            });
            
            // Set weaknesses
            const weaknessesList = document.getElementById('weaknesses-list');
            weaknessesList.innerHTML = '';
            generateWeaknesses(iqScore).forEach(weakness => {
                const li = document.createElement('li');
                li.textContent = weakness;
                weaknessesList.appendChild(li);
            });
            
            // Set recommendations
            document.getElementById('recommendations-content').innerHTML = generateRecommendations(iqScore);
            
            // Save results to database
            saveResultsToDatabase();
            
            // Set up download button
            document.getElementById('download-btn').addEventListener('click', function(e) {
                e.preventDefault();
                alert('Your results have been prepared for download. The download will start shortly.');
                // In a real application, this would generate a PDF or other document
            });
        }
        
        // Check if user completed the test
        window.addEventListener('load', function() {
            // Check if user info exists in sessionStorage
            if (!sessionStorage.getItem('username') || !sessionStorage.getItem('email') || !sessionStorage.getItem('iqScore')) {
                // Redirect back to index if user info is missing
                window.location.href = 'index.php';
                return;
            }
            
            // Populate results
            populateResults();
        });
    </script>
</body>
</html>
