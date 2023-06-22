import { CButton, CTable } from '@coreui/react'
import React from 'react'
import PropTypes from 'prop-types'
import { cilArrowBottom, cilArrowTop, cilSwapVertical } from '@coreui/icons'
import CIcon from '@coreui/icons-react'
import { Link } from 'react-router-dom'

function ContactTable({ contacts, onApplySorting, sort, direction }) {
  const columnHeaderLabel = (key, label) => {
    let iconStyle = { icon: cilSwapVertical, class: 'opacity-25' }
    let nextSort = key
    let nextDir = 'asc'
    if (sort === key) {
      if (direction === 'desc') {
        iconStyle = { icon: cilArrowTop, class: '' }
        nextSort = ''
        nextDir = ''
      } else {
        iconStyle = { icon: cilArrowBottom, class: '' }
        nextDir = 'desc'
      }
    }

    return (
      <div role="button" onClick={() => onApplySorting(nextSort, nextDir)}>
        <div className="d-inline">{label}</div>
        <span className="float-end me-1">
          <CIcon icon={iconStyle.icon} className={iconStyle.class} />
        </span>
      </div>
    )
  }

  const columns = [
    {
      key: 'id',
      label: 'Id',
      _props: { scope: 'col' },
    },
    {
      key: 'name',
      label: columnHeaderLabel('name', 'Name'),
      _props: { scope: 'col' },
    },
    {
      key: 'address',
      label: 'Address',
      _props: { scope: 'col' },
    },
    {
      key: 'country',
      label: columnHeaderLabel('country', 'Country'),
      _props: { scope: 'col' },
    },
    {
      key: 'contact_info',
      label: 'Contact Info',
      _props: { scope: 'col' },
    },
    {
      key: 'actions',
      label: '',
      _props: { scope: 'col' },
    },
  ]

  const items = contacts.map((contact) => ({
    id: <Link to={'/contact/' + contact.id}>{contact.id}</Link>,
    name: contact.name,
    address: (
      <>
        <div>{contact.address}</div>
        <div>
          {contact.zip} {contact.city}, {contact.state}
        </div>
      </>
    ),
    country: contact.country,
    contact_info: (
      <>
        <div>Phone: {contact.phone}</div>
        <div>Email: {contact.email}</div>
      </>
    ),
    actions: (
      <>
        <CButton size="sm" color="danger" variant="outline">
          Delete
        </CButton>
      </>
    ),
    _cellProps: { id: { scope: 'row' } },
  }))

  return (
    <>
      <CTable
        small
        align="middle"
        columns={columns}
        items={items}
        tableHeadProps={{ color: 'dark' }}
      />
    </>
  )
}

ContactTable.propTypes = {
  contacts: PropTypes.array.isRequired,
  onApplySorting: PropTypes.func.isRequired,
  sort: PropTypes.string.isRequired,
  direction: PropTypes.string.isRequired,
}

export default ContactTable
