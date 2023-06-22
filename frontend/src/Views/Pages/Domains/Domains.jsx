import React, { useCallback, useEffect, useState } from 'react'
import Default from '../../Layout/Default'
import { CButton, CCard, CCardBody, CCardHeader, CCol, CRow } from '@coreui/react'
import useAuth from '../../../OAuth/Provider/useAuth'
import api, { parseError } from '../../../Api'
import DataTable from './DataTable'
import Pagination from './Pagination'
import Filter from './Filter'
import RegisterModal from './RegisterModal'

function Domains() {
  const { getToken } = useAuth()

  const [loadedData, setLoadedData] = useState({ items: [], pagination: null })

  const [page, setPage] = useState(1)
  const [perPage, setPerPage] = useState(10)
  const [sort, setSort] = useState('')
  const [direction, setDirection] = useState('')
  const [filter, setFilter] = useState({ name: '' })

  const [visibleRegisterDomain, setVisibleRegisterDomain] = useState(false)

  const loadData = useCallback(() => {
    const params = new URLSearchParams({
      sort,
      direction,
      page: String(page),
      per_page: String(perPage),
    })

    for (const prop in filter) {
      if (filter[prop]) {
        params.append('filter[' + prop + ']', filter[prop])
      }
    }

    console.log(params.toString())

    getToken()
      .then((accessToken) =>
        api.get('/domains?' + params, {
          Accept: 'application/json',
          'Content-type': 'application/json',
          Authorization: accessToken,
        })
      )
      .then((result) => {
        setLoadedData(result)
      })
      .catch(async (error) => {
        console.log(await parseError(error))
      })
  }, [sort, direction, page, perPage, filter])

  useEffect(() => {
    setTimeout(loadData, 1000)
  }, [loadData])

  const changePerPage = (perPage) => {
    setPage(1)
    setPerPage(perPage)
  }

  const applySorting = (sort, direction) => {
    setPage(1)
    setSort(sort)
    setDirection(direction)
  }

  const submitFilter = (filter) => {
    setPage(1)
    setFilter(filter)
  }

  return (
    <Default>
      <>
        <CRow>
          <CCol xs>
            <CCard className="mb-4">
              <CCardHeader className="d-flex">
                <div className="fs-5">Manage Domains</div>
                <div className="ms-auto">
                  <CButton
                    size="sm"
                    type="submit"
                    color="success"
                    variant="outline"
                    onClick={() => setVisibleRegisterDomain(true)}
                  >
                    + Register Domain
                  </CButton>
                  <RegisterModal
                    visibleRegisterDomain={visibleRegisterDomain}
                    onCloseModal={(val) => setVisibleRegisterDomain(val)}
                  />
                </div>
              </CCardHeader>
              <CCardBody>
                <Filter onSubmitFilter={submitFilter} />
                <DataTable
                  domains={loadedData.items}
                  onApplySorting={applySorting}
                  sort={sort}
                  direction={direction}
                />
                <Pagination
                  info={loadedData.pagination}
                  onClickPage={(page) => {
                    setPage(page)
                  }}
                  onClickPerPage={changePerPage}
                />
              </CCardBody>
            </CCard>
          </CCol>
        </CRow>
      </>
    </Default>
  )
}

export default Domains
