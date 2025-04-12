$(document).ready(async function () {
  const purpose = getURLParams('purpose')
  const activeHashFragment = getHashFragment()
  //console.log('test')

  async function handlePageContent() {
    if (purpose === 'verify' && activeHashFragment === 'external') {
      $('.av-header h2').text('Verifying your account')
      $('.av-header p').text(
        'Please wait a seconds while processing your request.'
      )
      $('#avForm').hide()
      $('#avLoadVerifying').removeClass('d-none')
      const email = getURLParams('email')
      const code = getURLParams('code')
      console.log('email', email)
      console.log('code', code)
      if (isEmpty(email) || isEmpty(code)) return

      try {
        const response = await Promise.race([
          verifyAccount(email, code),
          new Promise((resolve) => setTimeout(resolve, 10000))
        ])

        if (response && response.success) {
          $('.av-loading-content').addClass('d-none')
          $('.av-success-content').removeClass('d-none')
          $('.av-error-content').addClass('d-none')
          $('.success-message').text(
            response.message || 'Your account has been successfully verified.'
          )
        } else {
          $('.av-loading-content').addClass('d-none')
          $('.av-success-content').addClass('d-none')
          $('.av-error-content').removeClass('d-none')
          $('.error-message').text(
            response.message ||
              'An error occurred during verification. Please try again.'
          )
        }
      } catch (error) {
        $('.av-loading-content').addClass('d-none')
        $('.av-success-content').addClass('d-none')
        $('.av-error-content').removeClass('d-none')
        $('.error-message').text(
          'An error occurred during verification. Please try again.'
        )
        console.error('Error verifying account:', error)
      }
    } else if (purpose === 'request' && activeHashFragment === 'internal') {
      $('.av-header h2').text('Request Account Verification')
      $('.av-header p').text(
        `Enter your email address to start the verification process. We'll send you a secure link to confirm your account`
      )
      $('#avForm').show()
      $('#avLoadVerifying').hide()
    }
  }

  $('#requestVerificationBtn').click(async function (e) {
    e.preventDefault()
    const email = $('#email')
    const grecaptchaValue = $('#g-recaptcha-response').val()
    let grecaptchaResponse = grecaptcha.getResponse()

    if (isEmpty(email.val())) {
      toastr.warning('Email is required, Please enter your email address.')
      return
    }
    const emailValidation = await isValidEmail(email.val())
    if (!emailValidation) {
      toastr.warning('Invalid email address. Please enter a valid email.')
      return
    }
    if (isEmpty(grecaptchaValue)) {
      toastr.warning('Please complete the reCAPTCHA!')
      return
    }

    try {
      $(this).text('Sending...').prop('disabled', true)

      const response = await requestAccountVerification(
        email.val().trim(),
        grecaptchaResponse
      )
      console.log('response', response ?? null)
      if (response.success) {
        toastr.success(response.message)
      }
    } catch (error) {
      console.error('Error login account:', error)
    } finally {
      email.val('').focus()
      grecaptcha.reset()
      $(this).text('Request Verification').prop('disabled', false)
    }
  })

  await handlePageContent()
})
