<?php

declare(strict_types=1);

function handle_create_employee_cooperative(mixed $payload): void
{
    //log_request('payload: ', $payload);
    $validated = validate_data($payload, [
        //account information
        'role_id' => 'required|numeric|min:1|check:role_model',
        'email'   => 'required|email|check:is_email_unique',
        'username' => 'required|check:is_username_unique',
        'password' => 'required|min:8|contains:lowercase,uppercase,number,symbol',
        'confirm_password' => 'required|match:password',
        //'profile_img'   => 'required|image|mimes:jpeg,jpg,png|size:1048576',
        //employee information
        'first_name' => 'required|string',
        'middle_name' => 'optional|string',
        'last_name' => 'required|string',
        'contact_number' => 'required|length:13',
        'salary' => 'required|numeric',
        'rata' => 'required|numeric',
        //'page_from' => 'required'
    ]);

    //return_response(['success' => true, 'message' => '[TEST]: Employee Registered successfully.']);

    $a_created = create_account($validated['data']);
    if (!$a_created['success']) {
        return_response($a_created);
    }

    $validated['data']['account_id'] = $a_created['data']['account_id'];

    $e_created = create_employee($validated['data']);
    if (!$e_created['success']) {
        return_response($e_created);
    }

    return_response(['success' => true, 'message' => 'Registered successfully.']);
}


function handle_get_employee_by_account(mixed $payload): void
{
    $validated = validate_data($payload, [
        'account_id' => 'required|numeric|min:1|check:account_model',
    ]);
    $employee = get_employee_by_account((int)$validated['data']['account_id']);
    return_response($employee);
}

function handle_update_employee_cooperative(mixed $paylod): void
{
    $validated = validate_data($paylod, [
        //acount info
        'account_id' => 'required|numeric|min:1|check:account_model',
        'role_id' => 'required|numeric|min:1|check:role_model',
        'email'   => 'required|email|check_except:is_email_unique,account_id',
        'username' => 'required|check_except:is_username_unique,account_id',
        'password' => 'optional|min:8|contains:lowercase,uppercase,number,symbol',
        'confirm_password' => 'optional|match:password',
        //employee
        'employee_id' => 'required|numeric|min:1|check:employee_model',
        'first_name' => 'required|string',
        'middle_name' => 'optional|string',
        'last_name' => 'required|string',
        'contact_number' => 'required|length:13',
        'salary' => 'required|numeric',
        'rata' => 'required|numeric',
    ]);

    // log_request('payload', $validated['data']);
    // return_response(['success' => true, 'message' => 'testtt update']);
    $a_updated = update_account((int)$validated['data']['account_id'], $validated['data']);
    if (!$a_updated['success']) {
        return_response($a_updated);
    }

    $e_updated = update_employee((int)$validated['data']['employee_id'], $validated['data']);
    if (!$e_updated['success']) {
        return_response($e_updated);
    }

    return_response(['success' => true, 'message' => 'Updated successfully.']);
}
