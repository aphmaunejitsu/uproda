import React from 'react';
import styled from '@emotion/styled';
import NavBar from './header/NavBar';
import SiteLogo from './header/SiteLogo';

const Header = styled.article`
`;

function HeaderBar() {
  return (
    <header className="Top">
      <SiteLogo />
      <NavBar />
    </header>
  );
}

export default Header;
