import React, { useState } from 'react'
import {
  CAlert,
  CAlertHeading,
  CAlertLink,
  CButton,
  CCard,
  CCardBody,
  CCol,
  CContainer,
  CForm,
  CFormCheck,
  CFormInput,
  CInputGroup,
  CInputGroupText,
  CRow,
} from '@coreui/react'
import CIcon from '@coreui/icons-react'
import { cilLockLocked, cilUser } from '@coreui/icons'
import api, { parseError, parseErrors } from '../../../Api'
import { v4 as uuidv4 } from 'uuid'

const Register = () => {
  const [formData, setFormData] = useState({
    email: '',
    password: '',
    name: '',
  })

  const [buttonActive, setButtonActive] = useState(true)
  const [errors, setErrors] = useState({})
  const [error, setError] = useState(null)
  const [success, setSuccess] = useState(false)

  const handleChange = (event) => {
    const input = event.target
    setFormData({
      ...formData,
      [input.name]: input.value,
    })
  }

  const handleSubmit = (event) => {
    event.preventDefault()
    event.stopPropagation()

    setButtonActive(false)
    setErrors({})
    setError(null)
    setSuccess(false)

    api
      .post('/auth/register', {
        id: uuidv4(),
        name: formData.name,
        email: formData.email,
        password: formData.password,
      })
      .then(() => {
        setSuccess(true)
        setButtonActive(true)
      })
      .catch(async (error) => {
        setErrors(await parseErrors(error))
        setError(await parseError(error))
        setButtonActive(true)
      })
  }

  return (
    <div className="bg-light min-vh-100 d-flex flex-row align-items-center">
      <CContainer>
        <CRow className="justify-content-center">
          <CCol md={9} lg={7} xl={6}>
            <CCard className="mx-4">
              <CCardBody className="p-4">
                <h1>Registration form</h1>
                <p className="text-medium-emphasis">Create your account</p>
                {error ? <CAlert color="danger">{error}</CAlert> : null}
                {!success ? (
                  <CForm onSubmit={handleSubmit}>
                    <CInputGroup className="mb-3">
                      <CInputGroupText>
                        <CIcon icon={cilUser} />
                      </CInputGroupText>
                      <CFormInput
                        placeholder="Full Name"
                        name="name"
                        autoComplete="name"
                        value={formData.name}
                        onChange={handleChange}
                        feedback={errors.name}
                        invalid={!!errors.name}
                        required
                      />
                    </CInputGroup>
                    <CInputGroup className="mb-3">
                      <CInputGroupText>@</CInputGroupText>
                      <CFormInput
                        placeholder="Email Address"
                        type="email"
                        name="email"
                        autoComplete="email"
                        value={formData.email}
                        onChange={handleChange}
                        feedback={errors.email}
                        invalid={!!errors.email}
                        required
                      />
                    </CInputGroup>
                    <CInputGroup className="mb-3">
                      <CInputGroupText>
                        <CIcon icon={cilLockLocked} />
                      </CInputGroupText>
                      <CFormInput
                        placeholder="Password"
                        type="password"
                        name="password"
                        autoComplete="new-password"
                        value={formData.password}
                        onChange={handleChange}
                        feedback={errors.password}
                        invalid={!!errors.password}
                        required
                      />
                    </CInputGroup>
                    <CInputGroup className="mb-3">
                      <CFormCheck type="checkbox" label="Agree to terms and conditions" required />
                    </CInputGroup>
                    <div className="d-grid">
                      <CButton color="success" type="submit" disabled={!buttonActive}>
                        Create Account
                      </CButton>
                    </div>
                  </CForm>
                ) : (
                  <CAlert color="success">
                    <CAlertHeading tag="h4">Well done!</CAlertHeading>
                    <p>
                      Account has been created successfully. Now you can easily login into your
                      account.
                    </p>
                    <hr />
                    <p className="mb-0">
                      Go to <CAlertLink href="#">Log In</CAlertLink> form.
                    </p>
                  </CAlert>
                )}
              </CCardBody>
            </CCard>
          </CCol>
        </CRow>
      </CContainer>
    </div>
  )
}

export default Register
