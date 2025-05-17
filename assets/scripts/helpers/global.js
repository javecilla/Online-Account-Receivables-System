const openModal = (modalId) => {
  $(modalId).attr('data-backdrop', 'static').modal('show')
}

const closeModal = (modalId) => {
  $(modalId).modal('hide')
}

const isEmpty = (field) => {
  return field === ''
}
const isNull = (field) => {
  return field === null
}
const isNA = (field) => {
  return field === 'N/A'
}

const getHashFragment = () => {
  return window.location.hash.substring(1)
}

const setHashFragment = (hash) => {
  window.location.hash = hash
}

const removeHashFragment = () => {
  window.location.hash = ''
}

const addURLParams = (paramName, paramValue) => {
  const url = new URL(window.location)
  url.searchParams.set(paramName, paramValue)
  window.history.pushState({ path: url.href }, '', url.href)
}

const removeURLParams = (paramName) => {
  const url = new URL(window.location)
  url.searchParams.delete(paramName)
  window.history.pushState({ path: url.href }, '', url.href)
}

const removeAllParams = () => {
  const url = new URL(window.location)
  url.searchParams.forEach((value, key) => {
    url.searchParams.delete(key)
  })
  window.history.pushState({ path: url.href }, '', url.href)
}

const getURLParams = (paramName) => {
  const url = new URL(window.location)
  return url.searchParams.get(paramName)
}

const createDecoyURL = (symbol = '', mention, urlName) => {
  let newUrl =
    '/' + symbol + '' + mention.toLowerCase() + '/' + urlName.toLowerCase()

  history.replaceState(null, null, newUrl.trim())
}

const checkDomainExists = async (domain) => {
  try {
    const response = await fetch(
      `https://dns.google/resolve?name=${domain}&type=MX`
    )
    const data = await response.json()
    return data.Status === 0
  } catch (error) {
    console.error('DNS lookup failed:', error)
    return false
  }
}

const validatePassword = (password) => {
  return password.length >= 8
}

const validateContactNumber = (number) => {
  const contactRegex = /^[0-9]{10}$/
  return contactRegex.test(number)
}

const validateRequiredField = (value) => {
  return value.trim() !== ''
}

const validateNumericField = (value) => {
  return !isNaN(value) && parseFloat(value) > 0
}

const isValidEmail = async (email) => {
  if (isEmpty(email)) {
    return false
  }

  const emailParts = email.split('@')
  if (emailParts.length !== 2) {
    return false
  }

  const localPart = emailParts[0]
  const domainPart = emailParts[1]

  const localPartRegex =
    /^[a-zA-Z0-9!#$%&'*+\-/=?^_`{|}~]+(\.[a-zA-Z0-9!#$%&'*+\-/=?^_`{|}~]+)*$/
  if (!localPartRegex.test(localPart)) {
    return false
  }
  //check local part for consecutive periods
  if (localPart.includes('..')) {
    return false
  }

  //check local part for leading or trailing period
  if (localPart.startsWith('.') || localPart.endsWith('.')) {
    return false
  }
  //check domain part for invalid characters
  const domainPartRegex = /^[a-zA-Z0-9.-]+$/
  if (!domainPartRegex.test(domainPart)) {
    return false
  }
  //check domain part for consecutive hyphens
  if (domainPart.includes('--')) {
    return false
  }
  //check domain part for leading or trailing hyphen
  if (domainPart.startsWith('-') || domainPart.endsWith('-')) {
    return false
  }
  //check domain part for valid TLD
  const tldRegex = /^[a-zA-Z]{2,}$/
  const domainParts = domainPart.split('.')
  if (
    domainParts.length < 2 ||
    !tldRegex.test(domainParts[domainParts.length - 1])
  ) {
    return false
  }

  // Add DNS validation
  return await checkDomainExists(domainPart)
}

const disabledButton = (btn) => {
  btn.setAttribute('disabled', 'disabled')
  btn.classList.add('disabled')
  btn.style = 'cursor: not-allowed'
}

const enableButton = (btn) => {
  btn.removeAttribute('disabled')
  btn.classList.remove('disabled')
  btn.style = 'cursor: pointer'
}

const formatReadableDate = (date) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric' }
  return new Date(date).toLocaleDateString('en-US', options)
}

const formatReadableDateTime = (dateTime) => {
  //console.log(dateTime)
  if (!dateTime) return 'N/A'

  try {
    const dateTimeParts = dateTime.split(' ')
    if (dateTimeParts.length !== 2) return 'Invalid date format'

    const dateParts = dateTimeParts[0].split('-')
    const timeParts = dateTimeParts[1].split(':')

    if (dateParts.length !== 3 || timeParts.length !== 3) {
      return 'Invalid date format'
    }

    const options = {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: 'numeric',
      minute: 'numeric',
      second: 'numeric',
      hour12: true
    }

    const formattedDate = new Date(
      parseInt(dateParts[0]), //year
      parseInt(dateParts[1]) - 1, //month (0-indexed)
      parseInt(dateParts[2]), //day
      parseInt(timeParts[0]), //hours
      parseInt(timeParts[1]), //minutes
      parseInt(timeParts[2]) //seconds
    ).toLocaleString('en-US', options)

    return formattedDate || 'N/A'
  } catch (error) {
    console.error('Date formatting error:', error)
    return 'N/A'
  }
}

const formatCurrency = (value) => {
  if (value === null || value === undefined) return '₱0.00'
  return `₱${parseFloat(value).toLocaleString('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  })}`
}

const validatePasswordStrict = function (password) {
  const hasUpperCase = /[A-Z]/.test(password)
  const hasLowerCase = /[a-z]/.test(password)
  const hasNumber = /\d/.test(password)
  const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>[\]\/\\]/.test(password)
  const isValidLength = password.length >= 8

  return (
    hasUpperCase && hasLowerCase && hasNumber && hasSpecialChar && isValidLength
  )
}
