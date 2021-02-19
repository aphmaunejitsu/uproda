import React from 'react';
import ReactDOM from 'react-dom';

import HeaderBar from './HeaderBar';

function App() {
  return (
    <HeaderBar />
  );
}

if (document.getElementById('root')) {
  ReactDOM.render(<App />, document.getElementById('root'));
}
