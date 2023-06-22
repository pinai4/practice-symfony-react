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
import { useNavigate } from 'react-router-dom'

function RegisterModal({ visibleRegisterDomain, onCloseModal }) {
  const { getToken } = useAuth()
  const navigate = useNavigate()

  const [formData, setFormData] = useState({
    name: '',
    period: '0',
    ownerContactId: '',
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
          '/domains',
          {
            id: newId,
            name: formData.name,
            period: parseInt(formData.period, 10),
            owner_contact_id: formData.ownerContactId,
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
        navigate('/domains/' + newId, { replace: true })
      })
      .catch(async (error) => {
        setErrors(await parseErrors(error))
        setError(await parseError(error))
        setButtonActive(true)
      })
  }

  return (
    <CModal backdrop="static" visible={visibleRegisterDomain} onClose={() => onCloseModal(false)}>
      <CModalHeader>
        <CModalTitle>Register Domain</CModalTitle>
      </CModalHeader>
      <CModalBody>
        {error ? <CAlert color="danger">{error}</CAlert> : null}
        <CForm className="row g-3" id="registerDomainForm" onSubmit={handleSubmit}>
          <CCol md={12}>
            <CFormInput
              label="Domain Name"
              name="name"
              value={formData.name}
              onChange={handleChange}
              feedback={errors.name}
              invalid={!!errors.name}
              required
            />
          </CCol>
          <CCol md={12}>
            <CFormSelect
              label="Registration Period"
              name="period"
              value={formData.period}
              onChange={handleChange}
              feedback={errors.period}
              invalid={!!errors.period}
            >
              <option value="0">...</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
            </CFormSelect>
          </CCol>
          <CCol md={12}>
            <CFormInput
              label="Owner Contact Id"
              name="ownerContactId"
              value={formData.ownerContactId}
              onChange={handleChange}
              feedback={errors.ownerContactId}
              invalid={!!errors.ownerContactId}
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
          Register Domain
        </CButton>
      </CModalFooter>
    </CModal>
  )
}

RegisterModal.propTypes = {
  visibleRegisterDomain: PropTypes.bool.isRequired,
  onCloseModal: PropTypes.func.isRequired,
}

export default RegisterModal
