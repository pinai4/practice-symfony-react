import React, { useCallback, useEffect, useState } from 'react'
import useAuth from '../../../OAuth/Provider/useAuth'
import api, { parseError } from '../../../Api'
import Default from '../../Layout/Default'
import { CCard, CCardBody, CCardHeader, CCol, CRow } from '@coreui/react'

function Profile() {
  const { getToken } = useAuth()
  const [profile, setProfile] = useState(null)

  const loadData = useCallback(() => {
    getToken()
      .then((accessToken) =>
        api.get('/profile', {
          Accept: 'application/json',
          'Content-type': 'application/json',
          Authorization: accessToken,
        })
      )
      .then((result) => {
        setProfile(result)
      })
      .catch(async (error) => {
        console.log(await parseError(error))
      })
  }, [])

  useEffect(() => {
    setTimeout(loadData, 1000)
  }, [loadData])

  return (
    <Default>
      <>
        <CRow>
          <CCol xs>
            <CCard className="mb-4">
              <CCardHeader className="fs-5">Profile</CCardHeader>
              <CCardBody>
                {profile ? (
                  <>
                    <CRow className="mb-3">
                      <CCol sm={1} className="fw-bold">
                        Name:
                      </CCol>
                      <CCol sm={11}>{profile.name}</CCol>
                    </CRow>
                  </>
                ) : null}
              </CCardBody>
            </CCard>
          </CCol>
        </CRow>
      </>
    </Default>
  )
}

export default Profile
