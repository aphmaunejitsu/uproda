import React from 'react';
import { Link } from 'react-router-dom';
import NotFoundImage from '../../images/404.jpg';

function NotFound() {
  return (
    <div>
      <h1>Not Found</h1>
      <Link to="/">
        <img src={NotFoundImage} alt="なんてこった、ここはね実だったのか" />
      </Link>
    </div>
  );
}

export default NotFound;
