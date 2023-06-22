import React, { useCallback, useEffect, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import {
  CDropdown,
  CDropdownDivider,
  CDropdownHeader,
  CDropdownItem,
  CDropdownMenu,
  CDropdownToggle,
} from '@coreui/react'
import { cilAccountLogout, cilLockLocked, cilUser } from '@coreui/icons'
import CIcon from '@coreui/icons-react'

import useAuth from '../../../../OAuth/Provider/useAuth'
import api, { parseError } from '../../../../Api'

const HeaderDropdown = () => {
  const { logout } = useAuth()
  const navigate = useNavigate()

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
    loadData()
  }, [loadData])

  const logoutProcess = (e) => {
    e.preventDefault()
    logout()
    navigate('/', { replace: true })
  }

  return (
    <CDropdown variant="nav-item">
      <CDropdownToggle placement="bottom-end" className="py-0" caret={false}>
        {profile ? (
          <>
            <CIcon icon={cilLockLocked} className="me-2" />
            <span className="fw-bold text-decoration-underline">{profile.name}</span>
          </>
        ) : null}
      </CDropdownToggle>
      <CDropdownMenu className="pt-0" placement="bottom-end">
        <CDropdownHeader className="bg-light fw-semibold py-2">Settings</CDropdownHeader>
        <CDropdownItem href="/profile">
          <CIcon icon={cilUser} className="me-2" />
          Profile
        </CDropdownItem>
        <CDropdownDivider />
        <CDropdownItem href="#" onClick={logoutProcess}>
          <CIcon icon={cilAccountLogout} className="me-2" />
          Logout
        </CDropdownItem>
      </CDropdownMenu>
    </CDropdown>
  )
}

export default HeaderDropdown
