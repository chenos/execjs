import React from 'react'
import { Link, Route, Switch } from 'react-router-dom'
import MetaTags from 'react-meta-tags';

const Home = () => (
  <div className="page">
    <MetaTags>
      <title>React Meta Tags | Home</title>
    </MetaTags>
    <h1>Home Page</h1>
  </div>
)

const About = () => (
  <div className="page">
    <MetaTags>
      <title>React Meta Tags | About Us</title>
    </MetaTags>
    <h1>About Page</h1>
  </div>
)

const NotFound = () => {
  if (typeof httpServer !== 'undefined' && typeof httpServer.res !== 'undefined') {
    httpServer.res.status(404)
  }

  return (
    <div className="page">
      <MetaTags>
        <title>React Meta Tags | Not Found</title>
      </MetaTags>
      <h1>Not Found</h1>
    </div>
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
