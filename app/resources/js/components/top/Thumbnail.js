import React from 'react';
import { Link } from 'react-router-dom';
import PropTypes from 'prop-types';

function Thumbnail({ image }) {
  return (
    <div className="Thumbnail">
      <Link to={image.detail}>
        <img
          src={image.thumbnail}
          alt={image.comment}
          width="100%"
        />
      </Link>
    </div>
  );
}

Thumbnail.propTypes = {
  image: PropTypes.shape({
    detail: PropTypes.string.isRequired,
    image: PropTypes.string.isRequired,
    thumbnail: PropTypes.string.isRequired,
    comment: PropTypes.string.isRequired,
  }).isRequired,
};

export default Thumbnail;
