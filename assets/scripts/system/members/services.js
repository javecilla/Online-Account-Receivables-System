const fetchMembershipTypes = async () => {
  try {
    const response = await axios.get(`${API_URL}?action=get_membership_types`, {
      headers: HEADERS
    })
    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to fetch membership types'
      )
    }
    return response.data
  } catch (error) {
    console.error('Error fetching membership types:', error)
    const errorMessage =
      error.response?.data?.message || 'Opss! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const fetchMemberByAccount = async (accountId) => {
  // return { success: true, message: 'test', data: { test: 1 } }
  try {
    const response = await axios.get(
      `${API_URL}?action=get_member_by_account&account_id=${accountId}`,
      {
        headers: HEADERS
      }
    )
    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to fetch member by its account'
      )
    }
    return response.data
  } catch (error) {
    console.error('Error fetching member by its account:', error)
    const errorMessage =
      error.response?.data?.message || 'Opss! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const fetchMember = async (memberId) => {
  // return { success: true, message: 'test', data: { test: 1 } }
  try {
    const response = await axios.get(
      `${API_URL}?action=get_member&member_id=${memberId}`,
      {
        headers: HEADERS
      }
    )
    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to fetch member')
    }
    return response.data
  } catch (error) {
    console.error('Error fetching member:', error)
    const errorMessage =
      error.response?.data?.message || 'Opss! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const fetchMembers = async () => {
  try {
    const response = await axios.get(`${API_URL}?action=get_members`, {
      headers: HEADERS
    })
    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to fetch members')
    }
    return response.data
  } catch (error) {
    console.error('Error fetching members:', error)
    const errorMessage =
      error.response?.data?.message || 'Opss! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const fetchMemberAmortizations = async (memberId) => {
  try {
    const response = await axios.get(
      `${API_URL}?action=get_member_amortizations&member_id=${memberId}`,
      {
        headers: HEADERS
      }
    )

    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to fetch member amotizations'
      )
    }
    return response.data
  } catch (error) {
    console.error('Error fetching member amotizations:', error)
    const errorMessage =
      error.response?.data?.message || 'Opss! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const fetchMemberTransactionsHistory = async (memberId) => {
  try {
    const response = await axios.get(
      `${API_URL}?action=get_member_transactions&member_id=${memberId}`,
      {
        headers: HEADERS
      }
    )
    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to fetch member transactions history'
      )
    }
    return response.data
  } catch (error) {
    console.error('Error fetching member transactions history:', error)
    const errorMessage =
      error.response?.data?.message || 'Opss! Something went wrong.'
  }
}

const fetchMembersTransactionsLogs = async () => {
  try {
    const response = await axios.get(
      `${API_URL}?action=get_members_transactions`,
      {
        headers: HEADERS
      }
    )
    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to fetch members transactions history'
      )
    }
    return response.data
  } catch (error) {
    console.error('Error fetching members transactions history:', error)
    const errorMessage =
      error.response?.data?.message || 'Opss! Something went wrong.'
  }
}

const deleteMember = async (memberId) => {
  try {
    const payload = {
      action: 'delete_member',
      data: {
        member_id: memberId
      }
    }
    console.log(payload)
    const response = await axios.post(`${API_URL}`, payload, {
      headers: HEADERS
    })

    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to delete member')
    }
    return response.data
  } catch (error) {
    console.error('Error deleting member:', error)
    const errorMessage =
      error.response?.data?.message || 'Oppss! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const fetchMemberAmortizationPayments = async (amortizationId) => {
  try {
    const response = await axios.get(
      `${API_URL}?action=get_member_amortization_payments&amortization_id=${amortizationId}`,
      {
        headers: HEADERS
      }
    )
    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to fetch members transactions history'
      )
    }
    return response.data
  } catch (error) {
    console.error('Error fetching members transactions history:', error)
    const errorMessage =
      error.response?.data?.message || 'Opss! Something went wrong.'
  }
}

const createMemberAmortization = async (data) => {
  try {
    const payload = {
      action: 'create_amortization',
      data: data
    }
    const response = await axios.post(`${API_URL}`, payload, {
      headers: HEADERS
    })
    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to create member amortization'
      )
    }
    return response.data
  } catch (error) {
    console.error('Error creating member amortization:', error)
    const errorMessage =
      error.response?.data?.message || 'Opss! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const updateMemberAmortization = async (amortizationId, newData) => {
  try {
    const payload = {
      action: 'update_amortization',
      data: {
        amortization_id: amortizationId,
        ...newData
      }
    }
    const response = await axios.post(`${API_URL}`, payload, {
      headers: HEADERS
    })
    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to create member amortization'
      )
    }
    return response.data
  } catch (error) {
    console.error('Error updating member amortization:', error)
    const errorMessage =
      error.response?.data?.message || 'Opss! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}
