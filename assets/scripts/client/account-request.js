$(document).ready(async function () {
  // Profile picture upload functionality
  const $profilePictureInput = $('#cmProfilePicture')
  const $profilePreview = $('#profilePreview')
  const $profileUploadBtn = $('#profileUploadBtn')
  const $profileRemoveBtn = $('#profileRemoveBtn')
  const defaultProfileSrc = '/assets/images/default-profile.png'

  // Handle file selection
  $profilePictureInput.on('change', function (e) {
    if (this.files && this.files[0]) {
      const reader = new FileReader()

      reader.onload = function (e) {
        // Update preview image with selected file
        $profilePreview.attr('src', e.target.result)
        // Show remove button and hide upload button
        $profileUploadBtn.addClass('hidden')
        $profileRemoveBtn.removeClass('hidden')
      }

      reader.readAsDataURL(this.files[0])
    }
  })

  function resetProfilePicture() {
    $profilePictureInput.val('')
    $profilePreview.attr('src', defaultProfileSrc)
    $profileUploadBtn.removeClass('hidden')
    $profileRemoveBtn.addClass('hidden')
  }

  // Handle remove button click
  $profileRemoveBtn.on('click', resetProfilePicture)

  const $cmConfirmPassword = $('#cmConfirmPassword')

  //password toggle
  $('#togglePassword').click(function () {
    const passwordFieldType = $cmConfirmPassword.attr('type')
    const eyeIcon = $('.eye-icon')

    if (passwordFieldType === 'password') {
      $cmConfirmPassword.attr('type', 'text')
      eyeIcon.removeClass('fa-eye-slash').addClass('fa-eye')
    } else {
      $cmConfirmPassword.attr('type', 'password')
      eyeIcon.removeClass('fa-eye').addClass('fa-eye-slash')
    }
  })

  const $cmSexUI = $('#cmSexUI')
  const $cmSex = $('#cmSex')
  $cmSex.on('change', function () {
    //const selectedSex = $(this).val()
    const selectedSexLabel = $(this).find('option:selected').text()
    $cmSexUI.val(selectedSexLabel)
  })

  //membership type card selection
  function handleCooperativeAccountSelection() {
    // Get the hidden input field
    const $selectedAccountsInput = $('#cmSelectedCooperativeAccounts')

    // Get initial selected values
    let selectedAccounts = $selectedAccountsInput.val()
      ? $selectedAccountsInput.val().split(',')
      : []

    // Mark initially selected checkboxes
    selectedAccounts.forEach((id) => {
      $(`.servicesOffer input[data-id="${id}"]`).prop('checked', true)
      $(`.servicesOffer input[data-id="${id}"]`)
        .closest('label')
        .addClass('selected')
    })

    // Add event listener to all checkboxes
    $('.servicesOffer input[type="checkbox"]').on('change', function () {
      const accountId = $(this).data('id').toString()
      const $label = $(this).closest('label')

      if ($(this).is(':checked')) {
        // Add to selected accounts if not already included
        if (!selectedAccounts.includes(accountId)) {
          selectedAccounts.push(accountId)
        }
        $label.addClass('selected')
      } else {
        // Remove from selected accounts
        selectedAccounts = selectedAccounts.filter((id) => id !== accountId)
        $label.removeClass('selected')
      }

      // Update the hidden input with comma-separated values
      $selectedAccountsInput.val(selectedAccounts.join(','))
    })

    // Update the alert message
    $('.servicesOffer .alert-light').html(
      '<small><span class="fw-bold text-danger">*</span> Please select the cooperative account types you wish to open. You can choose multiple account types based on your needs. Each selection will determine the services available to your membership.</small>'
    )
  }

  const $cmContactNumber = $('#cmContactNumber')
  $cmContactNumber.on('input', function () {
    let value = $(this).val()
    value = value.replace(/\D/g, '')
    if (value.length > 10) {
      value = value.slice(0, 10)
    }
    $(this).val(value)
  })

  const $cmEmail = $('#cmEmail')
  const $cmUsername = $('#cmUsername')
  const $cmPassword = $('#cmPassword')
  const $cmFirstName = $('#cmFirstName')
  const $cmMiddleName = $('#cmMiddleName')
  const $cmLastName = $('#cmLastName')

  const $cmHouseAddress = $('#cmHouseAddress')
  const $cmBarangay = $('#cmBarangay')
  const $cmMunicipality = $('#cmMunicipality')
  const $cmProvince = $('#cmProvince')
  const $cmRegion = $('#cmRegion')

  const $cmAccountRole = $('#cmAccountRole')
  const $cmSelectedCooperativeAccounts = $('#cmSelectedCooperativeAccounts')

  function resetMemberRegistrationForm() {
    resetProfilePicture()
    $cmEmail.val('')
    $cmUsername.val('')
    $cmPassword.val('')
    $cmConfirmPassword.val('')

    $cmFirstName.val('')
    $cmMiddleName.val('')
    $cmLastName.val('')
    $cmSexUI.val('')
    $cmSex.val('')
    $cmContactNumber.val('')
    $cmHouseAddress.val('')
    $cmBarangay.val('')
    $cmMunicipality.val('')
    $cmProvince.val('')
    $cmRegion.val('')
    $('.eye-icon').removeClass('fa-eye').addClass('fa-eye-slash')
    $('#togglePassword').prop('checked', false)
    $('.servicesOffer input[type="checkbox"]').prop('checked', false)
    $('.servicesOffer input[type="checkbox"]')
      .closest('label')
      .removeClass('selected')
    $cmSelectedCooperativeAccounts.val('')
    $('.servicesOffer.alert-light').html(
      '<small><span class="fw-bold text-danger">*</span> Please select the cooperative account types you wish to open. You can choose multiple account types based on your needs. Each selection will determine the services available to your membership.</small>'
    )
    grecaptcha.reset()
  }

  const $clearFormMemberRegistrationBtn = $('#clearFormMemberRegistrationBtn')
  //$clearFormMemberRegistrationBtn.on('click', resetMemberRegistrationForm)

  function validateRequiredFields() {
    const requiredFields = [
      $cmAccountRole,
      $cmEmail,
      $cmUsername,
      $cmPassword,
      $cmConfirmPassword,
      $cmFirstName,
      //$cmMiddleName,
      $cmLastName,
      $cmSex,
      $cmContactNumber,
      $cmHouseAddress,
      $cmBarangay,
      $cmMunicipality,
      $cmProvince,
      $cmRegion,
      $cmSelectedCooperativeAccounts
    ]

    for (let i = 0; i < requiredFields.length; i++) {
      if (isEmpty(requiredFields[i].val())) {
        return false
      }
    }

    return true
  }

  const $submitRegisterMemberBtn = $('#submitRegisterMemberBtn')
  $submitRegisterMemberBtn.on('click', async function () {
    if (!validateRequiredFields()) {
      toastr.warning('Please fill in all required fields.')
      return
    }

    const emailValidation = await isValidEmail($cmEmail.val())
    if (!emailValidation) {
      toastr.warning('Invalid email address.')
      return
    }

    const passwordValidation = validatePasswordStrict($cmPassword.val())
    if (!passwordValidation) {
      toastr.warning(
        'Password must contain at least 8 characters, including uppercase, lowercase, numbers, and special characters.'
      )
      return
    }

    const grecaptchaValue = $('#g-recaptcha-response').val()
    let grecaptchaResponse = grecaptcha.getResponse()
    if (isEmpty(grecaptchaValue)) {
      toastr.warning('Please complete the reCAPTCHA!')
      return
    }

    if ($cmPassword.val() !== $cmConfirmPassword.val()) {
      toastr.warning('Passwords do not match.')
      return
    }

    const profilePicture = $profilePictureInput[0].files[0]
    if (profilePicture) {
      const allowedExtensions = ['jpg', 'jpeg', 'png']
      const fileExtension = profilePicture.name.split('.').pop().toLowerCase()
      if (!allowedExtensions.includes(fileExtension)) {
        toastr.warning(
          'Invalid profile picture format. Only JPG, JPEG, and PNG files are allowed.'
        )
        return
      }
    }

    const formData = new FormData()
    // Account information
    formData.append('profile_picture', profilePicture)
    formData.append('role_id', $cmAccountRole.data('id'))
    formData.append('email', $cmEmail.val())
    formData.append('username', $cmUsername.val())
    formData.append('password', $cmPassword.val())
    formData.append('confirm_password', $cmConfirmPassword.val())
    // Member information
    formData.append('first_name', $cmFirstName.val())
    formData.append('middle_name', $cmMiddleName.val())
    formData.append('last_name', $cmLastName.val())
    formData.append('sex', $cmSex.val())
    formData.append('contact_number', `+63${$cmContactNumber.val()}`)
    formData.append('house_address', $cmHouseAddress.val())
    formData.append('barangay', $cmBarangay.val())
    formData.append('municipality', $cmMunicipality.val())
    formData.append('province', $cmProvince.val())
    formData.append('region', $cmRegion.val())
    // Cooperative accounts
    formData.append('selected_caids', $cmSelectedCooperativeAccounts.val())
    formData.append('page_from', getHashFragment())
    formData.append('recaptcha_response', grecaptchaResponse)

    try {
      $(this).prop('disabled', true).text('Processing...')

      const response = await registerMember(formData)
      if (response.success) {
        toastr.success(response.message)
        resetMemberRegistrationForm()
      }
    } catch (error) {
      console.error('Registration error:', error)
    } finally {
      $(this).prop('disabled', false).text('Register')
    }
  })

  handleCooperativeAccountSelection()
})
