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
      error.response?.data?.message || 'Something went wrong.'
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
      error.response?.data?.message || 'Something went wrong.'
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
      error.response?.data?.message || 'Something went wrong.'
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
      error.response?.data?.message || 'Something went wrong.'
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
      error.response?.data?.message || 'Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const updateEmployeeCooperative = async (payload) => {
  try {
    //console.log(postPayload)
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
      error.response?.data?.message || 'Something went wrong.'
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
      error.response?.data?.message || 'Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const loginAccount = async (uid, password, recaptchaResponse) => {
  try {
    const payload = {
      action: 'login_account',
      data: {
        uid: uid,
        password: password,
        recaptcha_response: recaptchaResponse
      }
    }
    const response = await axios.post(`${API_URL}`, payload, {
      headers: HEADERS
    })
    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to login account')
    }

    return response.data
  } catch (error) {
    console.error('Error attempt to login account:', error)
    const errorMessage =
      error.response?.data?.message || 'Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const requestAccountVerification = async (email, recaptchaResponse) => {
  try {
    const payload = {
      action: 'request_account_verification',
      data: {
        email: email,
        recaptcha_response: recaptchaResponse
      }
    }
    const response = await axios.post(`${API_URL}`, payload, {
      headers: HEADERS
    })
    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to request account verification'
      )
    }

    return response.data
  } catch (error) {
    console.error('Error attempt to request account verification:', error)
    const errorMessage =
      error.response?.data?.message || 'Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const verifyAccount = async (email, verificationCode) => {
  try {
    const payload = {
      action: 'verify_account',
      data: {
        email: email,
        code: verificationCode
      }
    }
    const response = await axios.post(`${API_URL}`, payload, {
      headers: HEADERS
    })

    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to verify account')
    }

    return response.data
  } catch (error) {
    console.error('Error verify account:', error)
    const errorMessage =
      error.response?.data?.message || 'Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const requestOTP = async (email) => {
  try {
    const payload = {
      action: 'request_otp',
      data: {
        email: email
      }
    }

    const response = await axios.post(`${API_URL}`, payload, {
      headers: HEADERS
    })
    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to request otp')
    }

    return response.data
  } catch (error) {
    console.error('Error request otp:', error)
    const errorMessage =
      error.response?.data?.message || 'Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const verifyOTP = async (email, otp) => {
  try {
    const payload = {
      action: 'verify_otp',
      data: {
        email: email,
        code: otp
      }
    }
    const response = await axios.post(`${API_URL}`, payload, {
      headers: HEADERS
    })

    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to verify otp')
    }

    return response.data
  } catch (error) {
    console.error('Error verify otp:', error)
    const errorMessage =
      error.response?.data?.message || 'Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const logoutAccount = async () => {
  try {
    const payload = {
      action: 'logout_account',
      data: {
        uid: $('meta[name="account-uid"]').attr('content'),
        session_id: $('meta[name="session-id"]').attr('content')
      }
    }
    const response = await axios.post(`${API_URL}`, payload, {
      headers: HEADERS
    })

    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to logout account')
    }

    return response.data
  } catch (error) {
    console.error('Error logout account:', error)
    const errorMessage =
      error.response?.data?.message || 'Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}
