import React from 'react';
import Nav from './header/Nav';
import SiteLogo from './header/SiteLogo';

function HeaderBar() {
  return (
    <header className="headerBar">
      <SiteLogo />
      <Nav />
    </header>
  );
}

export default HeaderBar;
