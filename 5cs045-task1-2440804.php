
<?php
$host = 'mi-linux.wlv.ac.uk';
$dbname = 'db2440804';
$username = '2440804';
$password = 'Uttamsingh@123';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    $sql = "SELECT Book_name, Genre, Price, Date_of_release
            FROM books
            ORDER BY Book_id";

    $stmt = $pdo->query($sql);
    $books = $stmt->fetchAll();

} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit('Unable to connect to the database. Please try again later.');
}

function escapeHtml($value) {
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f5f5f5;
        }

        h1 {
            color: #243b53;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }

        th {
            background: #243b53;
            color: white;
        }

        tr:nth-child(even) {
            background: #eeeeee;
        }
    </style>
</head>
<body>

<h1>Book List</h1>

<table>
    <thead>
        <tr>
            <th>Book name</th>
            <th>Genre</th>
            <th>Price</th>
            <th>Date of release</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><?= escapeHtml($book['Book_name']) ?></td>
                <td><?= escapeHtml($book['Genre']) ?></td>
                <td>£<?= number_format((float)$book['Price'], 2) ?></td>
                <td>
                    <?= date(
                        'd/m/Y',
                        strtotime($book['Date_of_release'])
                    ) ?>
                </td>
            </tr>
        <?php endforeach; ?>

        <?php if (count($books) === 0): ?>
            <tr>
                <td colspan="4">No books found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
