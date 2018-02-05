import React from 'react'
import { Link, Route, Switch } from 'react-router-dom'

const Home = () => (
  <h1>Home Page</h1>
)

const About = () => (
  <h1>About Page</h1>
)

const NotFound = () => {
  if (typeof httpServer !== 'undefined' && typeof httpServer.res !== 'undefined') {
    httpServer.res.status(404)
  }

  return (
    <h1>Not Found</h1>
  )
}

const App = () => (
  <div>
    <ul>
      <li><Link to='/react-router/'>Home</Link></li>
      <li><Link to='/react-router/about'>About</Link></li>
    </ul>
    <Switch>
      <Route path="/react-router/" exact component={Home} />
      <Route path="/react-router/about" component={About} />
      <Route component={NotFound} />
    </Switch>
  </div>
)

export default App
