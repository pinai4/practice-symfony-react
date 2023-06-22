import { CButton, CForm, CFormInput } from '@coreui/react'
import React from 'react'
import PropTypes from 'prop-types'

function Filter({ onSubmitFilter }) {
  const handleSubmit = (event) => {
    event.preventDefault()
    event.stopPropagation()

    onSubmitFilter({
      name: event.target.name.value,
    })
  }

  return (
    <div className="mb-3">
      <CForm className="d-inline-flex" onSubmit={handleSubmit}>
        <CFormInput name="name" size="sm" className="me-2" placeholder="Domain" />
        <CButton size="sm" type="submit" color="success" variant="outline">
          Filter
        </CButton>
      </CForm>
    </div>
  )
}

Filter.propTypes = {
  onSubmitFilter: PropTypes.func,
}

export default Filter
