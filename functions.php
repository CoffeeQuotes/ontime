<?php
// Form Validation
// Empty fields
function validateRequiredFields($fields)
{
    $emptyFields = [];
    foreach ($fields as $key => $value) {
        if (empty($value)) {
            $emptyFields[] = $key;
        }
    }
    return $emptyFields;
}

// Validate datetime local
function validateAndFormatDateTime($dateString)
{
    try {
        $dateTime = new DateTime($dateString);
        // Date and time is valid
        return $dateTime->format('Y-m-d H:i:s'); // Format for MySQL datetime field
    } catch (Exception $e) {
        // Date and time is not valid
        // You can handle the validation error here, display error message, etc.
        return false; // Return false to indicate validation failure
    }
}

// Make Excerpt
function createExcerpt($text, $maxLength = 100, $ellipsis = '...')
{
    // Remove HTML tags and trim whitespace
    $text = strip_tags($text);
    $text = trim($text);

    // If the text length is less than or equal to the maximum length, return the original text
    if (mb_strlen($text) <= $maxLength) {
        return $text;
    }

    // Truncate the text to the maximum length and append the ellipsis
    $excerpt = mb_substr($text, 0, $maxLength - mb_strlen($ellipsis)) . $ellipsis;

    // Trim whitespace again to ensure clean output
    $excerpt = trim($excerpt);

    return $excerpt;
}

function displayComments($comments, $parentId = null)
{
    // Filter comments based on parent_comment_id
    $filteredComments = array_filter($comments, function ($comment) use ($parentId) {
        return $comment['parent_comment_id'] == $parentId;
    });

    // If there are no comments for the given parent_id, return
    if (empty($filteredComments)) {
        return;
    }

    // Iterate through filtered comments and display them
    foreach ($filteredComments as $comment) {
        echo '<div class="comment">';
        echo '<b>' . htmlspecialchars($comment['username']) . ' says:</b>';
        echo '<div>' . htmlspecialchars($comment['comment_text']) . '</div>';
        echo '<div class="reply-link"><a href="javascript:void(0)" class="replyView">Reply</a></div>';
        echo '<div class="reply-form" style="display: none;">';
        echo '<form class="replyForm">';
        echo '<input type="hidden" class="parentCommentId" value="' . $comment["id"] . '">';
        echo '<textarea class="replyText" style="width: 100%;"></textarea>';
        echo '<button type="button" class="postReply">Post Reply</button>';
        echo '</form>';
        echo '</div>';

        // Recursively display nested comments
        displayComments($comments, $comment['id']);

        echo '</div>';
    }
}



function makeDateTimeHumanFriendly($dateTime)
{
    $timeAgo = strtotime($dateTime);
    $currentTime = time();
    $timeDifference = $currentTime - $timeAgo;

    // Define time intervals in seconds
    $intervals = array(
        1 => array('year', 31556926),
        array('month', 2629744),
        array('week', 604800),
        array('day', 86400),
        array('hour', 3600),
        array('minute', 60),
        array('second', 1)
    );

    // Iterate over intervals
    foreach ($intervals as $interval) {
        $intervalName = $interval[0];
        $intervalSeconds = $interval[1];

        // Calculate time difference in each interval
        $difference = floor($timeDifference / $intervalSeconds);

        // If the difference is greater than 0
        if ($difference >= 1) {
            // Return human-friendly format
            return $difference . ' ' . ($difference > 1 ? $intervalName . 's' : $intervalName) . ' ago';
        }
    }

    // If the difference is less than a second
    return 'just now';
}
// flatten Array
function flattenArray($array)
{
    $result = [];
    foreach ($array as $item) {
        if (is_array($item)) {
            $result = array_merge($result, flattenArray($item));
        } else {
            $result[] = $item;
        }
    }
    return $result;
}

function get_user_roles($user_id, $pdo)
{
    // Prepare the query
    $query = "SELECT r.role_name FROM user_roles ur INNER JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = ?";
    $statement = $pdo->prepare($query);

    // Bind the parameter
    $statement->bindValue(1, $user_id, PDO::PARAM_INT);

    // Execute the query
    $statement->execute();

    // Fetch the results
    $roles = [];
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $roles[] = $row['role_name'];
    }

    // Return the roles
    return $roles;
}



function formatMoney($amount, $currency)
{
    switch ($currency) {
        case 'INR':
            $formatted = number_format($amount, 2, '.', ',') . ' INR';
            break;
        case 'USD':
            $formatted = '$' . number_format($amount, 2);
            break;
        case 'EUR':
            $formatted = number_format($amount, 2, '.', ' ') . ' €';
            break;
        case 'GBP':
            $formatted = '£' . number_format($amount, 2);
            break;
        case 'JPY':
            $formatted = number_format($amount, 0) . ' ¥';
            break;
        case 'CNY':
            $formatted = '¥' . number_format($amount, 2);
            break;
        case 'AUD':
            $formatted = 'A$' . number_format($amount, 2);
            break;
        case 'CAD':
            $formatted = 'CA$' . number_format($amount, 2);
            break;
        case 'CHF':
            $formatted = number_format($amount, 2) . ' CHF';
            break;
        case 'SEK':
            $formatted = number_format($amount, 2) . ' kr';
            break;
        case 'NZD':
            $formatted = 'NZ$' . number_format($amount, 2);
            break;
        default:
            $formatted = 'Invalid Currency';
            break;
    }
    return $formatted;
}

// // Example usage
// echo formatMoney(99999, 'INR'); // Output: 99,999.00 INR
// echo formatMoney(99999, 'USD'); // Output: $99,999.00
// echo formatMoney(99999, 'EUR'); // Output: 99,999.00 €
// echo formatMoney(99999, 'GBP'); // Output: £99,999.00
// echo formatMoney(99999, 'JPY'); // Output: 99,999 ¥
// echo formatMoney(99999, 'CNY'); // Output: ¥99,999.00
// echo formatMoney(99999, 'AUD'); // Output: A$99,999.00
// echo formatMoney(99999, 'CAD'); // Output: CA$99,999.00
// echo formatMoney(99999, 'CHF'); // Output: 99,999.00 CHF
// echo formatMoney(99999, 'SEK'); // Output: 99,999.00 kr
// echo formatMoney(99999, 'NZD'); // Output: NZ$99,999.00
// echo formatMoney(99999, 'XXX'); // Output: Invalid Currency





















