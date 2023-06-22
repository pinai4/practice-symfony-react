import React, { useState } from 'react'
import PropTypes from 'prop-types'
import {
  CAlert,
  CButton,
  CCol,
  CForm,
  CFormInput,
  CFormSelect,
  CModal,
  CModalBody,
  CModalFooter,
  CModalHeader,
  CModalTitle,
} from '@coreui/react'
import useAuth from '../../../OAuth/Provider/useAuth'
import api, { parseError, parseErrors } from '../../../Api'
import { v4 as uuidv4 } from 'uuid'
import countryList from './countryList'
import { useNavigate } from 'react-router-dom'

function ContactNewModal({ visibleNewContact, onCloseModal }) {
  const { getToken } = useAuth()
  const navigate = useNavigate()

  const [formData, setFormData] = useState({
    name: '',
    organization: '',
    email: '',
    phone: '',
    address: '',
    city: '',
    state: '',
    country: '',
    zip: '',
  })

  const [buttonActive, setButtonActive] = useState(true)
  const [errors, setErrors] = useState({})
  const [error, setError] = useState(null)

  const handleChange = (event) => {
    const input = event.target
    setFormData({
      ...formData,
      [input.name]: input.value,
    })
  }

  const handleSubmit = () => {
    const newId = uuidv4()

    setButtonActive(false)
    setErrors({})
    setError(null)

    getToken()
      .then((accessToken) =>
        api.post(
          '/contacts',
          {
            id: newId,
            name: formData.name,
            organization: formData.organization,
            email: formData.email,
            phone: formData.phone,
            address: formData.address,
            city: formData.city,
            state: formData.state,
            country: formData.country,
            zip: formData.zip,
          },
          {
            Accept: 'application/json',
            'Content-type': 'application/json',
            Authorization: accessToken,
          }
        )
      )
      .then(() => {
        setButtonActive(true)
        navigate('/contact/' + newId, { replace: true })
      })
      .catch(async (error) => {
        setErrors(await parseErrors(error))
        setError(await parseError(error))
        setButtonActive(true)
      })
  }

  return (
    <CModal
      size="lg"
      backdrop="static"
      visible={visibleNewContact}
      onClose={() => onCloseModal(false)}
    >
      <CModalHeader>
        <CModalTitle>New Contact</CModalTitle>
      </CModalHeader>
      <CModalBody>
        {error ? <CAlert color="danger">{error}</CAlert> : null}
        <CForm className="row g-3" id="newContactForm" onSubmit={handleSubmit}>
          <CCol md={6}>
            <CFormInput
              label="Full Name"
              name="name"
              autoComplete="name"
              value={formData.name}
              onChange={handleChange}
              feedback={errors.name}
              invalid={!!errors.name}
              required
            />
          </CCol>
          <CCol md={6}>
            <CFormInput
              label="Organization"
              name="organization"
              value={formData.organization}
              onChange={handleChange}
              feedback={errors.organization}
              invalid={!!errors.organization}
            />
          </CCol>
          <CCol md={6}>
            <CFormInput
              label="Email"
              type="email"
              name="email"
              autoComplete="email"
              value={formData.email}
              onChange={handleChange}
              feedback={errors.email}
              invalid={!!errors.email}
              required
            />
          </CCol>
          <CCol md={6}>
            <CFormInput
              label="Phone"
              name="phone"
              autoComplete="phone"
              value={formData.phone}
              onChange={handleChange}
              feedback={errors.phone}
              invalid={!!errors.phone}
              required
            />
          </CCol>
          <CCol md={12}>
            <CFormInput
              label="Street"
              name="address"
              autoComplete="address"
              value={formData.address}
              onChange={handleChange}
              feedback={errors.address}
              invalid={!!errors.address}
              required
            />
          </CCol>
          <CCol md={6}>
            <CFormInput
              label="City"
              name="city"
              autoComplete="city"
              value={formData.city}
              onChange={handleChange}
              feedback={errors.city}
              invalid={!!errors.city}
              required
            />
          </CCol>
          <CCol md={6}>
            <CFormInput
              label="State"
              name="state"
              autoComplete="state"
              value={formData.state}
              onChange={handleChange}
              feedback={errors.state}
              invalid={!!errors.state}
              required
            />
          </CCol>
          <CCol md={6}>
            <CFormSelect
              label="Country"
              name="country"
              value={formData.country}
              onChange={handleChange}
              feedback={errors.country}
              invalid={!!errors.country}
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
              label="Zip"
              name="zip"
              autoComplete="zip"
              value={formData.zip}
              onChange={handleChange}
              feedback={errors.zip}
              invalid={!!errors.zip}
              required
            />
          </CCol>
        </CForm>
      </CModalBody>
      <CModalFooter>
        <CButton color="secondary" onClick={() => onCloseModal(false)}>
          Close
        </CButton>
        <CButton color="primary" disabled={!buttonActive} onClick={handleSubmit}>
          Create Contact
        </CButton>
      </CModalFooter>
    </CModal>
  )
}

ContactNewModal.propTypes = {
  visibleNewContact: PropTypes.bool.isRequired,
  onCloseModal: PropTypes.func.isRequired,
}

export default ContactNewModal
