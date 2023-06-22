import React from 'react'
import Default from '../../Layout/Default'
import { CCard, CCardBody, CCardHeader, CCol, CRow } from '@coreui/react'

function Dashboard() {
  return (
    <Default>
      <>
        <CRow>
          <CCol xs>
            <CCard className="mb-4">
              <CCardHeader className="fs-5">Stats</CCardHeader>
              <CCardBody>
                <CRow>
                  <CCol xs={12} md={6} xl={6}>
                    <CRow>
                      <CCol sm={6}>
                        <div className="border-start border-start-4 border-start-info py-1 px-3">
                          <div className="text-medium-emphasis small">Domains</div>
                          <div className="fs-5 fw-semibold">9,123</div>
                        </div>
                      </CCol>
                      <CCol sm={6}>
                        <div className="border-start border-start-4 border-start-danger py-1 px-3 mb-3">
                          <div className="text-medium-emphasis small">Contacts</div>
                          <div className="fs-5 fw-semibold">22,643</div>
                        </div>
                      </CCol>
                    </CRow>
                  </CCol>
                </CRow>
              </CCardBody>
            </CCard>
          </CCol>
        </CRow>
      </>
    </Default>
  )
}

export default Dashboard
