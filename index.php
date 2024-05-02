<?php
session_start();
ob_start();

$servername = "localhost";
$username = "u743445510_ratings_job";
$password = "Ratingsjobs@2024";
$database = "u743445510_ratings_job";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$refer_code = isset($_GET['refer_code']) ? htmlspecialchars($_GET['refer_code']) : ''; 

function generateDeviceID()
{
    return uniqid(); 
}
if (isset($_POST['btnSignup'])) {
    $mobile = $_POST["mobile"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"]; // Define $confirmPassword here
    $name = $_POST["name"];
    $email = $_POST["email"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $age = $_POST["age"];
    $referred_by = $_POST["referred_by"];
    $device_id = generateDeviceID(); // Generate a new device ID

    if ($password !== $confirmPassword) {
        echo "<script>alert('Password and Confirm Password do not match');</script>";
    } else {
        // Proceed with registration process since passwords match

        // Prepare data for API call
        $data = array(
            "mobile" => $mobile,
            "password" => $password,
            "name" => $name,
            "email" => $email,
            "city" => $city,
            "state" => $state,
            "age" => $age,
            "referred_by" => $referred_by,
            "device_id" => $device_id,
        );

        // API endpoint URL
        $apiUrl = "https://ratingsjob.graymatterworks.com/api/register.php";

        // Initialize cURL session
        $curl = curl_init($apiUrl);

        // Set cURL options
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        // Execute cURL request
        $response = curl_exec($curl);

        // Check for errors
        if ($response === false) {
            // Error in cURL request
            echo "Error: " . curl_error($curl);
        } else {
            // Successful API response
            $responseData = json_decode($response, true);

            // Check if response is valid JSON
            if ($responseData === null) {
                // Error decoding response
                echo "Error decoding API response.";
            } else {
                // Handle API response
                if (isset($responseData["success"]) && $responseData["success"]) {
                    // Registration successful
                    $_SESSION['id'] = $responseData["data"][0]['id'];
                    $_SESSION['codes'] = 0;
                    header("Location: app.php"); // Redirect to app.php
                    exit();
                } else {
                    // Registration failed
                    $message = isset($responseData["message"]) ? $responseData["message"] : "Registration failed. Please try again.";
                    echo "<script>alert('$message');</script>";

                    // If the user needs to register, redirect to the registration page
                    if (isset($responseData["register_required"]) && $responseData["register_required"]) {
                        header("Location: index.php"); // Redirect to registration.php
                        exit();
                    }
                }
            }
        }
        // Close cURL session
        curl_close($curl);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        body {
        font-family: 'Poppins', Arial, sans-serif;
        background: #efefef;
    }
        .custom-container {
            width: 450px; 
            margin: 10px auto; /* Adjusted margin */
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background: rgb(255, 255, 255);
        }
        .btn-custom {
            width: 100%;
            margin-top:25px;
            border-radius: 10px;
            border: 2px solid #fed346;
            
        }
        .btn-customs {
            width: 100%;
            border-radius: 15px;
           
        }
        @media (max-width: 576px) {
            .nowrap-mobile {
                white-space: nowrap;
                font-size: 10px;
            }
            .btn-customs {
            width: 100%;
            border-radius: 15px;
            margin-top:6px;
           
        }
     
        }

    </style>
</head>
<body>
<h2 class="text-center mt-5">Register</h2> <!-- Moved Register text outside the container -->
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="custom-container">
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                    <label for="number" style= "font-weight:bold;">Mobile Number:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="border-right: none; background: transparent;"><i class="fas fa-mobile-alt"></i></span>
                        </div>
                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile" required style="border-left: none; ">
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" style= "font-weight:bold;">Full Name:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="border-right: none; background: transparent;"><i class="fas fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" required style="border-left: none;">
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" style= "font-weight:bold;">Password:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="border-right: none; background: transparent;"><i class="fas fa-lock"></i></span>
                        </div>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required style="border-left: none;">
                    </div>
                    <span id="passwordError" class="text-danger"></span>
                </div>
                <div class="form-group">
                    <label for="confirmPassword" style= "font-weight:bold;">Confirm Password:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="border-right: none; background: transparent;"><i class="fas fa-lock"></i></span>
                        </div>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required style="border-left: none;">
                    </div>
                    <span id="confirmPasswordError" class="text-danger"></span>
                </div>
                <div class="form-group">
                    <label for="email" style= "font-weight:bold;">Email:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="border-right: none; background: transparent;"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required style="border-left: none;">
                    </div>
                </div>
                <div class="form-group">
                    <label for="age" style= "font-weight:bold;">Age:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                        <span class="input-group-text" style="border-right: none; background: transparent;"><i class="fas fa-calendar-alt"></i></span>
                        </div>
                        <input type="number" class="form-control" id="age" name="age" placeholder="Age" required style="border-left: none;">
                    </div>
                </div>
                <div class="form-group">
                    <label for="city" style= "font-weight:bold;">City:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="border-right: none; background: transparent;"><i class="fas fa-map-marker-alt"></i></span>
                        </div>
                        <input type="text" class="form-control" id="city" name="city" placeholder="City" required style="border-left: none;">
                    </div>
                </div>
                <div class="form-group">
                    <label for="state" style= "font-weight:bold;">State:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="border-right: none; background: transparent;"><i class="fas fa-map-marker-alt"></i></span>
                        </div>
                        <input type="text" class="form-control" id="state" name="state" placeholder="state" required style="border-left: none;">
                    </div>
                </div>
                <div class="form-group">
                    <label for="referred_by" style= "font-weight:bold;">Referral code:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="border-right: none; background: transparent;"><i class="fas fa-book"></i></span>
                        </div>
                        <input type="text" class="form-control" id="referred_by" name="referred_by" placeholder="Refer Code" style="border-left: none;" value="<?php echo $refer_code; ?>"; ?>
                    </div>
                </div>
                <div class="form-group">
                <button type="submit" class="btn btn-primary btn-custom" name="btnSignup"  style="background-color:#fed346; color:white; font-weight:bold;">Save</button>
                </div>
            </form>
        </div>
    </div>
    <script>
  window.addEventListener('DOMContentLoaded', function() {
    // Get the checkbox element
    var checkbox = document.getElementById('checkbox');
    // Set the "checked" attribute to true
    checkbox.checked = true;
  });
</script>

</body>
</html>