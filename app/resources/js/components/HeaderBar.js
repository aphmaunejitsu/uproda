import React from 'react';
import Nav from './header/Nav';
import SiteLogo from './header/SiteLogo';
import UpButton from './header/UpButton';
import NavMobile from './header/NavMobile';

function HeaderBar() {
  return (
    <header className="headerBar">
      <NavMobile />
      <SiteLogo />
      <Nav />
      <UpButton />
    </header>
  );
}

export default HeaderBar;
