<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$customer_name = $member_since = $favorite_product = $customer_review = "";
$customer_name_err = $member_since_err = $favorite_product_err = $customer_review_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate customer name
    $input_customer_name = trim($_POST["customer-name"]);
    if (empty($input_customer_name)) {
        $customer_name_err = "Please enter customer name.";
    } else {
        $customer_name = $input_customer_name;
    }

    // Validate member since
    $input_member_since = trim($_POST["member-since"]);
    if (empty($input_member_since)) {
        $member_since_err = "Please enter member since.";
    } else {
        $member_since = $input_member_since;
    }

    // Validate favorite product
    $input_favorite_product = trim($_POST["favorite-product"]);
    if (empty($input_favorite_product)) {
        $favorite_product_err = "Please select favorite product.";
    } else {
        $favorite_product = $input_favorite_product;
    }

    // Validate customer review
    $input_customer_review = trim($_POST["review"]);
    if (empty($input_customer_review)) {
        $customer_review_err = "Please enter customer review.";
    } else {
        $customer_review = $input_customer_review;
    }

    // Check input errors before inserting in database
    if (empty($customer_name_err) && empty($member_since_err) && empty($favorite_product_err) && empty($customer_review_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO customer_reviews (customer_name, member_since, favorite_product, customer_review) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_customer_name, $param_member_since, $param_favorite_product, $param_customer_review);

            // Set parameters
            $param_customer_name = $customer_name;
            $param_member_since = $member_since;
            $param_favorite_product = $favorite_product;
            $param_customer_review = $customer_review;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to customer review page
                header("location: customer_review.php");
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
