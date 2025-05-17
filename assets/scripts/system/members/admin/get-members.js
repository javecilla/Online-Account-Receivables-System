$(document).ready(async function () {
  const swalBase = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-light action-btn',
      cancelButton: 'btn btn-light action-btn',
      showLoaderOnConfirm: 'swal2-loader'
    },
    buttonsStyling: false
  })
  const $registeredMembersModalTitle = $('#registeredMembersModalTitle')

  $('#refreshMemberContentTableBtn').click(async function () {
    LoadingManager.show($('.main-content'))
    $(this).text('Refreshing...').prop('disabled', true)
    await Promise.all([
      displayRegisteredMembers(),
      displayPendingMembers(),
      displayMembersSelection()
    ])
    $(this)
      .html('<i class="fas fa-sync-alt me-2"></i>Refresh')
      .prop('disabled', false)
    LoadingManager.hide($('.main-content'))
  })

  const $registeredMembersTable = $('#registeredMembersTable')
  window.displayRegisteredMembers = async function () {
    try {
      const response = await fetchMembersByApprovalStatus('approved')
      if (response.success) {
        DataTableRegisteredMembers($registeredMembersTable, response.data)
      }
    } catch (e) {
      console.error(e)
    }
  }
  $registeredMembersTable.on('click', '.edit-btn', async function () {
    const memberId = $(this).data('member-id')
    const accountId = $(this).data('account-id')
    addURLParams('tab', 'registered')
    addURLParams('mode', 'update')
    addURLParams('member_id', memberId)
    addURLParams('account_id', accountId)
    setHashFragment('internal')
    await handlePageContent()
  })

  $registeredMembersTable.on('click', '.view-btn', async function () {
    const memberId = $(this).data('member-id')
    const accountId = $(this).data('account-id')
    addURLParams('tab', 'registered')
    addURLParams('mode', 'view')
    addURLParams('member_id', memberId)
    addURLParams('account_id', accountId)
    setHashFragment('internal')
    await handlePageContent()
  })

  const $registerNewMemberBtn = $('#registerNewMemberBtn')
  $registerNewMemberBtn.on('click', async function () {
    addURLParams('tab', 'registered')
    addURLParams('mode', 'create')
    setHashFragment('internal')
    await handlePageContent()
  })

  const $applySerivcesBtn = $('#applySerivcesBtn')
  $applySerivcesBtn.on('click', async function () {
    handleAMCooperativeAccountSelection()
    openModal('#applyServicesModal')
  })

  const $selectedServicesInput = $('#amSelectedCooperativeAccounts')
  function handleAMCooperativeAccountSelection() {
    // Reset all services to visible and enabled first
    $('.servicesApply input[type="checkbox"]')
      .prop('disabled', false)
      .prop('checked', false)
    $('.servicesApply .soSelection').removeClass('disabled selected').show()

    // Clear the member selection dropdown
    $applyServicesMember.val('').trigger('change.select2')

    // Reset the selected accounts
    let selectedAccounts = $selectedServicesInput.val()
      ? $selectedServicesInput.val().split(',')
      : []

    // Clear the selected accounts input
    $selectedServicesInput.val('')
    selectedAccounts = []

    // Remove previous event handlers to avoid duplicates
    $('.servicesApply input[type="checkbox"]').off('change')

    // Add event handler for checkbox changes
    $('.servicesApply input[type="checkbox"]').on('change', function () {
      const accountId = $(this).data('id').toString()
      const $label = $(this).closest('label')
      const isChecked = $(this).is(':checked')

      if (isChecked) {
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

      $selectedServicesInput.val(selectedAccounts.join(','))
    })

    // Set initial message
    $('.servicesApply .alert-light').html(
      '<small><span class="fw-bold text-danger">*</span> Please select a member first to see available services.</small>'
    )
  }

  function resetApplyServicesForm() {
    $('.aMemberProfile').attr('src', '/assets/images/default-profile.png')
    $('.aMemberName').text('Member Name')
    $('.aMemberUID').text('M267xxx')
    $('.aMemberEmail').text('member@gmail.com')
    $('.aMemberContact').text('+639772465xxx')
    $('.aMemberAddress').text('Member Full Address, Bulacan, Region 3')
    // Reset checkboxes and clear selected services input
    $('.servicesApply input[type="checkbox"]')
      .prop('disabled', false)
      .prop('checked', false)
    $('.servicesApply .soSelection').removeClass('disabled').show()
    $('.servicesApply .alert-light').html(
      '<small><span class="fw-bold text-danger">*</span> Please select a member first to see available services.</small>'
    )
    $('.amServices').addClass('d-none')
    $applyServicesMember.val('').trigger('change.select2')
    $selectedServicesInput.val('')
  }

  const $applyServicesMember = $('#applyServicesMember')
  window.displayMembersSelection = async function () {
    try {
      const response = await fetchRegisteredMembers()
      if (response.success) {
        //console.log(response.data)
        $applyServicesMember.empty()
        $applyServicesMember.append(
          '<option value="">Select a member...</option>'
        )

        response.data.forEach((member) => {
          $applyServicesMember.append(
            `<option value="${member.member_id}">${member.full_name} (${member.member_uid})</option>`
          )
        })

        $applyServicesMember.select2({
          placeholder: 'Select a member...',
          allowClear: true,
          width: '100%',
          dropdownCssClass: 'custom-select2-dropdown',
          dropdownParent: $('#applyServicesModal')
          //theme: 'bootstrap-5'
        })
      }
    } catch (e) {
      console.error(e)
    }
  }

  $applyServicesMember.on('change', async function () {
    const memberId = $(this).val()
    // Reset checkboxes and clear selected services input whenever selection changes
    $('.servicesApply input[type="checkbox"]')
      .prop('disabled', false)
      .prop('checked', false)
    $('.servicesApply .soSelection').removeClass('disabled selected').show()
    $selectedServicesInput.val('')

    if (memberId) {
      const response = await fetchRegisteredMember(memberId)
      if (response.success) {
        $('.amServices').removeClass('d-none')
        // Display member information
        const memberInfo = response.data
        $('.aMemberProfile').attr(
          'src',
          `/storage/uploads/profiles/${memberInfo.profile_image}`
        )
        $('.aMemberName').text(memberInfo.full_name)
        $('.aMemberUID').text(memberInfo.member_uid)
        $('.aMemberEmail').text(memberInfo.email)
        $('.aMemberContact').text(memberInfo.contact_number)
        $('.aMemberAddress').text(memberInfo.full_address)

        // Reset all services to visible and enabled first
        $('.servicesApply input[type="checkbox"]').prop('disabled', false)
        $('.servicesApply .soSelection').removeClass('disabled').show()

        // Get the services the member already has
        const memberServices = memberInfo.cooperative_accounts.map(
          (account) => account.type_id
        )

        // If member has services, hide them completely
        if (memberServices.length > 0) {
          $('.servicesApply input[type="checkbox"]').each(function () {
            const serviceId = parseInt($(this).data('id'))
            if (memberServices.includes(serviceId)) {
              // Hide the entire selection item, not just disable it
              $(this).closest('.soSelection').hide()
            }
          })

          // Update the alert message to inform the user
          $('.servicesApply .alert-light').html(
            '<small><span class="fw-bold text-danger">*</span> Only services that this member doesn\'t already have are available for selection.</small>'
          )
        } else {
          // If member has no services, show all
          $('.servicesApply .alert-light').html(
            '<small><span class="fw-bold text-danger">*</span> Select the cooperative services you want to activate for this member. This member currently has no active services.</small>'
          )
        }
      }
    } else {
      // If no member is selected, reset the form
      resetApplyServicesForm()
    }
  })

  $('#applyServicesSubmitBtn').click(async function () {
    const memberId = $applyServicesMember.val()
    const selectedServices = $selectedServicesInput.val()
    if (isEmpty(memberId) || isEmpty(selectedServices)) {
      toastr.warning('Please select a member and at least one service.')
      return
    }
    try {
      $(this).text('Processing...').prop('disabled', true)
      const response = await applyServicesToMember({
        member_id: memberId,
        selected_caids: selectedServices
      })
      if (response.success) {
        toastr.success(response.message)
        resetApplyServicesForm()
        closeModal('#applyServicesModal')
      }
    } catch (e) {
      console.error(e)
    } finally {
      $(this).text('Submit').prop('disabled', false)
    }
  })

  $('#applyServicesCloseModalBtn').click(function () {
    resetApplyServicesForm()
    closeModal('#applyServicesModal')
  })

  const $pendingMembersTable = $('#pendingMembersTable')
  window.displayPendingMembers = async function () {
    try {
      const response = await fetchMembersByApprovalStatus('pending,rejected')
      if (response.success) {
        DataTablePendingMembers($pendingMembersTable, response.data)
      }
    } catch (e) {
      console.error(e)
    }
  }

  $pendingMembersTable.on('click', '.view-btn', async function () {
    const memberId = $(this).data('member-id')
    const accountId = $(this).data('account-id')
    //toastr.info(`Viewing member ${memberId}...`)
    LoadingManager.show($('.main-content'))
    const response = await fetchMember(memberId)
    if (response.success) {
      //console.log(response.data)
      const memberInfo = response.data
      if (memberInfo.profile_image) {
        const profileImagePath = `/storage/uploads/profiles/${memberInfo.profile_image}`
        $('#rvMemberProfileImage').attr('src', profileImagePath)
      } else {
        $('#rvMemberProfileImage').attr('src', defaultProfileSrc)
      }

      $('#rvMemberName').text(memberInfo.full_name)
      $('#rvMemberUID').text(memberInfo.member_uid)
      $('#rvMemberEmail').text(memberInfo.email)
      $('#rvMemberContact').text(memberInfo.contact_number)
      $('#rvMemberAddress').text(memberInfo.full_address)
      $('#rvMemberApplicationDate').text(
        moment(memberInfo.member_since).format('MMMM D, YYYY')
      )

      //$('.rvServicesAvailedItem[data-type-id]').hide()
      $('.rvServicesAvailedItem[data-type-id]').addClass('d-none')
      console.log($('.rvServicesAvailedItem[data-type-id]'))

      // Only show services that the member has applied for
      if (
        memberInfo.cooperative_accounts &&
        memberInfo.cooperative_accounts.length > 0
      ) {
        memberInfo.cooperative_accounts.forEach((account) => {
          const typeId = account.type_id
          const accountItem = $(
            `.rvServicesAvailedItem[data-type-id="${typeId}"]`
          )
          if (accountItem.length > 0) {
            console.log(accountItem)
            //accountItem.show()
            accountItem.removeClass('d-none')

            // Update status text and add opened date if available
            const status =
              account.status.charAt(0).toUpperCase() + account.status.slice(1)

            // Format opened date if available
            let openedDateText = ''
            if (account.opened_date) {
              openedDateText = ` (Since ${moment(account.opened_date).format(
                'MMM D, YYYY'
              )})`
            }

            switch (typeId) {
              case 1: // Savings
                $('.sSavings').text(status + openedDateText)
                break
              case 2: // Time Deposit
                $('.sTimeDeposit').text(status + openedDateText)
                break
              case 3: // Fixed Deposit
                $('.sFixedDeposit').text(status + openedDateText)
                break
              case 6: // Loan
                $('.sLoan').text(status + openedDateText)
                break
              default:
                break
            }

            // Store account info for potential future use
            accountItem.data('account-info', account)
          }
        })
      }
    }
    LoadingManager.hide($('.main-content'))

    openModal('#requestMemberViewModal')
  })

  $pendingMembersTable.on('click', '.status-btn', async function () {
    const memberId = $(this).data('member-id')
    const accountId = $(this).data('account-id')
    const status = $(this).data('status')
    // toastr.info(
    //   `updata approval status member ${memberId}... to new status: ${status}...`
    // )
    try {
      LoadingManager.show($('.main-content'))
      const response = await updateMemberApproval({
        member_id: memberId,
        approval: status
      })
      if (response.success) {
        toastr.success(`Approval status updated successfully.`)
        await Promise.all([displayPendingMembers(), displayRegisteredMembers()])
      }
    } catch (e) {
      console.error(e)
    } finally {
      LoadingManager.hide($('.main-content'))
    }
  })

  $pendingMembersTable.on('click', '.delete-btn', async function () {
    const memberId = $(this).data('member-id')
    const accountId = $(this).data('account-id')
    //toastr.info(`Deleting member ${memberId}...`)
    swalBase.fire({
      title: 'Delete Confirmation',
      html: '<small>Are you sure you want to delete this member record? This action is cannot be undone.</small>',
      icon: 'warning',
      showCancelButton: true,
      cancelButtonText: 'Cancel',
      showConfirmButton: true,
      confirmButtonText: 'Confirm Delete',
      showLoaderOnConfirm: true,
      allowEscapeKey: false,
      allowOutsideClick: false,
      preConfirm: function (response) {
        if (!response) {
          return false
        } else {
          return new Promise(function (resolve) {
            setTimeout(async function () {
              //toastr.success('delete testing')
              try {
                const res = await deleteMember(memberId)
                if (res.success) {
                  toastr.success(res.message)
                  await displayPendingMembers()
                }
              } catch (error) {
                console.error('Error deleting account:', error)
              }
              swalBase.close()
            }, 1500) //end set timeout
          }) // end promise
        } // end else for checking response
      }, // end preconfirm
      allowOutsideClick: function () {
        !swalBase.isLoading()
      }
    }) //end swal
  })

  const $backToRegisteredMembersContent = $('.backToRegisteredMembersContent')
  $backToRegisteredMembersContent.on('click', async function () {
    const from = $(this).data('from')
    removeURLParams('tab')
    removeURLParams('mode')
    removeURLParams('member_id')
    removeURLParams('account_id')

    removeHashFragment()
    await handlePageContent()
    resetMemberRegistrationForm()
  })

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
  function handleCMCooperativeAccountSelection() {
    // Get the hidden input field
    const $selectedAccountsInput = $('#cmSelectedCooperativeAccounts')

    $('.servicesOffer .soSelection').show()

    // Get initial selected values
    let selectedAccounts = $selectedAccountsInput.val()
      ? $selectedAccountsInput.val().split(',')
      : []

    // Track changes in object format for consistency with update mode
    let accountChangesObj = {}

    // Mark initially selected checkboxes
    selectedAccounts.forEach((id) => {
      $(`.servicesOffer input[data-id="${id}"]`).prop('checked', true)
      $(`.servicesOffer input[data-id="${id}"]`)
        .closest('label')
        .addClass('selected')

      // Initialize account changes tracking
      accountChangesObj[id] = {
        caid: null, // Will be assigned by backend
        status: 'active',
        type_id: parseInt(id),
        original_status: null // New selection
      }
    })

    // Add event listener to all checkboxes
    $('.servicesOffer input[type="checkbox"]').on('change', function () {
      const accountId = $(this).data('id').toString()
      const $label = $(this).closest('label')
      const isChecked = $(this).is(':checked')

      if (isChecked) {
        // Add to selected accounts if not already included
        if (!selectedAccounts.includes(accountId)) {
          selectedAccounts.push(accountId)
        }
        $label.addClass('selected')

        // Add to account changes object
        accountChangesObj[accountId] = {
          caid: null,
          status: 'active',
          type_id: parseInt(accountId),
          original_status: null
        }
      } else {
        // Remove from selected accounts
        selectedAccounts = selectedAccounts.filter((id) => id !== accountId)
        $label.removeClass('selected')

        // Remove from account changes object
        delete accountChangesObj[accountId]
      }

      // Update the hidden input with comma-separated values for backward compatibility
      $selectedAccountsInput.val(selectedAccounts.join(','))
    })

    // Update the alert message for create mode
    $('.servicesOffer .alert-light').html(
      '<small><span class="fw-bold text-danger">*</span> Select the cooperative services you want to activate for this member.</small>'
    )

    // Update the alert message
    $('.servicesOffer .alert-light').html(
      '<small><span class="fw-bold text-danger">*</span> Please select the cooperative account types you wish to open. You can choose multiple account types based on your needs. Each selection will determine the services available to your membership.</small>'
    )
  }

  function handleUPCooperativeAccountSelection() {
    // Get the hidden input fields
    const $cmSelectedAccountsInput = $('#cmSelectedCooperativeAccounts')
    const $umSelectedAccountsInput = $('#umSelectedCooperativeAccounts')

    // Clear any previous event listeners to avoid duplicates
    $('.servicesOffer input[type="checkbox"]').off('change')

    // Get cooperative accounts data from the populated input
    let selectedAccounts = $cmSelectedAccountsInput.val()
      ? $cmSelectedAccountsInput.val().split(',')
      : []

    // Hide all service labels by default
    $('.servicesOffer .soSelection').hide()

    // Reset all checkboxes and labels
    $('.servicesOffer input[type="checkbox"]').prop('checked', false)
    $('.servicesOffer .soSelection').removeClass('selected')

    // Track changes for update - initialize with current selection
    let accountChanges = {}

    // Mark checkboxes based on active status from the data and show relevant labels
    if (window.memberData && window.memberData.cooperative_accounts) {
      window.memberData.cooperative_accounts.forEach((account) => {
        const typeId = account.type_id.toString()
        const isActive = account.status === 'active'

        // Show the label for this service
        $(`.servicesOffer .soSelection[data-type-id="${typeId}"]`).show()

        // Set checkbox state based on account status
        const $checkbox = $(`.servicesOffer input[data-id="${typeId}"]`)
        $checkbox.prop('checked', isActive)

        if (isActive) {
          $checkbox.closest('label').addClass('selected')
        }

        // Initialize account changes tracking
        accountChanges[typeId] = {
          caid: account.caid,
          status: account.status,
          type_id: account.type_id,
          original_status: account.status // Keep track of original status
        }
      })
    }

    // Store the account changes in the update input field as JSON
    $umSelectedAccountsInput.val(JSON.stringify(accountChanges))

    // Add event listener to all checkboxes for update mode
    $('.servicesOffer input[type="checkbox"]').on('change', function () {
      const typeId = $(this).data('id').toString()
      const $label = $(this).closest('label')
      const isChecked = $(this).is(':checked')

      // Update the label styling
      if (isChecked) {
        $label.addClass('selected')
      } else {
        $label.removeClass('selected')
      }

      // Update the account changes tracking
      if (accountChanges[typeId]) {
        // Existing account - update status
        accountChanges[typeId].status = isChecked ? 'active' : 'inactive'
      } else {
        // New account selection - add to tracking
        accountChanges[typeId] = {
          caid: null, // Will be assigned by backend
          status: isChecked ? 'active' : 'inactive',
          type_id: parseInt(typeId),
          original_status: null // New selection
        }
      }

      // Update the hidden input with the changes
      $umSelectedAccountsInput.val(JSON.stringify(accountChanges))
    })

    // Update the alert message for update mode
    $('.servicesOffer .alert-light').html(
      '<small><span class="fw-bold text-danger">*</span> These are your current cooperative accounts. Checking/unchecking will activate/deactivate the respective services. Unchecked services will be marked as inactive but not deleted.</small>'
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
  }

  const $clearFormMemberRegistrationBtn = $('#clearFormMemberRegistrationBtn')
  $clearFormMemberRegistrationBtn.on('click', resetMemberRegistrationForm)

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

    if ($cmPassword.val() !== $cmConfirmPassword.val()) {
      toastr.warning('Passwords do not match.')
      return
    }

    let profilePicture = null
    profilePicture = $profilePictureInput[0].files[0]
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
    formData.append('profile_picture', profilePicture || null)
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
    formData.append('selected_caids', $cmSelectedCooperativeAccounts.val())
    formData.append('page_from', getHashFragment())

    try {
      $(this).prop('disabled', true).text('Processing...')

      const response = await registerMember(formData)
      if (response.success) {
        toastr.success(response.message)
        await displayRegisteredMembers()
        resetMemberRegistrationForm()
      }
    } catch (error) {
      console.error('Registration error:', error)
    } finally {
      $(this).prop('disabled', false).text('Register')
    }
  })

  function populateMembersInformation(data) {
    // Store member data globally for access in other functions
    window.memberData = data

    // Populate profile picture
    if (data.profile_image) {
      const profileImagePath = `/storage/uploads/profiles/${data.profile_image}`
      $profilePreview.attr('src', profileImagePath)
      $profileUploadBtn.addClass('hidden')
      $profileRemoveBtn.removeClass('hidden')
    } else {
      $profilePreview.attr('src', defaultProfileSrc)
      $profileUploadBtn.removeClass('hidden')
      $profileRemoveBtn.addClass('hidden')
    }

    // Populate account information
    $cmEmail.val(data.email)
    $cmUsername.val(data.username)
    $cmAccountRole.val(data.role_name).data('id', data.role_id)

    // Clear password fields for security
    $cmPassword.val('')
    $cmConfirmPassword.val('')

    // Populate member information
    $cmFirstName.val(data.first_name)
    $cmMiddleName.val(data.middle_name)
    $cmLastName.val(data.last_name)

    // Set sex value and UI
    $cmSex.val(data.sex)
    $cmSexUI.val(data.sex === 'M' ? 'Male' : 'Female')

    // Format contact number by removing +63 prefix
    const contactNumber = data.contact_number.replace('+63', '')
    $cmContactNumber.val(contactNumber)

    // Populate address information
    $cmHouseAddress.val(data.house_address)
    $cmBarangay.val(data.barangay)
    $cmMunicipality.val(data.municipality)
    $cmProvince.val(data.province)
    $cmRegion.val(data.region)

    // Handle cooperative accounts selection
    if (data.cooperative_accounts && data.cooperative_accounts.length > 0) {
      // Extract type_ids from cooperative accounts
      const selectedTypeIds = data.cooperative_accounts.map((account) =>
        account.type_id.toString()
      )

      // Set the hidden input value with comma-separated type_ids
      $cmSelectedCooperativeAccounts.val(selectedTypeIds.join(','))

      // Call the function to handle checkbox selection
      //handleCMCooperativeAccountSelection()
      handleUPCooperativeAccountSelection()
    }
  }

  // Update Member Button Handler
  const $submitUpdateMemberBtn = $('#submitUpadteMemberBtn')
  $submitUpdateMemberBtn.on('click', async function () {
    // Validate required fields except password which is optional for updates
    const requiredUpdateFields = [
      $cmAccountRole,
      $cmEmail,
      $cmUsername,
      $cmFirstName,
      $cmLastName,
      $cmSex,
      $cmContactNumber,
      $cmHouseAddress,
      $cmBarangay,
      $cmMunicipality,
      $cmProvince,
      $cmRegion
    ]

    for (let i = 0; i < requiredUpdateFields.length; i++) {
      if (isEmpty(requiredUpdateFields[i].val())) {
        toastr.warning('Please fill in all required fields.')
        return
      }
    }

    const emailValidation = await isValidEmail($cmEmail.val())
    if (!emailValidation) {
      toastr.warning('Invalid email address.')
      return
    }

    // Only validate password if it's provided
    if (!isEmpty($cmPassword.val())) {
      const passwordValidation = validatePasswordStrict($cmPassword.val())
      if (!passwordValidation) {
        toastr.warning(
          'Password must contain at least 8 characters, including uppercase, lowercase, numbers, and special characters.'
        )
        return
      }

      if ($cmPassword.val() !== $cmConfirmPassword.val()) {
        toastr.warning('Passwords do not match.')
        return
      }
    }

    let profilePicture = null
    profilePicture = $profilePictureInput[0].files[0]
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

    const memberId = getURLParams('member_id')
    const accountId = getURLParams('account_id')
    const formData = new FormData()
    formData.append('member_id', memberId)
    formData.append('account_id', accountId)
    formData.append('profile_picture', profilePicture || null)
    formData.append('role_id', $cmAccountRole.data('id'))
    formData.append('email', $cmEmail.val())
    formData.append('username', $cmUsername.val())

    // Only include password if provided
    if (!isEmpty($cmPassword.val())) {
      formData.append('password', $cmPassword.val())
      formData.append('confirm_password', $cmConfirmPassword.val())
    }

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

    // Cooperative accounts - convert object to array before sending
    const accountChangesObj = JSON.parse(
      $('#umSelectedCooperativeAccounts').val() || '{}'
    )
    const accountChangesArray = Object.values(accountChangesObj)
    formData.append('account_changes', JSON.stringify(accountChangesArray))
    formData.append('page_from', getHashFragment())

    // console.log('FormData:', formData)
    // toastr.success('test')

    try {
      $(this).prop('disabled', true).text('Processing...')

      const response = await updateMember(formData)
      if (response.success) {
        toastr.success(response.message)
        await displayRegisteredMembers()
        // removeURLParams('tab')
        // removeURLParams('mode')
        // removeURLParams('member_id')
        // removeHashFragment()
        // await handlePageContent()
      }
    } catch (error) {
      console.error('Update error:', error)
    } finally {
      $(this).prop('disabled', false).text('Save Changes')
    }
  })

  function populateMemberPanelMetrics(
    memberInfo,
    accountBalance,
    savingsGoal,
    activeLoans,
    upcomingPayments,
    recentTransactions
  ) {
    //console.log('memberInfo:', memberInfo)
    displayMemberInformation(memberInfo)

    //console.log('accountBalance:', accountBalance)
    $('#vTotalCurrentBalanceValue').text(
      `₱${accountBalance.total_current_balance}`
    )
    $('#vCreditBalanceValue').text(`₱${accountBalance.credit_balance}`)
    $('#vTotalWithdrawalsValue').text(
      `₱${accountBalance.total_withdrawals_last_30d}`
    )

    //console.log('savingsGoal:', savingsGoal)
    const progressPercentage = Math.min(
      parseFloat(savingsGoal.savings_progress_percentage),
      100
    )
    $('#savingsProgressPercentage').text(
      `${isNaN(progressPercentage) ? 0 : progressPercentage.toFixed(2)}%`
    )
    $('#savingsProgressBar').css('width', `${progressPercentage}%`)
    $('#savingsProgressBar').attr('aria-valuenow', progressPercentage)
    $('#savingsTargetValue').text(
      formatCurrency(savingsGoal.savings_target_balance)
    )
    $('#savingsCurrentValue').text(
      formatCurrency(savingsGoal.savings_current_balance)
    )

    //console.log('activeLoans:', activeLoans)
    $('#totalActiveLoansCount').text(activeLoans.total_active_loans_count)
    $('#totalLoanAmountValue').text(`₱${activeLoans.total_loan_principal}`)
    $('#overdueAmountValue').text(`₱${activeLoans.total_overdue_amount}`)
    $('#overdueLoansCount').text(activeLoans.overdue_loans_count)

    //console.log('upcomingPayments:', upcomingPayments)
    displayUpcomingPayments(upcomingPayments)
    //console.log('recentTransactions:', recentTransactions)
    displayRecentTransactions(recentTransactions)
  }

  function displayMemberInformation(memberInfo) {
    if (memberInfo.profile_image) {
      const profileImagePath = `/storage/uploads/profiles/${memberInfo.profile_image}`
      $('#vMemberProfileImage').attr('src', profileImagePath)
    } else {
      $('#vMemberProfileImage').attr('src', defaultProfileSrc)
    }
    $('#vMemberName').text(memberInfo.full_name)
    $('#vMemberUID').text(memberInfo.member_uid)
    $('#vMemberEmail').text(memberInfo.email)
    $('#vMemberContact').text(memberInfo.contact_number)
    $('#vMemberAddress').text(memberInfo.full_address)
    $('#vMemberSince').text(
      moment(memberInfo.member_since).format('MMMM D, YYYY')
    )
    // Handle cooperative accounts display
    if (
      memberInfo.cooperative_accounts &&
      memberInfo.cooperative_accounts.length > 0
    ) {
      //$('.vServicesAvailedItem[data-type-id]').hide()
      $('.vServicesAvailedItem[data-type-id]').addClass('d-none')
      memberInfo.cooperative_accounts.forEach((account) => {
        const typeId = account.type_id
        const accountItem = $(`.vServicesAvailedItem[data-type-id="${typeId}"]`)
        if (accountItem.length > 0) {
          //accountItem.show()
          accountItem.removeClass('d-none')
          if (account.opened_date) {
            const formattedDate = moment(account.opened_date).format(
              'MMMM D, YYYY'
            )

            switch (typeId) {
              case 1: // Savings
                $('.doSavings').text(formattedDate)
                break
              case 2: // Time Deposit
                $('.doTimeDeposit').text(formattedDate)
                break
              case 3: // Fixed Deposit
                $('.doFixedDeposit').text(formattedDate)
                break
              case 6: // Loan
                $('.doLoan').text(formattedDate)
                break
              default:
                break
            }
            // Store account info for potential future use
            accountItem.data('account-info', account)
          }
        }
      })

      $('.vServicesAvailedItem[data-type-id]')
        .off('click')
        .on('click', function () {
          const accountInfo = $(this).data('account-info')
          const typeName = $(this).find('h6').text()

          if (accountInfo) {
            if (typeName === LOAN) {
              //todo:
              addURLParams('content', 'cooperative')
              addURLParams('ctype', typeName)

              $('.memberPanelMetricsContent').addClass('hidden')
              $('.memberTransactionsHistoryContent').addClass('hidden')
              $('.memberCooperativeContent').removeClass('hidden')

              $('.memberAmortizationsContent').removeClass('hidden')
              $('.amortizationApprovedList').removeClass('hidden')
              $('.amortizationRequestList').addClass('hidden')
              $('.amortizationPaymentList').addClass('hidden')
              $registeredMembersModalTitle.text(
                'List of approved amortizations:'
              )
            } else {
              toastr.warning(
                `TODO: ${typeName} account opened on ${moment(
                  accountInfo.opened_date
                ).format('MMMM D, YYYY')}`
              )
            }
          }
        })
    }
  }

  function displayUpcomingPayments(upcomingPayments) {
    const $upcomingPaymentsList = $('#upcomingPaymentsList')
    const $noUpcomingPaymentsText = $('#noUpcomingPaymentsText')
    if (upcomingPayments.length > 0) {
      $noUpcomingPaymentsText.addClass('hidden')
      $upcomingPaymentsList.empty().removeClass('hidden')

      upcomingPayments.forEach((payment) => {
        const dueDate = moment(payment.next_due_date_estimate).format(
          'DD MMM YYYY'
        )
        const listItem = `
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <small class="d-block text-muted">${payment.loan_type}</small>
                <strong>₱${payment.monthly_amount}</strong>
              </div>
              <span class="badge bg-light text-dark">${dueDate}</span>
            </li>
          `
        $upcomingPaymentsList.append(listItem)
      })
    } else {
      $noUpcomingPaymentsText.removeClass('hidden')
      $upcomingPaymentsList.addClass('hidden').empty()
    }
  }

  function displayRecentTransactions(transactions) {
    const $recentTransactionsList = $('#recentTransactionsList')
    $recentTransactionsList.empty()

    // Define transaction type configurations
    const transactionConfig = {
      deposit: { prefix: '+', icon: 'fa-arrow-up', color: '#28a745' },
      interest: { prefix: '+', icon: 'fa-percentage', color: '#28a745' },
      credit: { prefix: '+', icon: 'fa-credit-card', color: '#28a745' },
      withdrawal: { prefix: '-', icon: 'fa-arrow-down', color: '#dc3545' },
      fee: { prefix: '-', icon: 'fa-money-bill-wave', color: '#dc3545' },
      credit_used: {
        prefix: '-',
        icon: 'fa-hand-holding-usd',
        color: '#dc3545'
      },
      loan_payment: { prefix: '-', icon: 'fa-piggy-bank', color: '#dc3545' }
    }

    if (transactions && transactions.length > 0) {
      // Sort transactions by date (newest first)
      const sortedTransactions = [...transactions].sort((a, b) => {
        return (
          new Date(b.transaction_created_at) -
          new Date(a.transaction_created_at)
        )
      })

      // Get only the 3 most recent transactions
      const recentTransactions = sortedTransactions.slice(0, 3)

      // Create a list to display the transactions
      const $transactionList = $('<ul class="transaction-items"></ul>')

      recentTransactions.forEach((transaction) => {
        // Get transaction configuration or fallback
        const config = transactionConfig[transaction.transaction_type] || {
          prefix: '-',
          icon: 'fa-question',
          color: '#6c757d' // Gray for unknown types
        }

        // Format the date
        const transactionDate = new Date(transaction.transaction_created_at)
        const formattedDate = transactionDate.toLocaleDateString('en-US', {
          year: 'numeric',
          month: 'short',
          day: 'numeric'
        })

        // Format the amount with proper sign
        const formattedAmount = `${config.prefix}₱${parseFloat(
          transaction.amount
        ).toLocaleString('en-US', {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2
        })}`

        // Create transaction item
        const listItem = $(`
                <li class="transaction-item">
                    <div class="transaction-info">
                        <div class="transaction-type ${
                          transaction.transaction_type
                        }">
                            <i class="fas ${config.icon}"></i>
                        </div>
                        <div class="transaction-details">
                            <div class="transaction-title">${
                              transaction.transaction_type
                                .charAt(0)
                                .toUpperCase() +
                              transaction.transaction_type
                                .slice(1)
                                .replace('_', ' ')
                            }</div>
                            <div class="transaction-reference">Ref: ${
                              transaction.reference_number
                            }</div>
                            <div class="transaction-date">${formattedDate}</div>
                        </div>
                        <div class="transaction-amount ${
                          transaction.transaction_type
                        }">${formattedAmount}</div>
                    </div>
                </li>
            `)

        $transactionList.append(listItem)
      })

      $recentTransactionsList.append($transactionList)

      // Add 'View All Transactions' button
      const viewAllButton = $(`
            <div class="text-center mt-3 mb-2">
                <button id="viewAllTransactionsBtn" class="btn action-btn">
                    <i class="fas fa-list me-1"></i> View All Transactions History
                </button>
            </div>
        `)

      $recentTransactionsList.append(viewAllButton)

      // Add event listener to the View All button
      $('#viewAllTransactionsBtn').on('click', async function () {
        addURLParams('content', 'transactions')
        $('.memberPanelMetricsContent').addClass('hidden')
        $('.memberTransactionsHistoryContent').removeClass('hidden')
        $registeredMembersModalTitle.text(`List of all transactions history:`)
      })
    } else {
      $recentTransactionsList.html(
        '<div class="text-center text-muted py-3">No recent transactions found</div>'
      )
    }
  }

  $('.backToMemberPanelContent').click(function () {
    const from = $(this).data('from')

    if (from === 'transactions') {
      $('.memberPanelMetricsContent').removeClass('hidden')
      $('.memberTransactionsHistoryContent').addClass('hidden')
      $('.memberCooperativeContent').addClass('hidden')
    } else if (from === 'cooperative') {
      $('.memberPanelMetricsContent').removeClass('hidden')
      $('.memberTransactionsHistoryContent').addClass('hidden')
      $('.memberCooperativeContent').addClass('hidden')
    }

    $registeredMembersModalTitle.text('View Member Profile:')
    removeURLParams('content')
    removeURLParams('ctype')
  })

  const $memberTransactionHistoryTable = $('#memberTransactionsTable')
  $memberTransactionHistoryTable.on('click', '.notes-btn', function () {
    const transactionId = $(this).data('id')
    const notes = $(this).data('notes')
    //toastr.info(`Transaction ID: ${transactionId}`)
    swalBase.fire({
      title: 'Notes:',
      html: `<small>${notes}</small>`
      //icon: 'info'
    })
  })

  const $memberAmortizationPaymentsTable = $('#memberPaymentsTable')
  $memberAmortizationPaymentsTable.on('click', '.notes-btn', function () {
    const amortizationId = $(this).data('id')
    const notes = $(this).data('notes')
    swalBase.fire({
      title: 'Notes:',
      html: `<small>${notes}</small>`
    })
  })

  const $memberApprovedAmortizationsTable = $('#memberAmortilizationTable')
  $memberApprovedAmortizationsTable.on(
    'click',
    '.payment-history-btn',
    async function () {
      const amortizationId = $(this).data('id')
      const amortizationType = $(this).data('at-name')

      addURLParams('list', 'payments')
      addURLParams('atype', amortizationType)
      addURLParams('aid', amortizationId)

      $('.memberAmortizationsContent').removeClass('hidden')
      $('.amortizationApprovedList').addClass('hidden')
      $('.amortizationRequestList').addClass('hidden')
      $('.amortizationPaymentList').removeClass('hidden')
      $registeredMembersModalTitle.text(
        `List of amortization payments on ${amortizationType}:`
      )
      try {
        LoadingManager.show($('.main-content'))
        const payments = await fetchMemberAmortizationPayments(amortizationId)
        if (payments.success) {
          DataTableMemberAmortizationPayments(
            $memberAmortizationPaymentsTable,
            payments.data
          )
        }
        LoadingManager.hide($('.main-content'))
      } catch (error) {
        console.error('Error fetching member amortization payments:', error)
      }
    }
  )

  $('.backToMemberAmortizationsContent').click(function () {
    $('.memberAmortizationsContent').removeClass('hidden')
    $('.amortizationApprovedList').removeClass('hidden')
    $('.amortizationRequestList').addClass('hidden')
    $('.amortizationPaymentList').addClass('hidden')

    removeURLParams('list')
    removeURLParams('atype')
    removeURLParams('aid')
  })

  $('.viewMemberAmortizationList').click(function () {
    addURLParams('list', 'request')

    $('.memberAmortizationsContent').removeClass('hidden')
    $('.amortizationApprovedList').addClass('hidden')
    $('.amortizationRequestList').removeClass('hidden')
    $('.amortizationPaymentList').addClass('hidden')
    $registeredMembersModalTitle.text('List of amortization requests:')
  })

  $registeredMembersModalTitle.text('List of approved amortizations:')
  async function handlePageContent() {
    const tab = getURLParams('tab')
    const mode = getURLParams('mode')
    const content = getURLParams('content')
    const hashFragment = getHashFragment()

    if (tab === 'registered' && ['create', 'update'].includes(mode)) {
      $('.registeredMembersContent').addClass('hidden')
      $('.createNewMemberContent').removeClass('hidden')

      if (mode === 'create') {
        $registeredMembersModalTitle.text('Create / Register New Member:')
        $('.cmActionBtn').removeClass('hidden')
        $('.umActionBtn').addClass('hidden')
        handleCMCooperativeAccountSelection()
      } else if (mode === 'update') {
        $registeredMembersModalTitle.text('Update Member Information:')
        $('.cmActionBtn').addClass('hidden')
        $('.umActionBtn').removeClass('hidden')
        const memberId = getURLParams('member_id')
        LoadingManager.show($('.main-content'))
        const response = await fetchMember(memberId)
        if (response.success) {
          populateMembersInformation(response.data)
        }
        LoadingManager.hide($('.main-content'))
      }
    } else if (tab === 'registered' && mode === 'view') {
      $registeredMembersModalTitle.text('View Member Profile:')
      $('.registeredMembersContent').addClass('hidden')
      $('.createNewMemberContent').addClass('hidden')
      $('.viewMemberContent').removeClass('hidden')
      const memberId = getURLParams('member_id')
      LoadingManager.show($('.main-content'))

      const response = await Promise.all([
        fetchMember(memberId),
        fetchMemberAccountBalanceMetrics(memberId),
        fetchMemberSavingsGoalMetrics(memberId),
        fetchMemberActiveLoansMetrics(memberId),
        fetchMemberUpcomingPayments(memberId),
        fetchMemberTransactionsHistory(memberId),
        fetchMemberApprovedAmortizations(memberId),
        fetchMemberRequestAmortizations(memberId)
      ])
      if (response[0].success && response[1].success) {
        populateMemberPanelMetrics(
          response[0].data, //memberInfo
          response[1].data, //accountBalance
          response[2].data, //savingsGoal
          response[3].data, //activeLoans
          response[4].data, //upcomingPayments
          response[5].data //recentTransactions
        )

        DataTableMemberTransactionsHistory(
          $memberTransactionHistoryTable,
          response[5].data
        )

        DataTableMemberApprovedAmortizations(
          $memberApprovedAmortizationsTable,
          response[6].data
        )

        DataTableMemberRequestAmortizations(
          $('#memberAmortizationsRequestTable'),
          response[7].data
        )
      }
      LoadingManager.hide($('.main-content'))
      if (content === 'transactions') {
        $('.memberPanelMetricsContent').addClass('hidden')
        $('.memberTransactionsHistoryContent').removeClass('hidden')
        $('.memberCooperativeContent').addClass('hidden')
        $registeredMembersModalTitle.text(`List of all transactions history:`)
      } else if (content === 'cooperative') {
        $('.memberPanelMetricsContent').addClass('hidden')
        $('.memberTransactionsHistoryContent').addClass('hidden')
        $('.memberCooperativeContent').removeClass('hidden')

        if (getURLParams('ctype') === LOAN) {
          if (getURLParams('list') === 'payments') {
            LoadingManager.show($('.main-content'))
            const payments = await fetchMemberAmortizationPayments(
              getURLParams('aid')
            )
            if (payments.success) {
              DataTableMemberAmortizationPayments(
                $memberAmortizationPaymentsTable,
                payments.data
              )
            }
            LoadingManager.hide($('.main-content'))

            $('.memberAmortizationsContent').removeClass('hidden')
            $('.amortizationApprovedList').addClass('hidden')
            $('.amortizationRequestList').addClass('hidden')
            $('.amortizationPaymentList').removeClass('hidden')
            $registeredMembersModalTitle.text(
              `List of amortization payments on ${getURLParams('atype')}:`
            )
          } else if (getURLParams('list') === 'request') {
            $('.memberAmortizationsContent').removeClass('hidden')
            $('.amortizationApprovedList').addClass('hidden')
            $('.amortizationRequestList').removeClass('hidden')
            $('.amortizationPaymentList').addClass('hidden')
            $registeredMembersModalTitle.text('List of amortization requests:')
          } else {
            $('.memberAmortizationsContent').removeClass('hidden')
            $('.amortizationApprovedList').removeClass('hidden')
            $('.amortizationRequestList').addClass('hidden')
            $('.amortizationPaymentList').addClass('hidden')
            $registeredMembersModalTitle.text('List of approved amortizations:')
          }
        }
      } else {
        $('.memberPanelMetricsContent').removeClass('hidden')
        $('.memberTransactionsHistoryContent').addClass('hidden')
        $('.memberCooperativeContent').addClass('hidden')
        $registeredMembersModalTitle.text('View Member Profile:')
      }
    } else {
      $registeredMembersModalTitle.text(
        $registeredMembersModalTitle.data('orginal-title')
      )
      $('.registeredMembersContent').removeClass('hidden')
      $('.createNewMemberContent').addClass('hidden')
      $('.viewMemberContent').addClass('hidden')
    }
  }

  await handlePageContent()
})
