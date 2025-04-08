const fetchAccounts = async () => {
  try {
    const response = await axios.get(`${API_URL}?action=get_accounts`, {
      headers: HEADERS
    })
    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to fetch accounts')
    }
    return response.data
  } catch (error) {
    console.error('Error fetching accounts:', error)
    const errorMessage =
      error.response?.data?.message || 'Something went wrong on our end'
    toastr.error(errorMessage)
    throw error
  }
}

const fetchAccountRoles = async () => {
  try {
    const response = await axios.get(`${API_URL}?action=get_account_roles`, {
      headers: HEADERS
    })
    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to fetch account roles')
    }
    return response.data
  } catch (error) {
    console.error('Error fetching accounts:', error)
    const errorMessage =
      error.response?.data?.message || 'Something went wrong on our end'
    toastr.error(errorMessage)
    throw error
  }
}

const createMemberCooperative = async (payload) => {
  try {
    console.log('createMemberCooperative() called')
    console.log('API_URL', API_URL)
    console.log('body', payload)
    const response = await axios.post(`${API_URL}`, payload, {
      headers: HEADERS
    })
    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to create member account'
      )
    }
    return response.data
  } catch (error) {
    console.error('Error create member account:', error)
    const errorMessage =
      error.response?.data?.message || 'Something went wrong on our end'
    toastr.error(errorMessage)
    throw error
  }
}

const createEmployeeCooperative = async (payload) => {
  try {
    const response = await axios.post(`${API_URL}`, payload, {
      headers: HEADERS
    })
    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to create employee account'
      )
    }
    return response.data
  } catch (error) {
    console.error('Error create employee account:', error)
    const errorMessage =
      error.response?.data?.message || 'Something went wrong on our end'
    toastr.error(errorMessage)
    throw error
  }
}

const updateMemberCooperative = async (payload) => {
  try {
    console.log('called')
    console.log('API_URL', API_URL)
    console.log('body', payload)
    const response = await axios.post(`${API_URL}`, payload, {
      headers: HEADERS
    })
    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to update member account'
      )
    }
    console.log('data', response.data)
    return response.data
  } catch (error) {
    console.error('Error update member account:', error)
    const errorMessage =
      error.response?.data?.message || 'Something went wrong on our end'
    toastr.error(errorMessage)
    throw error
  }
}

const updateEmployeeCooperative = async (payload) => {
  try {
    console.log(postPayload)
    const response = await axios.post(`${API_URL}`, payload, {
      headers: HEADERS
    })

    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to update employee account'
      )
    }
    return response.data
  } catch (error) {
    console.error('Error updating employee account:', error)
    const errorMessage =
      error.response?.data?.message || 'Something went wrong on our end'
    toastr.error(errorMessage)
    throw error
  }
}

const deleteAccount = async (accountId) => {
  try {
    // Create payload for both DELETE and POST methods
    const payload = {
      action: 'delete_account',
      data: {
        account_id: accountId
      }
    }
    console.log(payload)
    const response = await axios.post(`${API_URL}`, payload, {
      headers: HEADERS
    })

    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to delete account')
    }
    return response.data
  } catch (error) {
    console.error('Error deleting account:', error)
    const errorMessage =
      error.response?.data?.message || 'Something went wrong on our end'
    toastr.error(errorMessage)
    throw error
  }
}
