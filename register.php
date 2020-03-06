<?php
include('dbconfig.php');
include('templates/header.php');

//get all timeslots
$query = 'SELECT * FROM timeslots';
$result = $connection->query($query);
$resultArr = $result->fetch_all(MYSQLI_ASSOC);

//form validation
$umid = $first_name = $last_name = $project_title = $phone = $email = $id = '';
$errors = [];
$umidExists = false;

if (isset($_POST['register']) || isset($_POST['update'])) {
    $umid = $_POST['umid'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $project_title = trim($_POST['project_title']);
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $id = empty($_POST['timeslot']) ? '' : $_POST['timeslot'];

    //validate each field
    if (!preg_match('/^\d{8}$/', $umid)) array_push($errors, 'UMID must be 8 digits.');
    if (!preg_match('/^\d\d\d-\d\d\d-\d\d\d\d$/', $phone)) array_push($errors, 'Phone must follow format: ###-###-####');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) array_push($errors, 'Email is not valid.');
    if (strlen($first_name) > 20 || empty($first_name)) array_push($errors, 'First name must be at least 1 character and must be less than 21 characters.');
    if (strlen($last_name) > 20 || empty($last_name)) array_push($errors, 'Last name must be at last 1 character and must be less than 21 characters.');
    if (strlen($project_title) > 50 || empty($project_title)) array_push($errors, 'Project title must be at least 1 character and must be less than 51 characters.');
    if (empty($id)) array_push($errors, 'A timeslot must be selected.');

    //proceed to post if no errors exist
    if (count($errors) === 0) {
        $id = $_POST['timeslot'];
        //check if timeslot has slots
        $query = $connection->prepare('SELECT * FROM timeslots WHERE id = ?');
        $query->bind_param('i', $id);
        $query->execute();
        $timeslot = $query->get_result()->fetch_all(MYSQLI_ASSOC);
        if ($timeslot[0]['seats_remaining'] > 0) {
            //check if user wants to update timeslot, or add if umid doesn't already exist
            $query = $connection->prepare('SELECT * FROM students WHERE umid = ?');
            $query->bind_param('s', $umid);
            $query->execute();
            $student = $query->get_result()->fetch_all(MYSQLI_ASSOC);
            $existingTimeslotId = $student[0]['timeslot_id'];
            if (count($student) === 1) {
                if (isset($_POST['update'])) {
                    $query = $connection->prepare('UPDATE students SET first_name = ?, last_name = ?, project_title = ?, phone = ?, email = ?, timeslot_id = ? WHERE umid = ?');
                    $query->bind_param('sssssis', $first_name, $last_name, $project_title, $phone, $email, $id, $umid);
                    $query->execute();
                    $query = $connection->prepare('UPDATE timeslots SET seats_remaining = seats_remaining + 1 WHERE id = ?');
                    $query->bind_param('i', $existingTimeslotId);
                    $query->execute();
                    $query = $connection->prepare('UPDATE timeslots SET seats_remaining = seats_remaining - 1 WHERE id = ?');
                    $query->bind_param('i', $id);
                    $query->execute();
                    $_SESSION['success'] = 'Successfully updated reservation.';
                    header('Location: reservations.php');
                } else {
                    $umidExists = true;
                }
            } else {
                $query = $connection->prepare('INSERT INTO students (umid, first_name, last_name, project_title, phone, email, timeslot_id) VALUES (?, ?, ?, ?, ?, ?, ?)');
                $query->bind_param('ssssssi', $umid, $first_name, $last_name, $project_title, $phone, $email, $id);
                $query->execute();
                $query = $connection->prepare('UPDATE timeslots SET seats_remaining = seats_remaining - 1 WHERE id = ?');
                $query->bind_param('i', $id);
                $query->execute();
                $_SESSION['success'] = 'Successfully created reservation.';
                header('Location: reservations.php');
            }
        } else {
            array_push($errors, 'No seats are available for the selected timeslot.');
        }
    }
}
?>

<?php if ($umidExists) { ?>
    <div class='alert-msg'>
        The UMID specified already contains a reservation. Would you like to change it?
        <input type='button' value='Yes' style='margin: 5px' onclick='yesChange()' />
        <input type='button' value='No' style='margin: 5px' onclick='noChange(this)' />
    </div>
    <script>
        function yesChange() {
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'update';
            let form = document.querySelector('.registration-form');
            form.prepend(input);
            form.submit();
        }

        function noChange(element) {
            closeMsg(element);
            document.querySelector('.registration-form input[type=submit]').disabled = false;
        }
        window.onload = function() {
            document.querySelector('.registration-form input[type=submit]').disabled = true;
        }
    </script>
<?php } ?>

<?php foreach ($errors as $error) { ?>
    <div class='error-msg'>
        <span style='position: absolute; left: 15px; cursor: pointer;' onclick='closeMsg(this)'>X</span>
        Error: <?php echo $error ?>
    </div>
<?php } ?>

<div style='padding: 10px'>
    <div style='display: flex; align-items: center; flex-direction: column;'>
        <h2>Registration Form</h2>
        <div style='text-align: center'>
            <b>Instructions:</b> Use the following registration form to select a timeslot for your CIS435 presentation. If a timeslot is full, the system
            will not show it and you will not be able to register for it. If you're already registered and would like to change your timeslot and/or any other information,
            you may do so by using <b>the same UMID</b> you used to sign up initially, all other information may be changed. Keep in mind when registering, the following
            field requirements must be satisfied:
            <br />
            <br />
            <div><b>1. </b>UMID must be 8 digits</div>
            <div><b>2. </b>First name and last name must be at least 1 character and less than 21 characters</div>
            <div><b>3. </b>Project title must be 1 character and less than 51 characters</div>
            <div><b>4. </b>Phone number must follow the format: ###-###-####</div>
            <div><b>5. </b>Email must follow the pattern: sometext@sometext.sometext</div>
            <div><b>6. </b>A timeslot must be selected</div>
        </div>
        <br />
        <form style='display: flex; flex-direction: column;' method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class='registration-form'>
            <input type='text' name='umid' value='<?php echo $umid ?>' placeholder="UMID" />
            <input type='text' name='first_name' value='<?php echo $first_name ?>' placeholder="First Name" />
            <input type='text' name='last_name' value='<?php echo $last_name ?>' placeholder="Last Name" />
            <input type='text' name='project_title' value='<?php echo $project_title ?>' placeholder="Project Title" />
            <input type='text' name='phone' value='<?php echo $phone ?>' placeholder="Phone" />
            <input type='text' name='email' value='<?php echo $email ?>' placeholder="Email" />
            <br />
            <?php foreach ($resultArr as $timeslot) { ?>
                <?php if ($timeslot['seats_remaining'] != 0) { ?>
                    <div style='display: flex'>
                        <input type='radio' name='timeslot' value='<?php echo $timeslot['id'] ?>' <?php if ($id == $timeslot['id']) { ?> checked <?php } ?> />
                        <?php echo $timeslot['title'] . ', ' . $timeslot['seats_remaining'] . ' seats remaining' ?>
                    </div>
                <?php } ?>
            <?php } ?>
            <br />
            <input name='register' type='submit' />
        </form>
    </div>
</div>

<?php include('templates/footer.php') ?>