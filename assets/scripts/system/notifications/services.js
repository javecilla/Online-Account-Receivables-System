const createNotification = async (data) => {
  try {
    const payload = {
      action: 'create_notification',
      data: data
    }

    const response = await axios.post(`${API_URL}`, payload, {
      headers: HEADERS
    })

    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to create notification')
    }
    return response.data
  } catch (error) {
    console.error('Error creating notification:', error)
    const errorMessage =
      error.response?.data?.message ||
      'Something went wrong while processing request.'
    toastr.error(errorMessage)
    throw error
  }
}
