<?php

/**
 * This function takes a $resource_link_id and creates a new Step for it.
 * @param $resource_link_id
 * @return int|string
 */
function NewLab($resource_link_id)
{
    //Create Database Connection
    $connection = ConnectDB();

    //Check if 'labs' table exists
    $query = "SELECT id FROM labs";
    $result = mysqli_query($connection, $query);
    //TODO: Evaluate Database Field Sizes - Do we need correct_answer?

    //Create the table if necessary
    if(empty($result)) {
        $query = "CREATE TABLE labs (
                          resource_link_id varchar(32) NOT NULL,
                          steps varchar(512) NOT NULL,
                          due_date DATETIME NOT NULL,
                          open_date DATETIME NOT NULL,
                          PRIMARY KEY  (resource_link_id)
                          )";
        $result = mysqli_query($connection, $query);
    }

    //Create the new step entry with auto increment
    $sql = "INSERT INTO labs (resource_link_id) VALUES ('$resource_link_id')";
    if ($connection->query($sql) == TRUE) {

        $step_id = mysqli_insert_id($connection);

        return GetLab($resource_link_id);

    } else {
        return "Error: " . $sql . "<br>" . $connection->error;
    }
}

function GetLab($resource_link_id)
{
    $connection = ConnectDB();
    $sql = "SELECT * FROM labs WHERE resource_link_id='$resource_link_id'";

    $result = $connection->query($sql);

    if ($result == TRUE) {
        return $result->fetch_assoc();
    }
    else
    {
        return null;
    }
}

function SaveLab($resource_link_id, $steps, $due_date, $open_date)
{
    $connection = ConnectDB();
    $sql = "UPDATE labs SET steps='$steps', due_date='$due_date', open_date='$open_date' WHERE resource_link_id='$resource_link_id'";

    if ($connection->query($sql) == TRUE) {
        return "Record updated successfully";
    } else {
        return "Error: " . $sql . "<br>" . $connection->errno . ": " . $connection->error;
    }
}

function DeleteStep($step_id)
{
    //Create Database Connection
    $connection = ConnectDB();

    //Create the new step entry with auto increment
    $sql = "DELETE FROM steps WHERE id=$step_id";
    if ($connection->query($sql) == TRUE) {

        return TRUE;

    } else {
        return $connection->error;
    }
}

function NewStep($resource_link_id, $step_mask)
{

    //Create Database Connection
    $connection = ConnectDB();

    //Check if 'steps' table exists
    $query = "SELECT id FROM steps";
    $result = mysqli_query($connection, $query);

    //TODO: Evaluate Database Field Sizes - Do we need correct_answer?

    //Create the table if necessary
    if(empty($result)) {
        $query = "CREATE TABLE steps (
                          id int(32) AUTO_INCREMENT,
                          instruction varchar(512) NOT NULL,
                          correct_answer varchar(1024) NOT NULL,
                          expected_output varchar(512) NOT NULL,
                          resource_link_id varchar(32) NOT NULL,
                          step_mask int(8) NOT NULL,
                          PRIMARY KEY  (id)
                          )";
        $result = mysqli_query($connection, $query);
    }

    //Create the new step entry with auto increment
    $sql = "INSERT INTO steps (resource_link_id, step_mask) VALUES ('$resource_link_id', '$step_mask')";
    if ($connection->query($sql) == TRUE) {

        $step_id = mysqli_insert_id($connection);

        return GetStep($step_id);

    } else {
        return "Error: " . $sql . "<br>" . $connection->error;
    }
}

function GetStep($id)
{
    $connection = ConnectDB();
    $sql = "SELECT * FROM steps WHERE id=$id";

    $result = $connection->query($sql);

    if ($result == TRUE) {
        return $result->fetch_assoc();
    }
    else
    {
        return "Error: " . $sql . "<br>". $connection->errno . ": " . $connection->error;
    }
}

function SaveStep($instruction, $correct_answer, $expected_output, $id, $step_mask)
{
    $connection = ConnectDB();
    $sql = "UPDATE steps SET instruction='$instruction', correct_answer='$correct_answer', expected_output='$expected_output', step_mask='$step_mask' WHERE id=$id";

    if ($connection->query($sql) == TRUE) {
        return "Record updated successfully";
    } else {
        return "Error: " . $sql . "<br>" . $connection->errno . ": " . $connection->error;
    }
}

function ConnectDB()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "labsoftware";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    else
    {
        return $conn;
    }
}
?>

<?php
/*
//Step Database test Cases

echo "<h1>NewStep</h1>";

$step = NewStep("120988f929-274612", 1);

echo var_dump($step) . "\n";

echo "<h1>SaveStep</h1>";

echo var_dump(SaveStep("Please Input four ? in a Row (????) into the Answer Field", "????", "????", $step['id'], $step['step_mask'])) . "\n";

echo "<h1>GetStep</h1>";

echo var_dump(GetStep($step['id']));

//lab Database test Cases

echo "<h1>NewLab</h1>";

//$lab = NewLab("120988f929-274612");

//echo var_dump($lab) . "\n";

echo "<h1>SaveLab</h1>";

echo var_dump(SaveLab("120988f929-274612", "1,2,3", time(), time()  )) . "\n";

echo "<h1>GetLab</h1>";

echo var_dump(GetLab("120988f929-274612"));

*/