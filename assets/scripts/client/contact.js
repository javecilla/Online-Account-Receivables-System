$(document).ready(async function () {
  await fetchAboutUsContent()

  if (document.getElementById('contact-map')) {
    await initializeMap()
  }

  // Add smooth scrolling for navigation links
  $('.nav-scroll').on('click', function (e) {
    e.preventDefault()

    const target = $(this).attr('href')
    const offset = $(target).offset().top

    $('html, body').animate(
      {
        scrollTop: offset
      },
      800,
      'swing',
      function () {
        // Update active state in navbar
        $('.nav-scroll').removeClass('active')
        $(`a[href="${target}"]`).addClass('active')
      }
    )

    // Close mobile menu if open
    if ($('.offcanvas.show').length) {
      $('.offcanvas').offcanvas('hide')
    }
  })

  $('#contactForm').on('submit', async function (e) {
    e.preventDefault()
    const name = $('#name').val()
    const email = $('#email').val()
    const message = $('#message').val()
    const grecaptchaValue = $('#g-recaptcha-response').val()
    let grecaptchaResponse = grecaptcha.getResponse()
    const submitButton = $('#submit')

    if (isEmpty(name) || isEmpty(email) || isEmpty(message)) {
      toastr.warning('All fields are required')
      return
    }

    if (isEmpty(grecaptchaValue)) {
      toastr.warning('Please complete the reCAPTCHA!')
      return
    }

    submitButton.text('Processing...').prop('disabled', true)
    try {
      const payload = {
        action: 'create_contact_messages',
        data: {
          name: name,
          email: email,
          message: message,
          recaptcha_response: grecaptchaResponse
        }
      }
      const response = await axios.post(`${API_URL}`, payload, {
        headers: HEADERS
      })
      if (!response.data || !response.data.success) {
        throw new Error(response.data?.message || 'Failed to send message.')
      }
      const send = response.data

      if (send.success) {
        toastr.success(send.message)
        $('#contactForm')[0].reset()
      }
    } catch (error) {
      console.error('Error:', error)
      const errorMessage =
        error.response?.data?.message ||
        'Something went wrong while processing request.'
      toastr.error(errorMessage)
    } finally {
      grecaptcha.reset()
      grecaptchaResponse = ''
      submitButton.text('Send Message').prop('disabled', false)
    }
  })
})

async function fetchAboutUsContent() {
  try {
    const response = await axios.get(`${API_URL}?action=get_aboutus_content`, {
      headers: HEADERS
    })

    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to fetch about us content'
      )
    }

    const aboutUsData = response.data.aboutus[0]
    if (aboutUsData) {
      $('.about-us h1.fw-bold').text(aboutUsData.title)
      $('.about-us p.lead').text(aboutUsData.description)
      $('.about-us .col-lg-8 ul.list-unstyled').html(aboutUsData.features)
    }

    return response.data
  } catch (error) {
    console.error('Error:', error)
    const errorMessage =
      error.response?.data?.message ||
      'Something went wrong while fetching about us content.'
    toastr.error(errorMessage)
    return { success: false, message: errorMessage }
  }
}

async function initializeMap() {
  let lat
  let lng
  let popup

  try {
    const response = await axios.get(`${API_URL}?action=get_contact_map`, {
      headers: HEADERS
    })

    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to fetch contacts')
    }

    const contact = response.data.contacts[0]
    //console.log(contact)
    lat = contact.latitude
    lng = contact.longitude
    popup = contact.popup

    const map = L.map('contact-map').setView([lat, lng], 15)

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution:
        '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      maxZoom: 19
    }).addTo(map)

    const customIcon = L.icon({
      iconUrl:
        'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
      shadowUrl:
        'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      shadowSize: [41, 41]
    })

    const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map)
    marker.bindPopup(`${popup}`).openPopup()

    setTimeout(function () {
      map.invalidateSize()
    }, 400)
  } catch (error) {
    console.error('Error:', error)
    const errorMessage =
      error.response?.data?.message ||
      'Something went wrong while processing request.'
    toastr.error(errorMessage)
  }
}
