import React from 'react';

import NavBar from './header/NavBar';
import SiteLogo from './header/SiteLogo';

function Header() {
  return (
    <header className="Top">
      <SiteLogo />
      <NavBar />
    </header>
  );
}

export default Header;
