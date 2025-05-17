$(document).ready(async function () {
  await fetchAboutUsContent()

  $('#titleAboutUs').on('input', function () {
    $('#content-aboutus .col-md-6 h1.fw-bold').text($(this).val())
  })

  $('#descriptionAboutUs').on('input', function () {
    $('#content-aboutus .col-md-6 p.lead').text($(this).val())
  })

  $('#featuredAboutUs').on('input', function () {
    $('#content-aboutus .col-md-6 ul.list-unstyled').html($(this).val())
  })

  $('#updateAboutUsBtn').on('click', async function () {
    const button = $(this)
    const originalText = button.text()

    try {
      const title = $('#titleAboutUs').val()
      const description = $('#descriptionAboutUs').val()
      const features = $('#featuredAboutUs').val()

      if (!title || !description || !features) {
        toastr.error('Please fill in all fields')
        return
      }

      button.text('Saving...').prop('disabled', true)

      const payload = {
        action: 'update_aboutus_content',
        data: {
          aboutus_id: 1,
          title: title,
          description: description,
          features: features
        }
      }

      const response = await axios.post(`${API_URL}`, payload, {
        headers: HEADERS
      })

      if (!response.data || !response.data.success) {
        throw new Error(
          response.data?.message || 'Failed to update about us content'
        )
      }

      toastr.success('About Us content updated successfully')
    } catch (error) {
      console.error('Error:', error)
      const errorMessage =
        error.response?.data?.message ||
        'Something went wrong while updating about us content.'
      toastr.error(errorMessage)
    } finally {
      button.text(originalText).prop('disabled', false)
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
      // Update preview section
      $('#content-aboutus .col-md-6 h1.fw-bold').text(aboutUsData.title)
      $('#content-aboutus .col-md-6 p.lead').text(aboutUsData.description)
      $('#content-aboutus .col-md-6 ul.list-unstyled').html(
        aboutUsData.features
      )

      // Update form fields
      $('#titleAboutUs').val(aboutUsData.title)
      $('#descriptionAboutUs').val(aboutUsData.description)
      $('#featuredAboutUs').val(aboutUsData.features)
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
