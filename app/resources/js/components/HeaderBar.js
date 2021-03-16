import React from 'react';
import Nav from './header/Nav';
import SiteLogo from './header/SiteLogo';
import UpDialog from './header/UpDialog';

function HeaderBar() {
  return (
    <header className="headerBar">
      <SiteLogo />
      <Nav />
      <UpDialog />
    </header>
  );
}

export default HeaderBar;
