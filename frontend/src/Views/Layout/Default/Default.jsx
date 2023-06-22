import React, { Suspense } from 'react'
import { CContainer, CSpinner } from '@coreui/react'
import PropTypes from 'prop-types'
import Sidebar from './Sidebar/Sidebar'
import Header from './Header/Header'
import Footer from './Footer/Footer'

function Default({ children }) {
  return (
    <div>
      <Sidebar />
      <div className="wrapper d-flex flex-column min-vh-100 bg-light">
        <Header />
        <div className="body flex-grow-1 px-3">
          <CContainer lg>
            <Suspense fallback={<CSpinner color="primary" />}>{children}</Suspense>
          </CContainer>
        </div>
        <Footer />
      </div>
    </div>
  )
}

Default.propTypes = {
  children: PropTypes.any,
}

export default Default
