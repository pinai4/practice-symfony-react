import React from 'react'
import { CFormSelect, CPagination, CPaginationItem } from '@coreui/react'
import PropTypes from 'prop-types'

function ContactPagination({ info, onClickPage, onClickPerPage }) {
  if (info === null || !info.pages) {
    return null
  }

  const list = []
  for (let i = 1; i <= info.pages; i++) {
    list.push(i)
  }

  const itemsList = list.map((val) => (
    <CPaginationItem
      role="button"
      active={val === info.page}
      onClick={() => onClickPage(val)}
      key={val}
    >
      {val}
    </CPaginationItem>
  ))

  const firstPage = 1
  const previousPage = info.page - 1 > 0 ? info.page - 1 : 1
  const lastPage = info.pages
  const nextPage = info.page + 1 < info.pages ? info.page + 1 : info.pages

  return (
    <div className="d-flex">
      <div className="me-2">Items per page:</div>
      <div>
        <CFormSelect
          size="sm"
          className="mb-3"
          aria-label="Select items per page"
          value={info.per_page}
          onChange={(e) => onClickPerPage(e.target.value)}
        >
          <option value="1">1</option>
          <option value="5">5</option>
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
        </CFormSelect>
      </div>
      <div className="ms-auto">
        <CPagination size="sm" align="end">
          <CPaginationItem
            role="button"
            aria-label="Go to First Page"
            disabled={info.page === 1}
            onClick={() => onClickPage(firstPage)}
          >
            <span aria-hidden="true">&laquo;</span>
          </CPaginationItem>
          <CPaginationItem
            role="button"
            aria-label="Go to Previous Page"
            disabled={info.page === 1}
            onClick={() => onClickPage(previousPage)}
          >
            <span aria-hidden="true">&lsaquo;</span>
          </CPaginationItem>
          {itemsList}
          <CPaginationItem
            role="button"
            aria-label="Go to Next Page"
            disabled={info.page === info.pages}
            onClick={() => onClickPage(nextPage)}
          >
            <span aria-hidden="true">&rsaquo;</span>
          </CPaginationItem>
          <CPaginationItem
            role="button"
            aria-label="Go to Last Page"
            disabled={info.page === info.pages}
            onClick={() => onClickPage(lastPage)}
          >
            <span aria-hidden="true">&raquo;</span>
          </CPaginationItem>
        </CPagination>
      </div>
    </div>
  )
}

ContactPagination.propTypes = {
  info: PropTypes.object,
  onClickPage: PropTypes.func,
  onClickPerPage: PropTypes.func,
}

export default ContactPagination
