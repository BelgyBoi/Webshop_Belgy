<?php
session_start();

class LoginCheck {
    public function isUserLoggedIn() {
        error_log('Session Variables in LoginCheck: ' . print_r($_SESSION, true)); // Debugging line
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
            return true;
        }
        return false;
    }

    public function getLoginStatus() {
        $response = ['loggedin' => $this->isUserLoggedIn()];
        header('Content-Type: application/json');
        echo json_encode($response);
        error_log('Login Status Response: ' . json_encode($response)); // Debugging line
    }
}

$loginCheck = new LoginCheck();
$loginCheck->getLoginStatus();
?>
