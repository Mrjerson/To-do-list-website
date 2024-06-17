<?php
session_start();

// Check if the token is set in the session and in the URL
if (isset($_SESSION['verify_token']) && isset($_GET['token'])) {
    // Verify the token
    if ($_SESSION['verify_token'] === $_GET['token']) {
        // Token is valid, proceed with verification
        echo '<h1>Verification Successful</h1>';
        // Unset the token to prevent reuse
        unset($_SESSION['verify_token']);
    } else {
        // Invalid token
        http_response_code(403);
        header('Location: error.html');
        exit();
    }
} else {
    // Token is missing
    http_response_code(403);
    header('Location: error.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Code</title>
    <style>
        /* Remove the spin buttons in number input fields */
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield; /* Hide spin buttons in Firefox */
        }
    </style>
</head>
<body>
    <form id="verifyForm" action="verify1.php" method="post">
        <label for="code1">Authentication Code:</label><br>
        <input type="number" id="code1" name="code1" maxlength="1" required oninput="moveToNext(this, 'code2')" onkeydown="moveToPrev(event, 'code1')">
        <input type="number" id="code2" name="code2" maxlength="1" required oninput="moveToNext(this, 'code3')" onkeydown="moveToPrev(event, 'code1', 'code2')">
        <input type="number" id="code3" name="code3" maxlength="1" required oninput="moveToNext(this, 'code4')" onkeydown="moveToPrev(event, 'code2', 'code3')">
        <input type="number" id="code4" name="code4" maxlength="1" required oninput="moveToNext(this, 'code5')" onkeydown="moveToPrev(event, 'code3', 'code4')">
        <input type="number" id="code5" name="code5" maxlength="1" required oninput="moveToNext(this, 'code6')" onkeydown="moveToPrev(event, 'code4', 'code5')">
        <input type="number" id="code6" name="code6" maxlength="1" required oninput="checkAndSubmit()" onkeydown="moveToPrev(event, 'code5', 'code6')"><br><br>
    </form>

    <script>
        function moveToNext(current, nextFieldID) {
            if (current.value.length >= current.maxLength) {
                document.getElementById(nextFieldID).focus();
            }
        }

        function moveToPrev(event, prevFieldID, currentFieldID) {
            const prevField = document.getElementById(prevFieldID);
            const currentField = document.getElementById(currentFieldID);

            if (event.key === "Backspace" && currentField.value.length === 0) {
                prevField.focus();
                prevField.value = ''; // Clear the previous field to keep values intact
            }
        }

        function checkAndSubmit() {
            const form = document.getElementById('verifyForm');
            const inputs = form.querySelectorAll('input[type="number"]');
            let allFilled = true;
            inputs.forEach(input => {
                if (input.value === '') {
                    allFilled = false;
                }
            });

            if (allFilled) {
                form.submit();
            }
        }
    </script>
</body>
</html>
