<?php 
include "header.php";
?>

<body class="form-page">
    <h1>Add a book</h1>

    <form id="addBookForm" action="addBooks_action.php" method="post">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br>
        
        <label for="author">Author:</label><br>
        <input type="text" id="author" name="author" required><br>
        
        <label for="genre">Genre:</label><br>
        <input type="text" id="genre" name="genre" required><br>
        
        <label for="publishYear">Publish Year:</label><br>
        <input type="number" id="publishYear" name="publishYear" required><br>
        
        <label for="language">Language:</label><br>
        <input type="text" id="language" name="language" required><br>
        
        <label for="cover">Cover:</label><br>
        <input type="text" id="cover" name="cover" required><br>
        
        <label for="synopsis">Synopsis:</label><br>
        <textarea id="synopsis" name="synopsis" rows="4" cols="50" required></textarea><br>
        
        <input type="submit" name="submit" value="Add Book" class="submit-btn">
    </form>
</body>

<?php 
include "footer.php";
?>