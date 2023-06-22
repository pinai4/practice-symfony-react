import { CButton, CForm, CFormInput } from '@coreui/react'
import React from 'react'
import PropTypes from 'prop-types'

function ContactFilter({ onSubmitFilter }) {
  const handleSubmit = (event) => {
    event.preventDefault()
    event.stopPropagation()

    onSubmitFilter({
      name: event.target.name.value,
      phone: event.target.phone.value,
      email: event.target.email.value,
    })
  }

  return (
    <div className="mb-3">
      <CForm className="d-inline-flex" onSubmit={handleSubmit}>
        <CFormInput name="name" size="sm" className="me-2" placeholder="Full Name" />
        <CFormInput name="phone" size="sm" className="me-2" placeholder="Phone" />
        <CFormInput name="email" type="email" size="sm" className="me-2" placeholder="Email" />
        <CButton size="sm" type="submit" color="success" variant="outline">
          Filter
        </CButton>
      </CForm>
    </div>
  )
}

ContactFilter.propTypes = {
  onSubmitFilter: PropTypes.func,
}

export default ContactFilter
