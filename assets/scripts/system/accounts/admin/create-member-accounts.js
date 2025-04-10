//action buttons
const $createAccountBtn = $('#createAccountBtn')
const $resetBtn = $('#resetBtn')

//params data
const mode = getURLParams('mode') //create, update, view
const forAccount = getURLParams('for') //employee, member
const hashFragment = getHashFragment() //internal, external

//acount information
const $accountRole = $('#accountRole')
const $accountRoleError = $('#accountRoleError')

const $email = $('#email')
const $emailError = $('#emailError')

const $username = $('#username')
const $usernameError = $('#usernameError')

const $password = $('#password')
const $passwordError = $('#passwordError')

const $confirmPassword = $('#confirmPassword')
const $confirmPasswordError = $('#confirmPasswordError')

// member information
const $membershipType = $('#membershipType')
const $membershipTypeError = $('#membershipTypeError')

const $memberFirstName = $('#memberFirstName')
const $memberFirstNameError = $('#memberFirstNameError')

const $memberMiddleName = $('#memberMiddleName')
const $memberMiddleNameError = $('#memberMiddleNameError')

const $memberLastName = $('#memberLastName')
const $memberLastNameError = $('#memberLastNameError')

const $memberContactNumber = $('#memberContactNumber')
const $memberContactNumberError = $('#memberContactNumberError')

const $memberHouseAddress = $('#memberHouseAddress')
const $memberHouseAddressError = $('#memberHouseAddressError')

const $memberBarangay = $('#memberBarangay')
const $memberBarangayError = $('#memberBarangayError')

const $memberMunicipality = $('#memberMunicipality')
const $memberMunicipalityError = $('#memberMunicipalityError')

const $memberProvince = $('#memberProvince')
const $memberProvinceError = $('#memberProvinceError')

const $memberRegion = $('#memberRegion')
const $memberRegionError = $('#memberRegionError')

/*
Payload sample in 'create_member_cooperative':
Services Function: 'createMemberCooperative()'

{
    "action": "create_member_cooperative",
	"data": {
        # account information
        "role_id": 3,
        "email": "member.two@gmail.com",
        "username": "member.two",
        "password": "Javecilla24/",
        "confirm_password": "Javecilla24/",
        # member information
        "type_id": 6,
        "first_name": "John",
        "middle_name": "Smith",
        "last_name": "Doe",
        "contact_number": "+639772465533",
        "house_address": "Ph7, Blk3, Lot24, Residence III",
        "barangay": "Brgy. Mapulang Lupa",
        "municipality": "Pandi",
        "province": "Bulacan",
        "region": "Region 3",
        #
        "page_from": "internal"
    }
}
*/

// employee information
const $employeeFirstName = $('#employeeFirstName')
const $employeeFirstNameError = $('#employeeFirstNameError')

const $employeeMiddleName = $('#employeeMiddleName')
const $employeeMiddleNameError = $('#employeeMiddleNameError')

const $employeeLastName = $('#employeeLastName')
const $employeeLastNameError = $('#employeeLastNameError')

const $employeeContactNumber = $('#employeeContactNumber')
const $employeeContactNumberError = $('#employeeContactNumberError')

const $employeeSalary = $('#employeeSalary')
const $employeeSalaryError = $('#employeeSalaryError')

const $employeeRata = $('#employeeRata')
const $employeeRataError = $('#employeeRataError')

/*
Payload sample in 'create_employee_cooperative':
Services Function: 'createEmployeeCooperative()'

{
    "action": "create_employee_cooperative",
	"data": {
        # account information
        "role_id": 2,
        "email": "employee.two@gmail.com",
        "username": "employee.two",
        "password": "Javecilla24/",
        "confirm_password": "Javecilla24/",
        # employee information
        "first_name": "John",
        "middle_name": "Smith",
        "last_name": "Doe",
        "contact_number": "+639772465533",
        "salary":  20000,
        "rata": 10000,
        #
        "page_from": "internal"
    }
}
*/

// Reset form functions
window.resetAccountForm = () => {
  $accountRole.val('').removeClass('is-invalid')
  $email.val('').removeClass('is-invalid')
  $username.val('').removeClass('is-invalid')
  $password.val('').removeClass('is-invalid')
  $confirmPassword.val('').removeClass('is-invalid')

  $accountRoleError.text('')
  $emailError.text('')
  $usernameError.text('')
  $passwordError.text('')
  $confirmPasswordError.text('')

  displayAccountRoles()
}

window.resetMemberForm = () => {
  $('#membershipTypeUI').val('').removeClass('is-invalid')
  $membershipType.val('').removeClass('is-invalid')
  $memberFirstName.val('').removeClass('is-invalid')
  $memberMiddleName.val('')
  $memberLastName.val('').removeClass('is-invalid')
  $memberContactNumber.val('').removeClass('is-invalid')
  $memberHouseAddress.val('').removeClass('is-invalid')
  $memberBarangay.val('').removeClass('is-invalid')
  $memberMunicipality.val('').removeClass('is-invalid')
  $memberProvince.val('').removeClass('is-invalid')
  $memberRegion.val('').removeClass('is-invalid')

  $membershipTypeError.text('')
  $memberFirstNameError.text('')
  $memberMiddleNameError.text('')
  $memberLastNameError.text('')
  $memberContactNumberError.text('')
  $memberHouseAddressError.text('')
  $memberBarangayError.text('')
  $memberMunicipalityError.text('')
  $memberProvinceError.text('')
  $memberRegionError.text('')
}

window.resetEmployeeForm = () => {
  $employeeFirstName.val('').removeClass('is-invalid')
  $employeeMiddleName.val('')
  $employeeLastName.val('').removeClass('is-invalid')
  $employeeContactNumber.val('').removeClass('is-invalid')
  $employeeSalary.val('').removeClass('is-invalid')
  $employeeRata.val('').removeClass('is-invalid')

  $employeeFirstNameError.text('')
  $employeeMiddleNameError.text('')
  $employeeLastNameError.text('')
  $employeeContactNumberError.text('')
  $employeeSalaryError.text('')
  $employeeRataError.text('')
}

// Reset button click handler
$resetBtn.on('click', function () {
  resetAccountForm()
  //reset nalang lahat pukengnang yan
  //   if (forAccount === 'member') {
  //     resetMemberForm()
  //   } else {
  //     resetEmployeeForm()
  //   }
  resetMemberForm()
  resetEmployeeForm()
})

// Create account button click handler
// Add role detection function
window.isMemberRole = () => {
  const selectedRole = $('#accountRole option:selected').text()
  return selectedRole === 'Member'
}

$(document).ready(function () {
  // Update click handler to use dynamic role check
  $createAccountBtn.on('click', async function () {
    const originalButtonText = $createAccountBtn.text()
    let isValid = true
    const formData = {
      action:
        getURLParams('mode') === 'create'
          ? isMemberRole()
            ? 'create_member_cooperative'
            : 'create_employee_cooperative'
          : isMemberRole() //update
          ? 'update_member_cooperative'
          : 'update_employee_cooperative',
      data: {
        page_from: getHashFragment(),
        role_id: null,
        email: '',
        username: '',
        password: '',
        confirm_password: ''
      }
    }
    if (getURLParams('mode') === 'update') {
      formData.data.account_id = getURLParams('account_id')
      formData.data.member_id = getURLParams('member_id')
      formData.data.employee_id = getURLParams('employee_id')
    }

    // console.log('create-member.js', formData)
    // toastr.info('test')
    // return

    // Validate account information
    if (!validateRequiredField($accountRole.val())) {
      $accountRole.addClass('is-invalid')
      $accountRoleError.text('Please select an account role')
      isValid = false
    } else {
      formData.data.role_id = parseInt($accountRole.val())
    }

    // Email validation using async isValidEmail
    const emailValidation = await isValidEmail($email.val())
    if (!emailValidation) {
      $email.addClass('is-invalid')
      $emailError.text('Please enter a valid email')
      isValid = false
    } else {
      formData.data.email = $email.val()
    }

    if (!validateRequiredField($username.val())) {
      $username.addClass('is-invalid')
      $usernameError.text('Please enter a username')
      isValid = false
    } else {
      formData.data.username = $username.val()
    }

    //only applied bvaldiation dito sa password if creation
    //pero pag update wagna, or if my prinovide na password
    if (getURLParams('mode') === 'create' || !isEmpty($password.val())) {
      if (!validatePassword($password.val())) {
        $password.addClass('is-invalid')
        $passwordError.text('Password must be at least 8 characters long')
        isValid = false
      } else {
        formData.data.password = $password.val()
      }

      if ($password.val() !== $confirmPassword.val()) {
        $confirmPassword.addClass('is-invalid')
        $confirmPasswordError.text('Passwords do not match')
        isValid = false
      } else {
        formData.data.confirm_password = $confirmPassword.val()
      }
    }

    if (isMemberRole()) {
      // Validate member information
      if (!validateRequiredField($membershipType.val())) {
        $('#membershipTypeUI').addClass('is-invalid')
        $membershipTypeError.text('Please select a membership type')
        isValid = false
      } else {
        formData.data.type_id = parseInt($membershipType.val())
      }

      if (!validateRequiredField($memberFirstName.val())) {
        $memberFirstName.addClass('is-invalid')
        $memberFirstNameError.text('Please enter first name')
        isValid = false
      } else {
        formData.data.first_name = $memberFirstName.val()
      }

      formData.data.middle_name = $memberMiddleName.val()

      if (!validateRequiredField($memberLastName.val())) {
        $memberLastName.addClass('is-invalid')
        $memberLastNameError.text('Please enter last name')
        isValid = false
      } else {
        formData.data.last_name = $memberLastName.val()
      }

      if (!validateContactNumber($memberContactNumber.val())) {
        $memberContactNumber.addClass('is-invalid')
        $memberContactNumberError.text(
          'Please enter a valid 10-digit contact number'
        )
        isValid = false
      } else {
        formData.data.contact_number = '+63' + $memberContactNumber.val()
      }

      if (!validateRequiredField($memberHouseAddress.val())) {
        $memberHouseAddress.addClass('is-invalid')
        $memberHouseAddressError.text('Please enter house address')
        isValid = false
      } else {
        formData.data.house_address = $memberHouseAddress.val()
      }

      if (!validateRequiredField($memberBarangay.val())) {
        $memberBarangay.addClass('is-invalid')
        $memberBarangayError.text('Please enter barangay')
        isValid = false
      } else {
        formData.data.barangay = $memberBarangay.val()
      }

      if (!validateRequiredField($memberMunicipality.val())) {
        $memberMunicipality.addClass('is-invalid')
        $memberMunicipalityError.text('Please enter municipality')
        isValid = false
      } else {
        formData.data.municipality = $memberMunicipality.val()
      }

      if (!validateRequiredField($memberProvince.val())) {
        $memberProvince.addClass('is-invalid')
        $memberProvinceError.text('Please enter province')
        isValid = false
      } else {
        formData.data.province = $memberProvince.val()
      }

      if (!validateRequiredField($memberRegion.val())) {
        $memberRegion.addClass('is-invalid')
        $memberRegionError.text('Please enter region')
        isValid = false
      } else {
        formData.data.region = $memberRegion.val()
      }
    } else {
      // Validate employee information
      if (!validateRequiredField($employeeFirstName.val())) {
        $employeeFirstName.addClass('is-invalid')
        $employeeFirstNameError.text('Please enter first name')
        isValid = false
      } else {
        $employeeFirstName.removeClass('is-invalid')
        formData.data.first_name = $employeeFirstName.val()
      }

      formData.data.middle_name = $employeeMiddleName.val()

      if (!validateRequiredField($employeeLastName.val())) {
        $employeeLastName.addClass('is-invalid')
        $employeeLastNameError.text('Please enter last name')
        isValid = false
      } else {
        $employeeLastName.removeClass('is-invalid')
        formData.data.last_name = $employeeLastName.val()
      }

      const contactNumber = $employeeContactNumber.val()
      if (!validateContactNumber(contactNumber)) {
        $employeeContactNumber.addClass('is-invalid')
        $employeeContactNumberError.text(
          'Please enter a valid 10-digit contact number'
        )
        isValid = false
      } else {
        $employeeContactNumber.removeClass('is-invalid')
        formData.data.contact_number = '+63' + contactNumber
      }

      const salary = $employeeSalary.val()
      if (!validateNumericField(salary)) {
        $employeeSalary.addClass('is-invalid')
        $employeeSalaryError.text('Please enter a valid salary amount')
        isValid = false
      } else {
        $employeeSalary.removeClass('is-invalid')
        formData.data.salary = parseFloat(salary)
      }

      const rata = $employeeRata.val()
      if (!validateNumericField(rata)) {
        $employeeRata.addClass('is-invalid')
        $employeeRataError.text('Please enter a valid rata amount')
        isValid = false
      } else {
        $employeeRata.removeClass('is-invalid')
        formData.data.rata = parseFloat(rata)
      }
    }

    if (isValid) {
      $createAccountBtn
        .text('Submitting...')
        .prop('disabled', true)
        .css('cursor', 'no-drop')

      try {
        // Add validated data to formData
        formData.data.role_id = parseInt($accountRole.val())
        formData.data.email = $email.val()
        formData.data.username = $username.val()
        formData.data.password = $password.val()
        formData.data.confirm_password = $confirmPassword.val()

        if (isMemberRole()) {
          formData.data.type_id = parseInt($membershipType.val())
          formData.data.first_name = $memberFirstName.val()
          formData.data.middle_name = $memberMiddleName.val()
          formData.data.last_name = $memberLastName.val()
          formData.data.contact_number = '+63' + $memberContactNumber.val()
          formData.data.house_address = $memberHouseAddress.val()
          formData.data.barangay = $memberBarangay.val()
          formData.data.municipality = $memberMunicipality.val()
          formData.data.province = $memberProvince.val()
          formData.data.region = $memberRegion.val()
        } else {
          formData.data.first_name = $employeeFirstName.val()
          formData.data.middle_name = $employeeMiddleName.val()
          formData.data.last_name = $employeeLastName.val()
          formData.data.contact_number = '+63' + $employeeContactNumber.val()
          formData.data.salary = parseFloat($employeeSalary.val())
          formData.data.rata = parseFloat($employeeRata.val())
        }

        // console.log(formData)
        // toastr.info('test')
        // return
        const response =
          getURLParams('mode') === 'create'
            ? isMemberRole()
              ? await createMemberCooperative(formData)
              : await createEmployeeCooperative(formData)
            : isMemberRole()
            ? await updateMemberCooperative(formData)
            : await updateEmployeeCooperative(formData)

        if (response.success) {
          toastr.success(response.message)
          if (getURLParams('mode') === 'create') {
            resetAccountForm()
            resetMemberForm()
            resetEmployeeForm()
          }
          await dislayAccounts()
        } else {
          toastr.error(response.message || `Failed to ${mode} account`)
        }
      } catch (error) {
        console.error(`Error:`, error)
        //toastr.error('An error occurred while processing request')
      } finally {
        // Restore button to original state
        $createAccountBtn
          .html(
            `<i class="fas fa-save me-2"></i> <span id="accountModeTextBtn">${getURLParams(
              'mode'
            )}</span> Account`
          )
          .prop('disabled', false)
          .css('cursor', 'pointer')
      }
    }
  })

  // Add input event listeners to clear validation errors
  $accountRole.on('change', function () {
    $(this).removeClass('is-invalid')
    $accountRoleError.text('')
  })

  $email.on('input', function () {
    $(this).removeClass('is-invalid')
    $emailError.text('')
  })

  $username.on('input', function () {
    $(this).removeClass('is-invalid')
    $usernameError.text('')
  })

  $password.on('input', function () {
    $(this).removeClass('is-invalid')
    $passwordError.text('')
  })

  $confirmPassword.on('input', function () {
    $(this).removeClass('is-invalid')
    $confirmPasswordError.text('')
  })

  // Member form field listeners
  $membershipType.on('change', function () {
    $(this).removeClass('is-invalid')
    $membershipTypeError.text('')
  })

  $memberFirstName.on('input', function () {
    $(this).removeClass('is-invalid')
    $memberFirstNameError.text('')
  })

  $memberMiddleName.on('input', function () {
    $(this).removeClass('is-invalid')
    $memberMiddleNameError.text('')
  })

  $memberLastName.on('input', function () {
    $(this).removeClass('is-invalid')
    $memberLastNameError.text('')
  })

  $memberContactNumber.on('input', function () {
    $(this).removeClass('is-invalid')
    $memberContactNumberError.text('')
  })

  $memberHouseAddress.on('input', function () {
    $(this).removeClass('is-invalid')
    $memberHouseAddressError.text('')
  })

  $memberBarangay.on('input', function () {
    $(this).removeClass('is-invalid')
    $memberBarangayError.text('')
  })

  $memberMunicipality.on('input', function () {
    $(this).removeClass('is-invalid')
    $memberMunicipalityError.text('')
  })

  $memberProvince.on('input', function () {
    $(this).removeClass('is-invalid')
    $memberProvinceError.text('')
  })

  $memberRegion.on('input', function () {
    $(this).removeClass('is-invalid')
    $memberRegionError.text('')
  })

  // Employee form field listeners
  $employeeFirstName.on('input', function () {
    $(this).removeClass('is-invalid')
    $employeeFirstNameError.text('')
  })

  $employeeMiddleName.on('input', function () {
    $(this).removeClass('is-invalid')
    $employeeMiddleNameError.text('')
  })

  $employeeLastName.on('input', function () {
    $(this).removeClass('is-invalid')
    $employeeLastNameError.text('')
  })

  $employeeContactNumber.on('input', function () {
    $(this).removeClass('is-invalid')
    $employeeContactNumberError.text('')
  })

  $employeeSalary.on('input', function () {
    $(this).removeClass('is-invalid')
    $employeeSalaryError.text('')
  })

  $employeeRata.on('input', function () {
    $(this).removeClass('is-invalid')
    $employeeRataError.text('')
  })
})
