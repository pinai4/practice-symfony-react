import {
  CCard,
  CCardBody,
  CCardHeader,
  CCol,
  CForm,
  CFormInput,
  CFormSelect,
  CRow,
} from '@coreui/react'
import Default from '../../Layout/Default'
import React, { useCallback, useEffect, useState } from 'react'
import { useParams } from 'react-router-dom'
import Page404 from '../Page404'
import api, { parseError } from '../../../Api'
import useAuth from '../../../OAuth/Provider/useAuth'
import countryList from './countryList'

function ContactDetails() {
  const params = useParams()
  const { getToken } = useAuth()
  const [formData, setFormData] = useState(null)

  if (!params.id.match(/^[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12}$/)) {
    return <Page404 />
  }

  const id = params.id

  const loadData = useCallback(() => {
    getToken()
      .then((accessToken) =>
        api.get('/contacts/' + id, {
          Accept: 'application/json',
          'Content-type': 'application/json',
          Authorization: accessToken,
        })
      )
      .then((result) => {
        setFormData(result)
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
              <CCardHeader className="fs-5">Contact Details</CCardHeader>
              <CCardBody>
                {formData ? (
                  <CCol md={7}>
                    <CForm className="row g-3" id="newContactForm">
                      <CCol md={6}>
                        <CFormInput
                          disabled
                          label="Full Name"
                          name="name"
                          autoComplete="name"
                          value={formData.name}
                          required
                        />
                      </CCol>
                      <CCol md={6}>
                        <CFormInput
                          disabled
                          label="Organization"
                          name="organization"
                          value={formData.organization ? formData.organization : ''}
                        />
                      </CCol>
                      <CCol md={6}>
                        <CFormInput
                          disabled
                          label="Email"
                          type="email"
                          name="email"
                          autoComplete="email"
                          value={formData.email}
                          required
                        />
                      </CCol>
                      <CCol md={6}>
                        <CFormInput
                          disabled
                          label="Phone"
                          name="phone"
                          autoComplete="phone"
                          value={formData.phone}
                          required
                        />
                      </CCol>
                      <CCol md={12}>
                        <CFormInput
                          disabled
                          label="Street"
                          name="address"
                          autoComplete="address"
                          value={formData.address}
                          required
                        />
                      </CCol>
                      <CCol md={6}>
                        <CFormInput
                          disabled
                          label="City"
                          name="city"
                          autoComplete="city"
                          value={formData.city}
                          required
                        />
                      </CCol>
                      <CCol md={6}>
                        <CFormInput
                          disabled
                          label="State"
                          name="state"
                          autoComplete="state"
                          value={formData.state}
                          required
                        />
                      </CCol>
                      <CCol md={6}>
                        <CFormSelect
                          disabled
                          label="Country"
                          name="country"
                          value={formData.country}
                        >
                          <option>...</option>
                          {Object.keys(countryList).map((key) => (
                            <option key={key} value={key}>
                              {countryList[key]}
                            </option>
                          ))}
                        </CFormSelect>
                      </CCol>
                      <CCol md={6}>
                        <CFormInput
                          disabled
                          label="Zip"
                          name="zip"
                          autoComplete="zip"
                          value={formData.zip}
                          required
                        />
                      </CCol>
                    </CForm>
                  </CCol>
                ) : null}
              </CCardBody>
            </CCard>
          </CCol>
        </CRow>
      </>
    </Default>
  )
}

export default ContactDetails
