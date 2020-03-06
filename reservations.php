<?php 
include('templates/header.php');
include('dbconfig.php');

$query = 'SELECT students.umid, students.first_name, students.last_name, students.project_title, students.email, students.phone, timeslots.title 
          FROM students
          JOIN timeslots ON timeslots.id = students.timeslot_id';
$result = $connection->query($query);
$resultArr = $result->fetch_all(MYSQLI_ASSOC);
?>

<?php if (isset($_SESSION['success'])) { ?>
    <div class='success-msg'>
        <span style='position: absolute; left: 15px; cursor: pointer;' onclick='closeMsg(this)'>X</span>
        Success: <?php echo $_SESSION['success'] ?>
    </div>
    <?php unset($_SESSION['success']) ?>
<?php } ?>

<div style='padding: 10px; overflow: auto'>
<table>
    <thead>
        <tr>
            <th>UMID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Project Title</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Timeslot</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($resultArr as $row) { ?>
            <tr>
                <td><?php echo $row['umid']?></td>
                <td><?php echo $row['first_name']?></td>
                <td><?php echo $row['last_name']?></td>
                <td><?php echo $row['project_title']?></td>
                <td><?php echo $row['email']?></td>
                <td><?php echo $row['phone']?></td>
                <td><?php echo $row['title']?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
</div>

<?php include('templates/footer.php'); ?>