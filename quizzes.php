<?php
    include "header.php";
?>
<body>
    <div class="container">
        <h1>Unity Quizzes</h1>
        <div class="quiz-container">
            <div class="quiz">
                <h2>Quiz 1: Unity Basics</h2>
                <p>Question: What is the main scripting language used in Unity?</p>
                <input type="text" id="quiz1-answer" placeholder="Your answer here">
                <button onclick="checkQuiz1Answer()">Submit</button>
                <p id="quiz1-feedback"></p>
            </div>
            <div class="quiz">
                <h2>Quiz 2: Unity Components</h2>
                <p>Question: Name a component that is essential for displaying 3D objects in Unity.</p>
                <input type="text" id="quiz2-answer" placeholder="Your answer here">
                <button onclick="checkQuiz2Answer()">Submit</button>
                <p id="quiz2-feedback"></p>
            </div>
        </div>
    </div>

    <script>
        function checkQuiz1Answer() {
            var answer = document.getElementById('quiz1-answer').value.toLowerCase();
            var feedback = document.getElementById('quiz1-feedback');
            if (answer === 'c#' || answer === 'c sharp' || answer === 'csharp') {
                feedback.textContent = 'Correct!';
                feedback.style.color = 'green';
            } else {
                feedback.textContent = 'Incorrect, the correct answer is C#.';
                feedback.style.color = 'red';
            }
        }

        function checkQuiz2Answer() {
            var answer = document.getElementById('quiz2-answer').value.toLowerCase();
            var feedback = document.getElementById('quiz2-feedback');
            if (answer === 'mesh renderer' || answer === 'renderer') {
                feedback.textContent = 'Correct!';
                feedback.style.color = 'green';
            } else {
                feedback.textContent = 'Incorrect, the correct answer is Mesh Renderer.';
                feedback.style.color = 'red';
            }
        }
    </script>
</body>
<?php
    include "footer.php"
?>