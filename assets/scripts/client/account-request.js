$(document).ready(async function () {
  //member info
  const membershipTypesSelect = $('#membershipType')
  const membershipTypeUI = $('#membershipTypeUI')
  const firstName = $('#firstName')
  const lastName = $('#lastName')
  const middleName = $('#middleName')
  const contactNumber = $('#contactNumber')
  const houseAddress = $('#houseAddress')
  const barangay = $('#barangay')
  const municipality = $('#municipality')
  const province = $('#province')
  const region = $('#region')
  //account info
  const accountRole = $('#accountRole')
  const username = $('#username')
  const email = $('#email')
  const password = $('#password')
  const confirmPassword = $('#confirmPassword')
  try {
    LoadingManager.show($('.ar-container'))
    const membershipTypes = await fetchMembershipTypes()
    //console.log(membershipTypes)
    if (membershipTypes.success) {
      membershipTypesSelect.empty()
      membershipTypeUI.val('')
      membershipTypesSelect.append('<option value="" selected></option>')
      membershipTypes.data.forEach((type) => {
        membershipTypesSelect.append(
          `<option value="${type.type_id}">${type.type_name}</option>`
        )
      })

      membershipTypesSelect.on('change', function () {
        const selectedMembershipTypeName = $(this).find(':selected').text()
        membershipTypeUI.val(selectedMembershipTypeName)
      })
    }
  } catch (error) {
    console.error(error)
  } finally {
    LoadingManager.hide($('.ar-container'))
  }

  contactNumber.on('input', function () {
    let value = $(this).val()
    value = value.replace(/\D/g, '')
    if (value.length > 10) {
      value = value.slice(0, 10)
    }
    $(this).val(value)
  })

  $('#togglePassword').click(function () {
    const passwordField = $('#confirmPassword')
    const passwordFieldType = passwordField.attr('type')
    const eyeIcon = $('.eye-icon')

    if (passwordFieldType === 'password') {
      passwordField.attr('type', 'text')
      eyeIcon.removeClass('fa-eye-slash').addClass('fa-eye')
    } else {
      passwordField.attr('type', 'password')
      eyeIcon.removeClass('fa-eye').addClass('fa-eye-slash')
    }
  })

  function validateFields() {
    const requiredFields = [
      membershipTypesSelect,
      firstName,
      lastName,
      contactNumber,
      houseAddress,
      barangay,
      municipality,
      province,
      region,
      accountRole,
      username,
      email,
      password,
      confirmPassword
    ]

    for (let i = 0; i < requiredFields.length; i++) {
      if (isEmpty(requiredFields[i].val())) {
        return false
      }
    }

    return true
  }

  $('.request-btn').click(async function () {
    const isValid = validateFields()
    if (!isValid) {
      toastr.warning('Please fill in all required fields.')
      return
    }

    const grecaptchaValue = $('#g-recaptcha-response').val()
    let grecaptchaResponse = grecaptcha.getResponse()
    if (isEmpty(grecaptchaValue)) {
      toastr.warning('Please complete the reCAPTCHA!')
      return
    }

    const emailValidation = await isValidEmail(email.val())
    if (!emailValidation) {
      toastr.warning('Invalid email address.')
      return
    }

    const validatePassword = function (password) {
      const hasUpperCase = /[A-Z]/.test(password)
      const hasLowerCase = /[a-z]/.test(password)
      const hasNumber = /\d/.test(password)
      const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>[\]\/\\]/.test(password)
      const isValidLength = password.length >= 8

      return (
        hasUpperCase &&
        hasLowerCase &&
        hasNumber &&
        hasSpecialChar &&
        isValidLength
      )
    }

    const passwordValidation = validatePassword(password.val())
    if (!passwordValidation) {
      toastr.warning(
        'Password must contain at least 8 characters, including uppercase, lowercase, numbers, and special characters.'
      )
      return
    }

    //check password match
    if (password.val() !== confirmPassword.val()) {
      toastr.warning('Passwords do not match.')
      return
    }

    const formData = {
      action: 'create_member_cooperative',
      data: {
        type_id: membershipTypesSelect.val(),
        first_name: firstName.val(),
        last_name: lastName.val(),
        middle_name: middleName.val(),
        contact_number: `+63${contactNumber.val()}`,
        house_address: houseAddress.val(),
        barangay: barangay.val(),
        municipality: municipality.val(),
        province: province.val(),
        region: region.val(),
        role_id: accountRole.data('id'),
        username: username.val(),
        email: email.val(),
        password: password.val(),
        confirm_password: confirmPassword.val(),
        page_from: getHashFragment(),
        recaptcha_response: grecaptchaResponse
      }
    }
    //console.log(formData)
    try {
      //LoadingManager.show($('.ar-container'))
      $(this).text('Requesting...').prop('disabled', true)
      const response = await createMemberCooperative(formData)
      console.log(response)
      if (response.success) {
        toastr.success(response.message)
        //reset form
        grecaptcha.reset()
        membershipTypesSelect.val('')
        membershipTypeUI.val('')
        firstName.val('')
        lastName.val('')
        middleName.val('')
        contactNumber.val('')
        houseAddress.val('')
        barangay.val('')
        municipality.val('')
        province.val('')
        region.val('')
        //accountRole.val('')
        username.val('')
        email.val('')
        password.val('')
        confirmPassword.val('')
        $('.eye-icon').removeClass('fa-eye').addClass('fa-eye-slash')
        $('#togglePassword').prop('checked', false)
        grecaptcha.reset()
      }
    } catch (error) {
      grecaptcha.reset()
      console.error(error)
    } finally {
      //LoadingManager.hide($('.ar-container'))
      $(this).text('Request').prop('disabled', false)
    }
  })
})
