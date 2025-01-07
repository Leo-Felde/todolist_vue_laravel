export const setCookie = (name, value, days = null) => {
  let cookieValue = null
  if (days) {
    const expirationDate = new Date()
    expirationDate.setDate(expirationDate.getDate() + days)
    
    cookieValue = encodeURIComponent(value) + '; expires=' + expirationDate.toUTCString()
  } else {
    cookieValue = encodeURIComponent(value)
  }

  document.cookie = name + '=' + cookieValue
}

export const getCookie = (name) => {
  const cookieName = name + '='
  const decodedCookie = decodeURIComponent(document.cookie)
  const cookieArray = decodedCookie.split(';')

  for (let i = 0; i < cookieArray.length; i++) {
    let cookie = cookieArray[i]
    while (cookie.charAt(0) === ' ') {
      cookie = cookie.substring(1)
    }
    if (cookie.indexOf(cookieName) === 0) {
      return cookie.substring(cookieName.length, cookie.length)
    }
  }

  return null
}

export const deleteCookie = (name) => {
  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC'
}

export default getCookie
