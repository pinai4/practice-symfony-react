import React from 'react'
import CIcon from '@coreui/icons-react'
import { cilHouse, cilContact, cilSpeedometer } from '@coreui/icons'
import { CNavItem } from '@coreui/react'

const NavItems = [
  {
    component: CNavItem,
    name: 'Dashboard',
    to: '/dashboard',
    icon: <CIcon icon={cilSpeedometer} customClassName="nav-icon" />,
  },
  {
    component: CNavItem,
    name: 'Domains',
    to: '/domains',
    icon: <CIcon icon={cilHouse} customClassName="nav-icon" />,
  },
  {
    component: CNavItem,
    name: 'Contacts',
    to: '/contact',
    icon: <CIcon icon={cilContact} customClassName="nav-icon" />,
  },
]

export default NavItems
