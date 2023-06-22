import React from 'react'
import useAuth from './Provider/useAuth'
import { CAlert, CCard, CCardBody, CCol, CContainer, CRow } from '@coreui/react'

function OAuth() {
  const { error, loading } = useAuth()

  return (
    <div className="bg-light min-vh-100 d-flex flex-row align-items-center">
      <CContainer>
        <CRow className="justify-content-center">
          <CCol md={9} lg={7} xl={6}>
            <CCard className="mx-4">
              <CCardBody className="p-4">
                <h1>Auth</h1>
                {error ? <CAlert color="danger">{error}</CAlert> : null}
                {loading ? <p>Loading...</p> : null}
              </CCardBody>
            </CCard>
          </CCol>
        </CRow>
      </CContainer>
    </div>
  )
}

export default OAuth
