const debounce = (func, delay) => {
  let timeoutId
  return function (...args) {
    const context = this
    if (timeoutId) {
      clearTimeout(timeoutId)
    }
    timeoutId = setTimeout(function () {
      func.apply(context, args)
    }, delay)
  }
}
