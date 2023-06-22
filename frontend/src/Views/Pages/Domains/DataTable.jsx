import { CButton, CTable } from '@coreui/react'
import React from 'react'
import PropTypes from 'prop-types'
import { cilArrowBottom, cilArrowTop, cilSwapVertical } from '@coreui/icons'
import CIcon from '@coreui/icons-react'
import { Link } from 'react-router-dom'

function DataTable({ domains, onApplySorting, sort, direction }) {
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
      label: columnHeaderLabel('name', 'Domain'),
      _props: { scope: 'col' },
    },
    {
      key: 'cr_date',
      label: columnHeaderLabel('cr_date', 'Registration Date'),
      _props: { scope: 'col' },
    },
    {
      key: 'exp_date',
      label: columnHeaderLabel('exp_date', 'Expiration Date'),
      _props: { scope: 'col' },
    },
    {
      key: 'contacts',
      label: 'Contacts',
      _props: { scope: 'col' },
    },
    {
      key: 'actions',
      label: '',
      _props: { scope: 'col' },
    },
  ]

  const items = domains.map((domain) => ({
    id: <Link to={'/domains/' + domain.id}>{domain.id}</Link>,
    name: domain.name,
    cr_date: domain.cr_date,
    exp_date: domain.exp_date,
    contacts: (
      <>
        {domain.contacts.map((contact, index) => (
          <div key={index}>
            {contact.type}: <Link to={'/contact/' + contact.id}>{contact.id}</Link>
          </div>
        ))}
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

DataTable.propTypes = {
  domains: PropTypes.array.isRequired,
  onApplySorting: PropTypes.func.isRequired,
  sort: PropTypes.string.isRequired,
  direction: PropTypes.string.isRequired,
}

export default DataTable
