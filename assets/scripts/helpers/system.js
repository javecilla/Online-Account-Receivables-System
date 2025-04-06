const ajaxRequest = async (
	url,
	method,
	data,
	successCallback,
	errorCallback
) => {
	try {
		// For GET requests, append params directly to URL
		if (method.toUpperCase() === 'GET' && data) {
			// Convert data object to URL string
			const queryString = Object.keys(data)
				.map(
					(key) => `${encodeURIComponent(key)}=${encodeURIComponent(data[key])}`
				)
				.join('&')
			// Append to URL with ? or & depending if URL already has parameters
			url = `${url}${url.includes('?') ? '&' : '?'}${queryString}`
		}

		// Prepare fetch options
		const options = {
			method: method.toUpperCase(),
			headers: {
				...HEADERS
			}
		}

		// Handle request body for non-GET requests
		if (method.toUpperCase() !== 'GET') {
			if (data instanceof FormData) {
				delete options.headers['Content-Type']
				options.body = data
			} else {
				options.headers['Content-Type'] = 'application/json'
				options.body = JSON.stringify(data)
			}
		}

		const response = await fetch(url, options)
		const result = await response.json()

		if (!response.ok) {
			if (response.status === HTTP_STATUS_CODE.UNAUTHORIZED) {
				window.location.href = '/login'
				return
			}
			throw new Error(result.message || 'Request failed')
		}

		successCallback(result)
	} catch (error) {
		console.error('Request error:', error)
		errorCallback(error.message || 'An error occurred')
	}
}

const debounce = (func, delay) => {
	let timeoutId
	return (...args) => {
		if (timeoutId) {
			clearTimeout(timeoutId)
		}
		timeoutId = setTimeout(() => {
			func.apply(this, args)
		}, delay)
	}
}
