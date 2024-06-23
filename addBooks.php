<?php 
include "header.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a Book</title>
    <style>
        body.form-page {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            padding-top: 120px;
        }

        .form-page-header {
            text-align: center;
            color: #967f71;
            margin-top: 20px;
        }

        .add-book-form {
            width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f0e2de;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .add-book-form label {
            margin-bottom: 5px;
            color: #967f71;
        }

        .add-book-form input[type="text"],
        .add-book-form input[type="number"],
        .add-book-form input[type="file"],
        .add-book-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #967f71;
            border-radius: 5px;
            font-size: 1em;
            box-sizing: border-box;
        }

        .add-book-form input[type="file"] {
            padding: 3px;
        }

        .add-book-form textarea {
            resize: vertical;
        }

        .add-book-form .submit-btn {
            width: 100%;
            padding: 10px;
            background-color: #967f71;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-book-form .submit-btn:hover {
            background-color: #7a625b;
        }
    </style>
</head>
<body class="form-page">
    <h1 class="form-page-header">Add a Book</h1>

    <form id="addBookForm" action="addBooks_action.php" method="post" enctype="multipart/form-data" class="add-book-form">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>
        
        <label for="author">Author:</label>
        <input type="text" id="author" name="author" required>
        
        <label for="genre">Genre:</label>
        <input type="text" id="genre" name="genre" required>
        
        <label for="publishYear">Publish Year:</label>
        <input type="number" id="publishYear" name="publishYear" required>
        
        <label for="language">Language:</label>
        <input type="text" id="language" name="language" required>
        
        <label for="cover">Cover:</label>
        <input type="file" id="cover" name="cover" accept="image/*" required>
        
        <label for="synopsis">Synopsis:</label>
        <textarea id="synopsis" name="synopsis" rows="4" cols="50" required></textarea>
        
        <input type="submit" name="submit" value="Add" class="submit-btn">
    </form>
</body>
</html>

<?php 
include "footer.php";
?>
