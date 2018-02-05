import React from 'react'
import { StaticRouter } from 'react-router-dom'
import App from './App'

var context = {}

export default (props) => (
  <StaticRouter location={props.location} context={context}>
    <App/>
  </StaticRouter>
)
