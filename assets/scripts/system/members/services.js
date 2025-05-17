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
      //`${API_URL}?action=get_member&member_id=${memberId}`,
      `${API_URL}?action=get_registered_member&member_id=${memberId}`,
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

const fetchMembersByCriteria = async (
  types = 'Savings Account,Time Deposit,Fixed Deposit,Special Savings,Youth Savings,Loan',
  status = 'active,inactive,suspended,closed'
) => {
  try {
    const response = await axios.get(
      `${API_URL}?action=get_members_by_criteria&membership_types=${types}&membership_status=${status}`,
      {
        headers: HEADERS
      }
    )
    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to fetch members by criteria'
      )
    }
    return response.data
  } catch (error) {
    console.error('Error fetching members by criteria:', error)
    const errorMessage =
      error.response?.data?.message || 'Opss! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const updateMembershipStatus = async (data) => {
  try {
    const payload = {
      action: 'update_member_status',
      data: data
    }
    const response = await axios.post(`${API_URL}`, payload, {
      headers: HEADERS
    })
    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to updated member status'
      )
    }
    return response.data
  } catch (error) {
    console.error('Error updating member status:', error)
    const errorMessage =
      error.response?.data?.message || 'Opss! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const fetchMemberAccountBalanceMetrics = async (memberId) => {
  try {
    const response = await axios.get(
      `${API_URL}?action=get_member_account_balance_metrics&member_id=${memberId}`,
      {
        headers: HEADERS
      }
    )
    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to fetch')
    }
    return response.data
  } catch (error) {
    console.error('Error:', error)
    const errorMessage =
      error.response?.data?.message || 'Opss! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const fetchMemberSavingsGoalMetrics = async (memberId) => {
  try {
    const response = await axios.get(
      `${API_URL}?action=get_member_savings_goal_metrics&member_id=${memberId}`,
      {
        headers: HEADERS
      }
    )
    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to fetch')
    }
    return response.data
  } catch (error) {
    console.error('Error:', error)
    const errorMessage =
      error.response?.data?.message || 'Opss! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const fetchMemberActiveLoansMetrics = async (memberId) => {
  try {
    const response = await axios.get(
      `${API_URL}?action=get_member_active_loans_metrics&member_id=${memberId}`,
      {
        headers: HEADERS
      }
    )
    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to fetch')
    }
    return response.data
  } catch (error) {
    console.error('Error:', error)
    const errorMessage =
      error.response?.data?.message || 'Opss! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const fetchMemberAccountStatusMetrics = async (memberId) => {
  try {
    const response = await axios.get(
      `${API_URL}?action=get_member_account_status_metrics&member_id=${memberId}`,
      {
        headers: HEADERS
      }
    )
    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to fetch')
    }
    return response.data
  } catch (error) {
    console.error('Error:', error)
    const errorMessage =
      error.response?.data?.message || 'Opss! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const fetchMemberUpcomingPayments = async (memberId) => {
  try {
    const response = await axios.get(
      `${API_URL}?action=get_member_upcoming_payments&member_id=${memberId}`,
      {
        headers: HEADERS
      }
    )
    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to fetch')
    }
    return response.data
  } catch (error) {
    console.error('Error:', error)
    const errorMessage =
      error.response?.data?.message || 'Opss! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const fetchMembersByApprovalStatus = async (
  status = 'approved,pending,rejected'
) => {
  try {
    const response = await axios.get(
      `${API_URL}?action=get_members_by_approval&approval=${status}`,
      {
        headers: HEADERS
      }
    )
    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to fetch')
    }

    return response.data
  } catch (error) {
    console.error('Error:', error)
    const errorMessage =
      error.response?.data?.message || 'Opss! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const registerMember = async (formData) => {
  try {
    formData.append('action', 'register_member')
    // for (let pair of formData.entries()) {
    //   console.log(
    //     pair[0] + ': ' + (pair[1] instanceof File ? pair[1].name : pair[1])
    //   )
    // }

    const response = await axios.post(`${API_URL}`, formData, {
      headers: {
        Accept: 'application/json'
        // Don't set Content-Type - axios will set it automatically with boundary for FormData
      },
      // Ensure proper handling of FormData
      transformRequest: [
        function (data) {
          return data
        }
      ]
    })

    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to register member')
    }

    return response.data
  } catch (error) {
    console.error('Error registering member:', error)
    const errorMessage =
      error.response?.data?.message || 'Oops! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const updateMember = async (formData) => {
  try {
    formData.append('action', 'update_registered_member')

    const response = await axios.post(`${API_URL}`, formData, {
      headers: {
        Accept: 'application/json'
        // Don't set Content-Type - axios will set it automatically with boundary for FormData
      },
      // Ensure proper handling of FormData
      transformRequest: [
        function (data) {
          return data
        }
      ]
    })

    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to update member')
    }

    return response.data
  } catch (error) {
    console.error('Error updating member:', error)
    const errorMessage =
      error.response?.data?.message || 'Oops! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

// Make updateMember available globally
// window.updateMember = updateMember

const fetchRegisteredMember = async (memberId) => {
  try {
    const response = await axios.get(
      `${API_URL}?action=get_registered_member&member_id=${memberId}`,
      {
        headers: HEADERS
      }
    )
    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to fetch')
    }

    return response.data
  } catch (error) {
    console.error('Error:', error)
    const errorMessage =
      error.response?.data?.message || 'Opss! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const updateRegisteredMember = async (formData) => {
  try {
    formData.append('action', 'update_registered_member')
    // for (let pair of formData.entries()) {
    //   console.log(
    //     pair[0] + ': ' + (pair[1] instanceof File ? pair[1].name : pair[1])
    //   )
    // }

    const response = await axios.post(`${API_URL}`, formData, {
      headers: {
        Accept: 'application/json'
      },
      transformRequest: [
        function (data) {
          return data
        }
      ]
    })

    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to updated member')
    }

    return response.data
  } catch (error) {
    console.error('Error updating member:', error)
    const errorMessage =
      error.response?.data?.message || 'Oops! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const updateMemberApproval = async (data) => {
  const payload = {
    action: 'update_member_approval_status',
    data: data
  }
  try {
    const response = await axios.post(`${API_URL}`, payload, {
      headers: HEADERS
    })

    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to update member')
    }

    return response.data
  } catch (error) {
    console.error('Error updating member:', error)
    const errorMessage =
      error.response?.data?.message || 'Oops! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const fetchRegisteredMembers = async () => {
  try {
    const response = await axios.get(
      `${API_URL}?action=get_registered_members`,
      {
        headers: HEADERS
      }
    )
    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to fetch')
    }
    return response.data
  } catch (error) {
    console.error('Error:', error)
    const errorMessage =
      error.response?.data?.message || 'Opss! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const applyServicesToMember = async (data) => {
  const payload = {
    action: 'apply_services_to_member',
    data: data
  }
  try {
    const response = await axios.post(`${API_URL}`, payload, {
      headers: HEADERS
    })

    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to update member')
    }
    return response.data
  } catch (error) {
    console.error('Error updating member:', error)
    const errorMessage =
      error.response?.data?.message || 'Oops! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const deposit = async (data) => {
  const payload = {
    action: 'deposit',
    data: data
  }

  try {
    const response = await axios.post(`${API_URL}`, payload, {
      headers: HEADERS
    })

    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to deposit')
    }

    return response.data
  } catch (error) {
    console.error('Error depositing:', error)
    const errorMessage =
      error.response?.data?.message || 'Oops! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const withdraw = async (data) => {
  const payload = {
    action: 'withdraw',
    data: data
  }

  try {
    const response = await axios.post(`${API_URL}`, payload, {
      headers: HEADERS
    })

    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to withdraw')
    }

    return response.data
  } catch (error) {
    console.error('Error withdrawing:', error)
    const errorMessage =
      error.response?.data?.message || 'Oops! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}

const fetchTransactionsByCooperative = async (memberId, cooperativeType) => {
  try {
    const response = await axios.get(
      `${API_URL}?action=get_transactions_by_cooperative&member_id=${memberId}&type_name=${cooperativeType}`,
      {
        headers: HEADERS
      }
    )

    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to fetch transactions')
    }

    return response.data
  } catch (error) {
    console.error('Error fetching transactions:', error)
    const errorMessage =
      error.response?.data?.message || 'Oops! Something went wrong.'
    toastr.error(errorMessage)
    throw error
  }
}
