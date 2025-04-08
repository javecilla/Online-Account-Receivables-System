const fetchEmployeeByAccount = async (accountId) => {
  // return { success: true, message: 'test', data: { test: 1 } }
  try {
    const response = await axios.get(
      `${API_URL}?action=get_employee_by_account&account_id=${accountId}`
    )
    if (!response.data || !response.data.success) {
      throw new Error(
        response.data?.message || 'Failed to fetch employee by its account'
      )
    }
    return response.data
  } catch (error) {
    console.error('Error fetching employee by its account:', error)
    const errorMessage =
      error.response?.data?.message || 'Something went wrong on our end'
    toastr.error(errorMessage)
    throw error
  }
}
