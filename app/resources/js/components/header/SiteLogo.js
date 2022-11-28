import React from 'react';
import { Link } from 'react-router-dom';
import Logo from '../../../images/favicon.jpg';

function SiteLogo() {
  return (
    <div className="SiteLogo">
      <Link to="/">
        <img src={Logo} alt="ネ実うpろだ 我々のコリブリ いやらしい・・・" />
      </Link>
    </div>
  );
}

export default SiteLogo;
