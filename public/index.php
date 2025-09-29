<?php
require_once __DIR__ . '/../bootstrap.php';

use App\Controller\UserController;

$userController = new UserController($entityManager);

// Form Processes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $userController->create($_POST['username']);
    } elseif (isset($_POST['update'])) {
        $userController->update((int)$_POST['id'], $_POST['username']);
    } elseif (isset($_POST['delete'])) {
        $userController->delete((int)$_POST['id']);
    }
}

// get Users List
$allUsers = $userController->getAll();
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>Users Management  - CMS</title>
    <style>
        body { font-family: Tahoma, sans-serif; direction: rtl; margin: 20px; }
        form { margin-bottom: 20px; }
        input { margin: 5px; padding: 5px; }
        table { border-collapse: collapse; width: 50%; margin-top: 20px; }
        th, td { border: 1px solid #aaa; padding: 8px; text-align: center; }
    </style>
</head>
<body>
    <h1>Users Managemet</h1>

    <!-- User Create Form -->
    <h3>Create New User</h3>
    <form method="post">
        <input type="text" name="username" placeholder="username" required>
        <button type="submit" name="create">Create</button>
    </form>

    <!-- User Update Form -->
    <h3>Update User</h3>
    <form method="post">
        <input type="number" name="id" placeholder="UserID" required>
        <input type="text" name="username" placeholder="New Username" required>
        <button type="submit" name="update">Update</button>
    </form>

    <!-- User Delete Form -->
    <h3>Delete User</h3>
    <form method="post">
        <input type="number" name="id" placeholder="UserID" required>
        <button type="submit" name="delete">Delete</button>
    </form>

    <!-- Users List Table -->
    <h3>Users List</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
        </tr>
        <?php foreach ($allUsers as $user): ?>
            <tr>
                <td><?= $user->getId(); ?></td>
                <td><?= $user->getUsername(); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
