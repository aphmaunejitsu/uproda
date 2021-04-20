import React from 'react';
import Nav from './header/Nav';
import SiteLogo from './header/SiteLogo';
import UpDialog from './header/UpDialog';
import NavMobile from './header/NavMobile';

function HeaderBar() {
  return (
    <header className="headerBar">
      <NavMobile />
      <SiteLogo />
      <Nav />
      <UpDialog />
    </header>
  );
}

export default HeaderBar;
