<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$name = $location = $productssold = "";
$name_err = $location_err = $productssold_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    $input_name = trim($_POST["name"]);
    if (empty($input_name)) {
        $name_err = "Please enter a name.";
    } elseif (!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $name_err = "Please enter a valid name.";
    } else {
        $name = $input_name;
    }

    // Validate location
    $input_location = trim($_POST["location"]);
    if (empty($input_location)) {
        $location_err = "Please enter a location.";
    } else {
        $location = $input_location;
    }

    // Validate productssold
    $input_productssold = trim($_POST["Productssold"]);
    if (empty($input_productssold)) {
        $productssold_err = "Please enter the productssold amount.";
    } elseif (!ctype_digit($input_productssold)) {
        $productssold_err = "Please enter a positive integer value.";
    } else {
        $productssold = $input_productssold;
    }

    // Check input errors before inserting in database
    if (empty($name_err) && empty($location_err) && empty($productssold_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO employees (name, Location, Productssold) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_location, $param_productssold);

            // Set parameters
            $param_name = $name;
            $param_location = $location;
            $param_productssold = $productssold;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Location</label>
                            <textarea name="location" class="form-control <?php echo (!empty($location_err)) ? 'is-invalid' : ''; ?>"><?php echo $location; ?></textarea>
                            <span class="invalid-feedback"><?php echo $location_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Productssold</label>
                            <input type="text" name="Productssold" class="form-control <?php echo (!empty($productssold_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $productssold; ?>">
                            <span class="invalid-feedback"><?php echo $productssold_err; ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
