<?php
// utils.php

/**
 * Checks if a user is logged in.
 * If not, redirects to the login/signup page.
 */
function checkLogin() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['username'])) {
        header("Location: /index.php?page=auth");
        exit;
    }
}

/**
 * redirects to a given URL with a message.
 * the message type (success or error) is appended as a GET parameter.
 * type is set as success by default, in case of an error the function call should specify the type.
 */
function redirectWithMessage($url, $message, $type='success') {
    header("Location: $url&" . $type . "=" . urlencode($message));
    exit;
}

/**
 * executes a prepared query.
 * this function prepares a statement, binds parameters (if provided),
 * executes the query, and returns an array with the statement and result set.
 */
function executePreparedQuery($mysqli, $sql, $paramTypes = '', $params = []) {
    $stmt = $mysqli->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $mysqli->error);
    }
    if ($paramTypes && !empty($params)) {
        $stmt->bind_param($paramTypes, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    return [$stmt, $result];
}

/**
 * this verifies that an email address is valid
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * this function validates a 10-digit phone number.
 */
function validatePhone($phone) {
    return preg_match("/^\d{10}$/", $phone);
}

/**
 * this function sanitizes an input string for safe SQL insertion.
 * currently not used much in the project, however in future updates it can be used.
 */
function sanitizeInput($mysqli, $input) {
    return mysqli_real_escape_string($mysqli, $input);
}

/**
 * generates pagination links.
 * recieves current page index, and the total pages and the base url for the pagination
 * base url refers to the page where pagination is being generated
 */
function generatePaginationLinks($currentPage, $totalPages, $baseUrl) {
    $links = '';
    if ($totalPages <= 1) {
        return $links;
    }
    $links .= '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
    if ($currentPage > 1) {
        $links .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . 'p=' . ($currentPage - 1) . '">Previous</a></li>';
    }
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = ($i == $currentPage) ? ' active' : '';
        $links .= '<li class="page-item' . $active . '"><a class="page-link" href="' . $baseUrl . 'p=' . $i . '">' . $i . '</a></li>';
    }
    if ($currentPage < $totalPages) {
        $links .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . 'p=' . ($currentPage + 1) . '">Next</a></li>';
    }
    $links .= '</ul></nav>';
    return $links;
}
?>
