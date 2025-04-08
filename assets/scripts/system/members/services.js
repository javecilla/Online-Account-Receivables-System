const fetchMembershipTypes = async () => {
  try {
    const response = await axios.get(`${API_URL}?action=get_membership_types`)
    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to fetch membership types'
      )
    }
    return response.data
  } catch (error) {
    console.error('Error fetching membership types:', error)
    const errorMessage =
      error.response?.data?.message || 'Something went wrong on our end'
    toastr.error(errorMessage)
    throw error
  }
}
const fetchMemberByAccount = async (accountId) => {
  // return { success: true, message: 'test', data: { test: 1 } }
  try {
    const response = await axios.get(
      `${API_URL}?action=get_member_by_account&account_id=${accountId}`
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
      error.response?.data?.message || 'Something went wrong on our end'
    toastr.error(errorMessage)
    throw error
  }
}
