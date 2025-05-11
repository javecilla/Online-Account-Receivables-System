$(document).ready(async function () {
  let map
  let marker
  let currentLat = 14.8527
  let currentLng = 120.816
  let currentPopup = 'Bulacan State University'

  // Initialize map
  async function initializeMap() {
    try {
      const response = await axios.get(`${API_URL}?action=get_contact_map`, {
        headers: HEADERS
      })

      if (!response.data || !response.data.success) {
        throw new Error(response.data?.message || 'Failed to fetch contacts')
      }

      const contact = response.data.contacts[0]
      if (contact) {
        currentLat = parseFloat(contact.latitude)
        currentLng = parseFloat(contact.longitude)
        currentPopup = contact.popup
      }

      // Update form fields
      $('#latitude').val(currentLat)
      $('#longtitude').val(currentLng)
      $('#popup').val(currentPopup)

      map = L.map('contact-map').setView([currentLat, currentLng], 15)

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

      marker = L.marker([currentLat, currentLng], {
        icon: customIcon,
        draggable: true
      }).addTo(map)

      marker.bindPopup(currentPopup).openPopup()

      // Event listener for marker drag
      marker.on('dragend', function (event) {
        const position = marker.getLatLng()
        currentLat = position.lat
        currentLng = position.lng

        // Update form fields
        $('#latitude').val(currentLat)
        $('#longtitude').val(currentLng)

        // Update map view
        map.setView([currentLat, currentLng])
      })

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

  // Initialize map when tab is shown
  $('#content-contactus-tab').on('shown.bs.tab', function () {
    if (!map) {
      initializeMap()
    }
  })

  // Update popup content when textarea changes
  $('#popup').on('input', function () {
    currentPopup = $(this).val()
    if (marker) {
      marker.setPopupContent(currentPopup)
      marker.openPopup()
    }
  })

  // Save changes button click handler
  $('#updateContactMapBtn').on('click', async function () {
    const button = $(this)
    const originalText = button.text()

    try {
      const latitude = $('#latitude').val()
      const longitude = $('#longtitude').val()
      const popup = $('#popup').val()

      if (!latitude || !longitude || !popup) {
        toastr.error('Please fill in all fields')
        return
      }

      button.text('Saving...').prop('disabled', true)

      const payload = {
        action: 'update_contact_map',
        data: {
          contact_id: 1,
          latitude: parseFloat(latitude),
          longitude: parseFloat(longitude),
          popup: popup
        }
      }

      const response = await axios.post(`${API_URL}`, payload, {
        headers: HEADERS
      })

      if (!response.data || !response.data.success) {
        throw new Error(
          response.data?.message || 'Failed to update contact map'
        )
      }

      // Update map with new coordinates
      if (map && marker) {
        map.setView([latitude, longitude])
        marker.setLatLng([latitude, longitude])
        marker.setPopupContent(popup)
      }

      toastr.success('Contact map updated successfully')
    } catch (error) {
      console.error('Error:', error)
      const errorMessage =
        error.response?.data?.message ||
        'Something went wrong while updating contact map.'
      toastr.error(errorMessage)
    } finally {
      button.text(originalText).prop('disabled', false)
    }
  })

  await initializeMap()
})
