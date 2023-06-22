import React, { useCallback, useEffect, useState } from 'react'
import { useParams } from 'react-router-dom'
import useAuth from '../../../../OAuth/Provider/useAuth'
import Page404 from '../../Page404'
import api, { parseError } from '../../../../Api'
import Default from '../../../Layout/Default'
import { CCard, CCardBody, CCardHeader, CCol, CRow } from '@coreui/react'

function Domain() {
  const params = useParams()
  const { getToken } = useAuth()
  const [domain, setDomain] = useState(null)

  if (!params.id.match(/^[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12}$/)) {
    return <Page404 />
  }

  const id = params.id

  const loadData = useCallback(() => {
    getToken()
      .then((accessToken) =>
        api.get('/domains/' + id, {
          Accept: 'application/json',
          'Content-type': 'application/json',
          Authorization: accessToken,
        })
      )
      .then((result) => {
        setDomain(result)
      })
      .catch(async (error) => {
        console.log(await parseError(error))
      })
  }, [id])

  useEffect(() => {
    setTimeout(loadData, 1000)
  }, [loadData])

  return (
    <Default>
      <>
        <CRow>
          <CCol xs>
            <CCard className="mb-4">
              <CCardHeader className="fs-5">Domain Details</CCardHeader>
              <CCardBody>
                {domain ? (
                  <>
                    <CRow className="mb-3">
                      <CCol sm={2} className="fw-bold">
                        Name:
                      </CCol>
                      <CCol sm={10}>{domain.name}</CCol>
                    </CRow>
                    <CRow className="mb-3">
                      <CCol sm={2} className="fw-bold">
                        Creation Date:
                      </CCol>
                      <CCol sm={2}>{domain.cr_date}</CCol>
                    </CRow>
                    <CRow className="mb-3">
                      <CCol sm={2} className="fw-bold">
                        Expiration Date:
                      </CCol>
                      <CCol sm={10}>{domain.exp_date}</CCol>
                    </CRow>
                  </>
                ) : null}
              </CCardBody>
            </CCard>
          </CCol>
        </CRow>
      </>
    </Default>
  )
}

export default Domain
