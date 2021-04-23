import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';

import HeaderBar from './HeaderBar';
import Top from './Top';
import About from './About';
import ImageDetail from './Detail';
import NotFoundPage from './NotFound';

function App() {
  return (
    <Router>
      <>
        <HeaderBar />
        <Switch>
          <Route path="/" exact component={Top} />
          <Route path="/about" component={About} />
          <Route path="/image/:hash" component={ImageDetail} />
          <Route component={NotFoundPage} />
        </Switch>
      </>
    </Router>
  );
}

if (document.getElementById('root')) {
  ReactDOM.render(<App />, document.getElementById('root'));
}
