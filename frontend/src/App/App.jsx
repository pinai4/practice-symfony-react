import React, { Suspense } from 'react'
import PropTypes from 'prop-types'
import { BrowserRouter, Navigate, Route, Routes } from 'react-router-dom'
import { FeaturesProvider } from '../FeatureToggle'
import '../Scss/style.scss'
import Dashboard from '../Views/Pages/Dashboard'
import Login from '../Views/Pages/Login'
import Register from '../Views/Pages/Register'
import Page404 from '../Views/Pages/Page404'
import { AccessControl, OAuth } from '../OAuth'
import { AuthProvider } from '../OAuth/Provider'
import Contact from '../Views/Pages/Contact'
import ContactDetails from '../Views/Pages/Contact/ContactDetails'
import Domains from '../Views/Pages/Domains/Domains'
import Domain from '../Views/Pages/Domains/Domain/Domain'
import Profile from '../Views/Pages/Profile/Profile'

const loading = (
  <div className="pt-3 text-center">
    <div className="sk-spinner sk-spinner-pulse"></div>
  </div>
)

function App({ features }) {
  return (
    <FeaturesProvider features={features}>
      <AuthProvider
        authorizeUrl={process.env.REACT_APP_AUTH_URL + '/authorize'}
        tokenUrl={process.env.REACT_APP_AUTH_URL + '/token'}
        clientId="frontend"
        scope="common"
        redirectPath="/oauth"
      >
        <BrowserRouter>
          <Suspense fallback={loading}>
            <Routes>
              <Route path="/" element={<Navigate to="dashboard" replace />} />
              <Route
                path="/dashboard"
                name="Dashboard"
                element={
                  <AccessControl>
                    <Dashboard />
                  </AccessControl>
                }
              />
              <Route
                path="/profile"
                name="Profile"
                element={
                  <AccessControl>
                    <Profile />
                  </AccessControl>
                }
              />
              <Route
                path="/contact"
                name="Contacts"
                element={
                  <AccessControl>
                    <Contact />
                  </AccessControl>
                }
              />
              <Route
                path="/contact/:id"
                name="ContactDetails"
                element={
                  <AccessControl>
                    <ContactDetails />
                  </AccessControl>
                }
              />
              <Route
                path="/domains"
                name="Domains"
                element={
                  <AccessControl>
                    <Domains />
                  </AccessControl>
                }
              />
              <Route
                path="/domains/:id"
                name="DomainDetails"
                element={
                  <AccessControl>
                    <Domain />
                  </AccessControl>
                }
              />
              <Route exact path="/login" name="Login Page" element={<Login />} />
              <Route exact path="/register" name="Register Page" element={<Register />} />
              <Route exact path="/oauth" element={<OAuth />} />
              <Route path="*" name="Page Not Found" element={<Page404 />} />
            </Routes>
          </Suspense>
        </BrowserRouter>
      </AuthProvider>
    </FeaturesProvider>
  )
}

App.propTypes = {
  features: PropTypes.array.isRequired,
}

export default App
