import React from 'react'
import { CFooter } from '@coreui/react'

const Footer = () => {
  return (
    <CFooter>
      <div>
        <span className="ms-1">Domains Manager &copy; 2022 petProject</span>
      </div>
    </CFooter>
  )
}

export default React.memo(Footer)
