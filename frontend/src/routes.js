import React from 'react'
const Dashboard = React.lazy(() => import('./Views/Pages/Dashboard'))
const Profile = React.lazy(() => import('./Views/Pages/Profile/Profile'))
const Contact = React.lazy(() => import('./Views/Pages/Contact'))
const Domains = React.lazy(() => import('./Views/Pages/Domains/Domains'))

const routes = [
  { path: '/', exact: true, name: 'Home' },
  { path: '/dashboard', name: 'Dashboard', element: Dashboard },
  { path: '/profile', name: 'Profile', element: Profile },
  { path: '/contact', name: 'Contact', element: Contact },
  { path: '/domains', name: 'Domains', element: Domains },
]

export default routes
