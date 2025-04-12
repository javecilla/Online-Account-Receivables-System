$(document).ready(async function () {
  const otpInputs = $('.otp-input')

  //Handle paste functionality
  otpInputs.on('paste', function (e) {
    e.preventDefault()
    const pasteData = (
      e.originalEvent.clipboardData || window.clipboardData
    ).getData('text')
    const otpDigits = pasteData.replace(/\D/g, '').split('').slice(0, 6)

    otpInputs.each(function (index) {
      if (otpDigits[index]) {
        $(this).val(otpDigits[index])
        if (index < otpInputs.length - 1) {
          $(this).closest('.otp-input-group').next().find('.otp-input').focus()
        }
      }
    })
  })

  //auto-focus and navigation between OTP fields with numeric validation
  otpInputs.on('input', function (e) {
    const currentInput = $(this)
    const inputValue = currentInput.val()

    const numericValue = inputValue.replace(/[^0-9]/g, '')
    currentInput.val(numericValue)

    if (numericValue.length > 0) {
      const maxLength = currentInput.attr('maxlength')
      if (numericValue.length >= maxLength) {
        const nextInput = currentInput
          .closest('.otp-input-group')
          .next()
          .find('.otp-input')
        if (nextInput.length) {
          nextInput.focus()
        }
      }
    }
  })

  //handle backspace
  otpInputs.on('keydown', function (e) {
    const currentInput = $(this)
    if (e.key === 'Backspace' && !currentInput.val()) {
      e.preventDefault()
      const prevInput = currentInput
        .closest('.otp-input-group')
        .prev()
        .find('.otp-input')
      if (prevInput.length) {
        prevInput.focus().val('')
      }
    }
  })

  $('#resendOTPBtn').click(async function (e) {
    e.preventDefault()
    const email = getURLParams('email')
    const now = Date.now()
    const cooldownPeriod = 60000 // 1 minute in milliseconds
    const endTime = now + cooldownPeriod
    const $btn = $(this)

    try {
      $btn
        .addClass('disabled')
        .attr('disabled', 'disabled')
        .text('processing...')

      const response = await requestOTP(email)
      console.log('response', response)

      localStorage.setItem(
        'otpCooldown',
        JSON.stringify({
          endTime,
          storedEmail: email
        })
      )

      toastr.success('OTP has been sent to your email.')
      startResendCooldown($btn, 60)
    } catch (error) {
      $btn.removeClass('disabled').removeAttr('disabled').text('Resend')
      console.error('Error resending OTP:', error)
    }
  })

  $('#verifyOTPBtn').click(async function (e) {
    e.preventDefault()
    const email = getURLParams('email')
    const otp = $('.otp-input')
      .map(function () {
        return $(this).val()
      })
      .get()
      .join('')
    const otpCode = otp.replace(/\s/g, '')
    if (isEmpty(otpCode)) {
      toastr.warning('OTP code is required')
      return
    }
    try {
      $(this).text('Processing...').prop('disabled', true)
      const response = await verifyOTP(email, otpCode)
      //console.log('response', response)
      window.location.href = $('meta[name="base-url"]').attr('content') //response.redirect
      toastr.success('Account has been verified.')
      //setHashFragment('login')
    } catch (error) {
      console.error('Error verifying account:', error)
    } finally {
      $('.otp-input').focus()
      $('#verifyOTPBtn').text('Verify').prop('disabled', false)
    }
  })

  $('#backtoLoginBtn').click(async function (e) {
    e.preventDefault()

    const swalBtn = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-light action-btn',
        cancelButton: 'btn btn-light action-btn',
        showLoaderOnConfirm: 'swal2-loader'
      },
      buttonsStyling: false
    })

    swalBtn
      .fire({
        title: 'Please confirm',
        html: '<small>Are you sure do you want to discard and go to login?</small>',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: `Cancel`
      })
      .then(async function (result) {
        if (result.isConfirmed) {
          removeURLParams('checkpoint')
          removeURLParams('key')
          removeURLParams('email')
          removeHashFragment()
          await handlePageContent()
        }
      })
  })

  $('#togglePassword').click(function () {
    const passwordField = $('#password')
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

  $('.login-btn').click(async function (e) {
    e.preventDefault()
    //toastr.info('test')
    const username = $('#username')
    const password = $('#password')
    const grecaptchaValue = $('#g-recaptcha-response').val()
    let grecaptchaResponse = grecaptcha.getResponse()

    if (isEmpty(username.val())) {
      toastr.warning('Username is required')
      username.focus()
      return
    }
    if (isEmpty(password.val())) {
      toastr.warning('Password is required')
      password.focus()
      return
    }
    if (isEmpty(grecaptchaValue)) {
      toastr.warning('Please complete the reCAPTCHA!')
      return
    }

    try {
      $(this).text('Processing...').prop('disabled', true)

      const response = await loginAccount(
        username.val().trim(),
        password.val().trim(),
        grecaptchaResponse
      )
      window.location.href = $('meta[name="base-url"]').attr('content') //response.redirect
    } catch (error) {
      if (error.response.data.first_time_login) {
        addURLParams('checkpoint', 'true')
        addURLParams('key', error.response.data.key)
        addURLParams('email', error.response.data.email)
        setHashFragment('otpRequired')

        await handlePageContent()
      }
      //console.error('Error login account:', error)
    } finally {
      password.val('').focus()
      grecaptcha.reset()
      $(this).text('Login').prop('disabled', false)
    }
  })

  async function handlePageContent() {
    const checkpoint = getURLParams('checkpoint')
    const key = getURLParams('key')
    const email = getURLParams('email')
    const activeHashFragment = getHashFragment()
    // console.log(checkpoint)
    // console.log(key)
    // console.log(email)
    // console.log(activeHashFragment)

    const checkExistingCooldown = () => {
      const cooldownData = localStorage.getItem('otpCooldown')
      const resendButton = $('#resendOTPBtn')
      const email = getURLParams('email')

      if (cooldownData) {
        const { endTime, storedEmail } = JSON.parse(cooldownData)
        const now = Date.now()
        if (storedEmail === email && endTime > now) {
          startResendCooldown(resendButton, Math.ceil((endTime - now) / 1000))
        } else if (endTime <= now || storedEmail !== email) {
          localStorage.removeItem('otpCooldown')
        }
      }
    }

    if (checkpoint && checkpoint == 'true') {
      $('.login-header h2').text('OTP Verification')
      $('.login-header p').text(
        `We notice this is your first time login. A one-time password (OTP) has been sent to your registered email address, ${email}. Please enter the OTP below to complete the verification process. Note that the OTP is valid for 5 minutes.`
      )
      $('#loginForm').hide()
      $('#otpForm').show()
      checkExistingCooldown()
    } else {
      $('.login-header h2').text('Welcome back!')
      $('.login-header p').text('Enter your credentials to access your account')
      $('#loginForm').show()
      $('#otpForm').hide()
    }
  }

  await handlePageContent()
})

// Define startResendCooldown in global scope
const startResendCooldown = (button, remainingSeconds) => {
  button
    .text(`Resend OTP (${remainingSeconds}s)`)
    .addClass('disabled')
    .attr('disabled', 'disabled')
  const timer = setInterval(() => {
    remainingSeconds--
    if (remainingSeconds < 0) {
      clearInterval(timer)
      button.removeClass('disabled').removeAttr('disabled').text('Resend')
    } else {
      button.text(`Resend OTP (${remainingSeconds}s)`)
    }
  }, 1000)
}
