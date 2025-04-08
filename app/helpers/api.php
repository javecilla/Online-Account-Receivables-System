<?php

declare(strict_types=1);

require_once __DIR__ . '/global.php';
require_once __DIR__ . '/system.php';
require_once __DIR__ . '/../services/app.php';

function check_rate_limit(): bool
{
    $ip = $_SERVER['REMOTE_ADDR'];
    $request_per_minute = 50;
    $time_window = 60; // 1 minute in seconds

    $storage_file = sys_get_temp_dir() . '/rate_limit_' . md5($ip) . '.txt';
    // Check if file exists and get stored data
    if (file_exists($storage_file)) {
        $data = json_decode(file_get_contents($storage_file), true);
        $now = time();

        // Check if time window has expired
        if ($now - $data['timestamp'] >= $time_window) {
            // Reset for new time window
            $data = [
                'timestamp' => $now,
                'requests' => 1
            ];
        } else {
            // Increment requests within current window
            if ($data['requests'] >= $request_per_minute) {
                return false; // Rate limit exceeded
            }
            $data['requests']++;
        }
    } else {
        // Create new data for time window
        $data = [
            'timestamp' => time(),
            'requests' => 1
        ];
    }

    // Save updated data
    file_put_contents($storage_file, json_encode($data));
    return true;
}

function check_payload_validity(array $payload): bool
{
    return !is_null($payload) && json_last_error() === JSON_ERROR_NONE;
}

function check_csrf_token(): bool
{
    $is_prod = env('APP_ENV') === 'production';

    if ($is_prod) {
        // Missing CSRF token header
        if (!isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
            return false;
        }

        // Invalid session state
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }

        $token = $_SERVER['HTTP_X_CSRF_TOKEN'];

        // Invalid CSRF token
        if (!hash_equals($_SESSION['csrf_token'], $token)) {
            return false;
        }
    }

    return true;
}

function generate_csrf_token(): string
{
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    return $token;
}

function initialized_api_middleware(mixed $server_request): void
{
    # Check if the JSON decoding was successful
    if (!check_payload_validity($server_request)) {
        return_response(['success' => false, 'message' => 'Bad Request! Invalid data format.', 'status' => 400]);
    }

    # TODO: Check csrf validity before processing request
    // if (!check_csrf_token()) {
    //     return_response(['success' => false, 'message' => 'Unauthorized! Invalid CSRF token.', 'status' => 401]);
    // }

    # Check rate limit before processing request
    if (!check_rate_limit()) {
        return_response(['success' => false, 'message' => 'Too many requests! Rate limit exceeded.', 'status' => 429]);
    }

    # check for 'action'
    if (!isset($server_request['action']) || empty($server_request['action'])) {
        return_response(['success' => false, 'message' => 'Bad Request! Missing action.', 'status' => 400]);
    }

    # only check for 'data' in payload if the request is not get
    if (get_request_method() !== 'GET') {
        if (!isset($server_request['data']) || empty($server_request['data'])) {
            return_response(['success' => false, 'message' => 'Bad Request! Invalid missing data.', 'status' => 400]);
        }
    }
}

function validate_data(mixed $data, array $expected_field_types): ?array
{
    // Initialize validated data array with all fields as null
    $validated_data = [];
    foreach ($expected_field_types as $field => $rules) {
        $validated_data[$field] = null;
    }

    foreach ($expected_field_types as $field => $rules) {
        // Split the rules by pipe ('|')
        $rules = explode('|', $rules);

        // Track if the field is optional
        $is_optional = in_array('optional', $rules);

        // Check if the field exists and has a non-empty value
        $fieldExists = isset($data[$field]) && $data[$field] !== null && $data[$field] !== '';

        // Handle file validation if the field exists in $_FILES
        $file = $_FILES[$field] ?? null;

        // Always set the field value in validated data if it exists
        if (isset($data[$field])) {
            $validated_data[$field] = $data[$field];
        }

        // Skip validation if field is optional and empty
        if ($is_optional && !$fieldExists) {
            continue;
        }

        // Check if required field is missing
        if (!$is_optional && !$fieldExists) {
            return_response(['success' => false, 'message' => "Field '$field' is required.", 'status' => 400]);
        }

        // Only validate if field has a value (not empty/null)
        if ($fieldExists) {
            foreach ($rules as $rule) {
                if ($rule === 'optional') continue;

                // Handle rules with parameters (e.g., size:1048576 or mimes:jpeg,jpg,png)
                if (strpos($rule, ':') !== false) {
                    [$ruleType, $ruleValue] = explode(':', $rule, 2);

                    switch ($ruleType) {
                        case 'check':
                            $checkValues = explode(',', $ruleValue);

                            foreach ($checkValues as $check) {
                                switch (trim($check)) {
                                    case 'is_email_unique':
                                        if (is_email_exist($data[$field])) {
                                            return_response([
                                                'success' => false,
                                                'message' => 'Email is already taken.',
                                                'status' => 400
                                            ]);
                                        }
                                        break;

                                    case 'is_username_unique':
                                        if (is_username_exist($data[$field])) {
                                            return_response([
                                                'success' => false,
                                                'message' => 'Username is already taken.',
                                                'status' => 400
                                            ]);
                                        }
                                        break;

                                    case 'is_email_exist':
                                        if (!is_email_exist($data[$field])) {
                                            return_response([
                                                'success' => false,
                                                'message' => "Account with email {$data[$field]} does not exist.",
                                                'status' => 400
                                            ]);
                                        }
                                        break;

                                    case 'role_model':
                                        $result = get_role((int) $data[$field]);
                                        if (!$result['success']) {
                                            return_response($result);
                                        }
                                        break;

                                    case 'account_model':
                                        $result = get_account((int) $data[$field]);
                                        if (!$result['success']) {
                                            return_response($result);
                                        }
                                        break;

                                    case 'type_model':
                                        $result = get_type((int) $data[$field]);
                                        if (!$result['success']) {
                                            return_response($result);
                                        }
                                        break;

                                    case 'member_model':
                                        $result = get_member((int) $data[$field]);
                                        if (!$result['success']) {
                                            return_response($result);
                                        }
                                        break;

                                    case 'amortization_type_model':
                                        $result = get_amortization_type((int) $data[$field]);
                                        if (!$result['success']) {
                                            return_response($result);
                                        }
                                        break;

                                    case 'amortization_model':
                                        $result = get_amortization((int) $data[$field]);
                                        if (!$result['success']) {
                                            return_response($result);
                                        }
                                        break;

                                    case 'employee_model':
                                        $result = get_employee((int) $data[$field]);
                                        if (!$result['success']) {
                                            return_response($result);
                                        }
                                        break;

                                    default:
                                        return_response([
                                            'success' => false,
                                            'message' => "Unsupported validation check: {$ruleValue}",
                                            'status' => 400
                                        ]);
                                }
                            }
                            break;

                        case 'check_except':
                            $params = explode(',', $ruleValue);
                            if (count($params) !== 2) {
                                return_response([
                                    'success' => false,
                                    'message' => "Invalid check_except format. Expected: check_except:function,field",
                                    'status' => 400
                                ]);
                            }

                            $checkFunction = trim($params[0]);
                            $exceptField = trim($params[1]);

                            switch ($checkFunction) {
                                case 'is_email_unique':
                                    if (!isset($data[$exceptField])) {
                                        return_response([
                                            'success' => false,
                                            'message' => "Missing exception field '$exceptField' for email check",
                                            'status' => 400
                                        ]);
                                    }
                                    if (is_email_exist($data[$field], (int)$data[$exceptField])) {
                                        return_response([
                                            'success' => false,
                                            'message' => 'Email is already taken.',
                                            'status' => 400
                                        ]);
                                    }
                                    break;

                                case 'is_username_unique':
                                    if (!isset($data[$exceptField])) {
                                        return_response([
                                            'success' => false,
                                            'message' => "Missing exception field '$exceptField' for username check",
                                            'status' => 400
                                        ]);
                                    }
                                    if (is_username_exist($data[$field], (int)$data[$exceptField])) {
                                        return_response([
                                            'success' => false,
                                            'message' => 'Username is already taken.',
                                            'status' => 400
                                        ]);
                                    }
                                    break;

                                case 'is_email_exist':
                                    if (!isset($data[$exceptField])) {
                                        return_response([
                                            'success' => false,
                                            'message' => "Missing exception field '$exceptField' for email check",
                                            'status' => 400
                                        ]);
                                    }
                                    if (!is_email_exist($data[$field], (int)$data[$exceptField])) {
                                        return_response([
                                            'success' => false,
                                            'message' => "Account with email {$data[$field]} does not exist.",
                                            'status' => 400
                                        ]);
                                    }
                                    break;

                                default:
                                    return_response([
                                        'success' => false,
                                        'message' => "Unsupported check_except function: {$checkFunction}",
                                        'status' => 400
                                    ]);
                            }
                            break;

                        case 'size':
                            if ($file && $file['size'] > (int)$ruleValue) {
                                return_response([
                                    'success' => false,
                                    'message' => "Field '$field' exceeds the maximum allowed size of " . (int)$ruleValue . " bytes.",
                                    'status' => 400,
                                ]);
                            }
                            break;

                        case 'mimes':
                            if ($file) {
                                $allowedMimes = explode(',', $ruleValue);
                                $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
                                $fileType = mime_content_type($file['tmp_name']);

                                if (!in_array($fileExtension, $allowedMimes) || !in_array(str_replace('image/', '', $fileType), $allowedMimes)) {
                                    return_response([
                                        'success' => false,
                                        'message' => "Field '$field' must be an image of type: $ruleValue.",
                                        'status' => 400,
                                    ]);
                                }
                            }
                            break;

                        case 'min':
                            if (isset($data[$field])) {
                                if (is_numeric($data[$field]) && !is_string($data[$field])) {
                                    // Handle numeric validation
                                    if ($data[$field] < (int)$ruleValue) {
                                        return_response([
                                            'success' => false,
                                            'message' => "Field '$field' must be at least $ruleValue.",
                                            'status' => 400,
                                        ]);
                                    }
                                } else {
                                    // Handle string validation
                                    if (strlen((string)$data[$field]) < (int)$ruleValue) {
                                        return_response([
                                            'success' => false,
                                            'message' => "Field '$field' must be at least $ruleValue characters long.",
                                            'status' => 400,
                                        ]);
                                    }
                                }
                            }
                            break;

                        case 'max':
                            if (isset($data[$field])) {
                                if (is_numeric($data[$field]) && !is_string($data[$field])) {
                                    // Handle numeric validation
                                    if ($data[$field] > (int)$ruleValue) {
                                        return_response([
                                            'success' => false,
                                            'message' => "Field '$field' must be no more than $ruleValue.",
                                            'status' => 400,
                                        ]);
                                    }
                                } else {
                                    // Handle string validation
                                    if (strlen((string)$data[$field]) > (int)$ruleValue) {
                                        return_response([
                                            'success' => false,
                                            'message' => "Field '$field' must be no more than $ruleValue characters long.",
                                            'status' => 400,
                                        ]);
                                    }
                                }
                            }
                            break;

                        case 'length':
                            if (isset($data[$field]) && strlen((string)$data[$field]) != (int)$ruleValue) {
                                return_response([
                                    'success' => false,
                                    'message' => "Field '$field' must be exactly $ruleValue characters long.",
                                    'status' => 400,
                                ]);
                            }
                            break;

                        case 'match':
                            if (!isset($data[$ruleValue]) || $data[$field] !== $data[$ruleValue]) {
                                return_response([
                                    'success' => false,
                                    'message' => "Field '$field' must match with '$ruleValue'.",
                                    'status' => 400,
                                ]);
                            }
                            break;

                        case 'contains':
                            $containedValues = explode(',', $ruleValue);

                            foreach ($containedValues as $contain) {
                                switch (trim($contain)) {
                                    case 'lowercase':
                                        if (!preg_match('/[a-z]/', $data[$field])) {
                                            return_response([
                                                'success' => false,
                                                'message' => "Field '$field' must contain at least one lowercase letter.",
                                                'status' => 400,
                                            ]);
                                        }
                                        break;

                                    case 'uppercase':
                                        if (!preg_match('/[A-Z]/', $data[$field])) {
                                            return_response([
                                                'success' => false,
                                                'message' => "Field '$field' must contain at least one uppercase letter.",
                                                'status' => 400,
                                            ]);
                                        }
                                        break;

                                    case 'number':
                                        if (!preg_match('/\d/', $data[$field])) {
                                            return_response([
                                                'success' => false,
                                                'message' => "Field '$field' must contain at least one number.",
                                                'status' => 400,
                                            ]);
                                        }
                                        break;

                                    case 'symbol':
                                        if (!preg_match('/[\W_]/', $data[$field])) { // Matches non-alphanumeric characters
                                            return_response([
                                                'success' => false,
                                                'message' => "Field '$field' must contain at least one symbol.",
                                                'status' => 400,
                                            ]);
                                        }
                                        break;

                                    default:
                                        return_response([
                                            'success' => false,
                                            'message' => "Unsupported contain condition: $contain for field '$field'.",
                                            'status' => 400,
                                        ]);
                                }
                            }
                            break;

                        case 'in':
                            $allowedValues = explode(',', $ruleValue);
                            if (!in_array($data[$field], $allowedValues)) {
                                return_response([
                                    'success' => false,
                                    'message' => "Field '$field' must be one of the following values: $ruleValue.",
                                    'status' => 400,
                                ]);
                            }
                            break;

                        case 'not_in':
                            $disallowedValues = explode(',', $ruleValue);
                            if (in_array($data[$field], $disallowedValues)) {
                                return_response([
                                    'success' => false,
                                    'message' => "Field '$field' must not be one of the following values: $ruleValue.",
                                    'status' => 400,
                                ]);
                            }
                            break;

                        case 'date':
                            if (isset($data[$field])) {
                                $dateFormat = $ruleValue; // e.g., 'YYYY-MM-DD'
                                $inputValue = (string)$data[$field];

                                // Support multiple format notations
                                $phpFormat = str_replace(
                                    ['YYYY', 'MM', 'DD', 'YY'],
                                    ['Y', 'm', 'd', 'y'],
                                    $dateFormat
                                );

                                $dateTime = DateTime::createFromFormat($phpFormat, $inputValue);
                                $errors = DateTime::getLastErrors();

                                // Check if $errors is false (no errors) or an array with errors
                                $hasErrors = $errors !== false && (
                                    (isset($errors['warning_count']) && $errors['warning_count'] > 0) ||
                                    (isset($errors['error_count']) && $errors['error_count'] > 0)
                                );

                                if (
                                    !$dateTime ||
                                    $hasErrors ||
                                    $dateTime->format($phpFormat) !== $inputValue
                                ) {
                                    return_response([
                                        'success' => false,
                                        'message' => "Field '$field' must be a valid date in '$dateFormat' format.",
                                        'status' => 400,
                                    ]);
                                }

                                // TODO: Add additional validation like future/past dates
                                $today = new DateTime();
                                if ($dateTime > $today) {
                                    // ...
                                }
                            }
                            break;

                        default:
                            return_response([
                                'success' => false,
                                'message' => "Unsupported validation rule: $ruleType",
                                'status' => 400
                            ]);
                    }
                } else {
                    // Handle general rules without parameters
                    switch ($rule) {
                        case 'required':
                            // Check if the field is not null or empty, but allow 0
                            if (!isset($data[$field]) || ($data[$field] === null || $data[$field] === '') && !$file) {
                                return_response([
                                    'success' => false,
                                    'message' => "Field '$field' is required.",
                                    'status' => 400,
                                ]);
                            }
                            break;

                        case 'numeric':
                            // Check if the field is numeric before converting it to an integer
                            if (isset($data[$field]) && is_numeric($data[$field])) {
                                $data[$field] = intval($data[$field]);
                            } else {
                                return_response([
                                    'success' => false,
                                    'message' => "Field '$field' must be a valid integer.",
                                    'status' => 400,
                                ]);
                            }
                            break;

                        case 'string':
                            if (!is_valid_string($data[$field])) {
                                return_response([
                                    'success' => false,
                                    'message' => "Field '$field' must be a string.",
                                    'status' => 400,
                                ]);
                            }
                            break;

                        case 'boolean':
                            // Check if the value is a valid boolean (true, false, 1, or 0)
                            if (isset($data[$field])) {
                                if (!in_array($data[$field], [true, false, 1, 0], true)) {
                                    return_response([
                                        'success' => false,
                                        'message' => "Field '$field' must be a valid boolean (true, false, 1, or 0).",
                                        'status' => 400,
                                    ]);
                                }
                            }
                            break;

                        case 'double':
                            // Check if the field is a valid float
                            if (isset($data[$field]) && is_numeric($data[$field])) {
                                $data[$field] = floatval($data[$field]);
                            } else {
                                return_response([
                                    'success' => false,
                                    'message' => "Field '$field' must be a valid double.",
                                    'status' => 400,
                                ]);
                            }
                            break;

                        case 'array':
                            if (!is_array($data[$field])) {
                                return_response([
                                    'success' => false,
                                    'message' => "Field '$field' must be an array.",
                                    'status' => 400,
                                ]);
                            }
                            break;

                        case 'image':
                            if (!$file || strpos(mime_content_type($file['tmp_name']), 'image/') !== 0) {
                                return_response([
                                    'success' => false,
                                    'message' => "Field '$field' must be a valid image file.",
                                    'status' => 400,
                                ]);
                            }
                            break;

                        case 'email':
                            if (!filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                                return_response([
                                    'success' => false,
                                    'message' => "Field '$field' must be a valid email.",
                                    'status' => 400,
                                ]);
                            }
                            break;

                        default:
                            return_response(['success' => false, 'message' => "Unsupported validation rule: $rule", 'status' => 400]);
                    }
                }
            }
        }
    }

    // If all fields validated
    return ['success' => true, 'message' => 'Validation passed.', 'data' => $validated_data];
}
