<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include_once 'Student.php';

$db = new mysqli("localhost", "root", "", "my_database");
$student = new Student($db);

// Get counts for the dashboard cards
$total_students = $db->query("SELECT COUNT(*) as total FROM student_tbl")->fetch_assoc()['total'];
$total_depts = $db->query("SELECT COUNT(DISTINCT stud_dep) as total FROM student_tbl")->fetch_assoc()['total'];

$update_mode = false;
$id = $name = $age = $dep = "";

// Handle Logic (Delete/Edit/Save)
if (isset($_GET['delete'])) {
    $student->id = $_GET['delete'];
    $student->delete();
    header("Location: index.php");
}

if (isset($_GET['edit'])) {
    $update_mode = true;
    $student->id = $_GET['edit'];
    $row = $student->readOne();
    if ($row) {
        $id = $row['stud_id']; $name = $row['stud_name']; $age = $row['stud_age']; $dep = $row['stud_dep'];
    }
}

if (isset($_POST['save'])) {
    $student->name = $_POST['stud_name'];
    $student->age = $_POST['stud_age'];
    $student->dep = $_POST['stud_dep'];
    if (!empty($_POST['stud_id'])) {
        $student->id = $_POST['stud_id'];
        $student->update();
    } else {
        $student->create();
    }
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student System Dashboard</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; margin: 0; display: flex; height: 100vh; background: #f4f7f6; }
        
        /* Sidebar */
        .sidebar { width: 250px; background: #2c3e50; color: white; padding: 20px; display: flex; flex-direction: column; }
        .sidebar h2 { font-size: 18px; border-bottom: 1px solid #555; padding-bottom: 10px; }
        .sidebar a { color: #adb5bd; text-decoration: none; padding: 10px 0; display: block; }
        .sidebar a:hover { color: white; }
        .logout-btn { margin-top: auto; color: #ff6b6b !important; font-weight: bold; }

        /* Main Content */
        .main-content { flex: 1; overflow-y: auto; padding: 30px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
        /* Stats Cards */
        .stats-container { display: flex; gap: 20px; margin-bottom: 30px; }
        .card { background: white; padding: 20px; border-radius: 8px; flex: 1; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .card h3 { margin: 0; color: #7f8c8d; font-size: 14px; text-transform: uppercase; }
        .card p { margin: 10px 0 0; font-size: 24px; font-weight: bold; color: #2c3e50; }

        /* Form and Table Section */
        .content-box { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        form { display: flex; gap: 10px; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee; }
        input { padding: 10px; border: 1px solid #ddd; border-radius: 4px; flex: 1; }
        .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; color: white; }
        .btn-add { background: #27ae60; }
        .btn-update { background: #2980b9; }
        
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; background: #f8f9fa; padding: 12px; border-bottom: 2px solid #eee; }
        td { padding: 12px; border-bottom: 1px solid #eee; }
        .edit { color: #f39c12; text-decoration: none; font-weight: bold; }
        .delete { color: #e74c3c; text-decoration: none; margin-left: 10px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>SMS Admin</h2>
    <a href="index.php">Dashboard</a>
    <a href="#">Reports (Coming Soon)</a>
    <a href="#">Settings</a>
    <a href="logout.php" class="logout-btn">Sign Out</a>
</div>

<div class="main-content">
    <div class="header">
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        <p><?php echo date('F d, Y'); ?></p>
    </div>

    <div class="stats-container">
        <div class="card">
            <h3>Total Students</h3>
            <p><?php echo $total_students; ?></p>
        </div>
        <div class="card">
            <h3>Departments</h3>
            <p><?php echo $total_depts; ?></p>
        </div>
        <div class="card">
            <h3>System Status</h3>
            <p style="color: #27ae60; font-size: 16px;">Online</p>
        </div>
    </div>

    <div class="content-box">
        <form method="POST">
            <input type="hidden" name="stud_id" value="<?php echo $id; ?>">
            <input type="text" name="stud_name" placeholder="Student Name" value="<?php echo $name; ?>" required>
            <input type="number" name="stud_age" placeholder="Age" value="<?php echo $age; ?>" required>
            <input type="text" name="stud_dep" placeholder="Department" value="<?php echo $dep; ?>" required>
            
            <?php if ($update_mode): ?>
                <button type="submit" name="save" class="btn btn-update">Update</button>
                <a href="index.php" style="margin-top: 10px; display: inline-block;">Cancel</a>
            <?php else: ?>
                <button type="submit" name="save" class="btn btn-add">Add Student</button>
            <?php endif; ?>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Department</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $res = $student->read();
                while($row = $res->fetch_assoc()){
                    echo "<tr>
                            <td>#{$row['stud_id']}</td>
                            <td>{$row['stud_name']}</td>
                            <td>{$row['stud_age']}</td>
                            <td>{$row['stud_dep']}</td>
                            <td>
                                <a href='?edit={$row['stud_id']}' class='edit'>Edit</a>
                                <a href='?delete={$row['stud_id']}' class='delete' onclick='return confirm(\"Delete this record?\")'>Delete</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>