import React from 'react';
import { LazyLoadImage } from 'react-lazy-load-image-component';
import 'react-lazy-load-image-component/src/effects/blur.css';
import PropTypes from 'prop-types';

function Thumbnail({ image, handleClick }) {
  return (
    <>
      <LazyLoadImage
        alt={image.comment}
        effect="blur"
        src={image.thumbnail}
        onClick={() => handleClick(image)}
      />
    </>
  );
}

Thumbnail.propTypes = {
  image: PropTypes.shape({
    id: PropTypes.number.isRequired,
    basename: PropTypes.string.isRequired,
    detail: PropTypes.string.isRequired,
    image: PropTypes.string.isRequired,
    thumbnail: PropTypes.string.isRequired,
    comment: PropTypes.string.isRequired,
  }).isRequired,
  handleClick: PropTypes.func.isRequired,
};

export default Thumbnail;
