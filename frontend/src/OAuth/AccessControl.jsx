import useAuth from './Provider/useAuth'
import PropTypes from 'prop-types'

function AccessControl({ children }) {
  const { isAuthenticated, login } = useAuth()

  if (!isAuthenticated) {
    login()
  }

  return children
}

AccessControl.propTypes = {
  children: PropTypes.any,
}

export default AccessControl
