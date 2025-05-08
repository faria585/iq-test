<?php
// Include database connection
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IQ Test - Measure Your Intelligence</title>
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
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }
        
        .hero {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            padding: 3rem;
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .hero h1 {
            font-size: 2.8rem;
            margin-bottom: 1.5rem;
            color: #333;
        }
        
        .hero p {
            font-size: 1.2rem;
            line-height: 1.6;
            color: #555;
            margin-bottom: 2rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .start-btn {
            display: inline-block;
            background-color: #4a6cf7;
            color: white;
            font-size: 1.2rem;
            font-weight: 600;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(74, 108, 247, 0.3);
        }
        
        .start-btn:hover {
            background-color: #3a5bd9;
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(74, 108, 247, 0.4);
        }
        
        /* Features Section */
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .feature-card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            color: #4a6cf7;
        }
        
        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #333;
        }
        
        .feature-card p {
            font-size: 1rem;
            line-height: 1.5;
            color: #666;
        }
        
        /* Info Section */
        .info-section {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            padding: 3rem;
            margin-bottom: 3rem;
        }
        
        .info-section h2 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: #333;
            text-align: center;
        }
        
        .info-section p {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #555;
            margin-bottom: 1rem;
        }
        
        /* Form Styles */
        .user-form {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            margin-bottom: 3rem;
        }
        
        .user-form h2 {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            color: #333;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            font-size: 1rem;
            margin-bottom: 0.5rem;
            color: #555;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #4a6cf7;
        }
        
        .submit-btn {
            display: block;
            width: 100%;
            background-color: #4a6cf7;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            padding: 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .submit-btn:hover {
            background-color: #3a5bd9;
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
            .hero {
                padding: 2rem 1.5rem;
            }
            
            .hero h1 {
                font-size: 2.2rem;
            }
            
            .hero p {
                font-size: 1.1rem;
            }
            
            .info-section, .user-form {
                padding: 2rem 1.5rem;
            }
        }
        
        @media (max-width: 480px) {
            .hero h1 {
                font-size: 1.8rem;
            }
            
            .start-btn {
                padding: 0.8rem 2rem;
                font-size: 1.1rem;
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
        <section class="hero">
            <h1>Measure Your Intelligence Quotient</h1>
            <p>Take our scientifically designed IQ test to assess your logical reasoning, pattern recognition, and problem-solving abilities. Discover your cognitive strengths and areas for improvement.</p>
            <a href="#user-form" class="start-btn">Start Your IQ Test</a>
        </section>
        
        <section class="features">
            <div class="feature-card">
                <div class="feature-icon">🧠</div>
                <h3>Comprehensive Assessment</h3>
                <p>Our test evaluates multiple cognitive domains including logical reasoning, numerical ability, and pattern recognition.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Detailed Results</h3>
                <p>Receive a comprehensive breakdown of your performance across different cognitive areas with personalized insights.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">⏱️</div>
                <h3>Quick & Accurate</h3>
                <p>Complete the test in just 15-20 minutes and get immediate results based on standardized scoring methods.</p>
            </div>
        </section>
        
        <section class="info-section">
            <h2>About IQ Testing</h2>
            <p>Intelligence Quotient (IQ) is a measure of a person's reasoning ability. It is meant to gauge how well someone can use information and logic to answer questions or make predictions.</p>
            <p>IQ tests begin to assess this by measuring short- and long-term memory. They also measure how well people can solve puzzles and recall information they've heard — and how quickly.</p>
            <p>Our test is designed to provide an accurate assessment of your cognitive abilities across multiple domains, giving you valuable insights into your intellectual strengths.</p>
        </section>
        
        <section id="user-form" class="user-form">
            <h2>Begin Your IQ Test</h2>
            <form id="startForm">
                <div class="form-group">
                    <label for="username">Your Name</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <button type="submit" class="submit-btn">Start Test Now</button>
            </form>
        </section>
    </main>
    
    <footer>
        <div class="footer-content">
            <p>&copy; 2023 IQ Master. All rights reserved.</p>
        </div>
    </footer>

    <script>
        document.getElementById('startForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            
            // Store in sessionStorage for use in quiz.php
            sessionStorage.setItem('username', username);
            sessionStorage.setItem('email', email);
            
            // Redirect to quiz page
            window.location.href = 'quiz.php';
        });
    </script>
</body>
</html>
