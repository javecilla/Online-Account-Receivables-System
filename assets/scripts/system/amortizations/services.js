const fetchAmortizationsByStatus = async (
  status = 'active,completed,defaulted'
) => {
  try {
    const response = await axios.get(
      `${API_URL}?action=get_amortizations_by_status&status=${status}`,
      {
        headers: HEADERS
      }
    )

    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to fetch amortizations by status'
      )
    }
    return response.data
  } catch (error) {
    console.error('Error fetching amortizations by status:', error)
    const errorMessage =
      error.response?.data?.message ||
      'Something went wrong while processing request.'
    toastr.error(errorMessage)
    throw error
  }
}

const fetchAmortizationsByApproval = async (approval = 'pending,rejected') => {
  try {
    const response = await axios.get(
      `${API_URL}?action=get_amortizations_by_approval&approval=${approval}`,
      {
        headers: HEADERS
      }
    )

    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to fetch amortizations by approval'
      )
    }
    return response.data
  } catch (error) {
    console.error('Error fetching amortizations by approval:', error)
    const errorMessage =
      error.response?.data?.message ||
      'Something went wrong while processing request.'
    toastr.error(errorMessage)
    throw error
  }
}

const updateAmortizationApproval = async (amortizationId, newStatus) => {
  try {
    const payload = {
      action: 'update_amortization_approval',
      data: {
        amortization_id: amortizationId,
        approval: newStatus
      }
    }

    const response = await axios.post(`${API_URL}`, payload, {
      headers: HEADERS
    })

    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to update approval status'
      )
    }
    return response.data
  } catch (error) {
    console.error('Error updating approval status:', error)
    const errorMessage =
      error.response?.data?.message ||
      'Something went wrong while processing request.'
    toastr.error(errorMessage)
    throw error
  }
}

const deleteAmortization = async (amortizationId) => {
  try {
    const payload = {
      action: 'delete_amortization',
      data: {
        amortization_id: amortizationId
      }
    }
    const response = await axios.post(`${API_URL}`, payload, {
      headers: HEADERS
    })
    if (!response.data || !response.data.success) {
      throw new Error(response.data?.message || 'Failed to delete amortization')
    }
    return response.data
  } catch (error) {
    console.error('Error deleting amortization:', error)
    const errorMessage =
      error.response?.data?.message ||
      'Something went wrong while processing request.'
    toastr.error(errorMessage)
    throw error
  }
}

const fetchAmortizationPayments = async () => {
  try {
    const response = await axios.get(
      `${API_URL}?action=get_amortization_payments`,
      {
        headers: HEADERS
      }
    )
    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to fetch amortization payments'
      )
    }
    return response.data
  } catch (error) {
    console.error('Error fetching amortization payments:', error)
    const errorMessage =
      error.response?.data?.message ||
      'Something went wrong while processing request.'
    toastr.error(errorMessage)
    throw error
  }
}

const fetchMemberApprovedAmortizations = async (
  memberId,
  status = 'active,completed,defaulted'
) => {
  try {
    const response = await axios.get(
      `${API_URL}?action=get_member_approved_amortizations&status=${status}&member_id=${memberId}`,
      {
        headers: HEADERS
      }
    )

    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message ||
          'Failed to fetch member approved amortizations'
      )
    }
    return response.data
  } catch (error) {
    console.error('Error fetching member approved amortization:', error)
    const errorMessage =
      error.response?.data?.message ||
      'Something went wrong while processing request.'
    toastr.error(errorMessage)
    throw error
  }
}

const fetchMemberRequestAmortizations = async (
  memberId,
  approval = 'pending,rejected'
) => {
  try {
    const response = await axios.get(
      `${API_URL}?action=get_member_request_amortizations&approval=${approval}&member_id=${memberId}`,
      {
        headers: HEADERS
      }
    )

    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to fetch member request amortizations'
      )
    }
    return response.data
  } catch (error) {
    console.error('Error fetching member request amortization:', error)
    const errorMessage =
      error.response?.data?.message ||
      'Something went wrong while processing request.'
    toastr.error(errorMessage)
    throw error
  }
}
