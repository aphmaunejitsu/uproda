import React from 'react';
import { Link } from 'react-router-dom';

function Nav() {
  return (
    <nav>
      <ul>
        <li>
          <Link to="/top">Top</Link>
          <Link to="/about">About</Link>
        </li>
      </ul>
    </nav>
  );
}

export default Nav;
