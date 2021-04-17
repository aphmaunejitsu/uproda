import React from 'react';
import { LazyLoadImage } from 'react-lazy-load-image-component';
import 'react-lazy-load-image-component/src/effects/blur.css';
import PropTypes from 'prop-types';
import useWindowDimensions from '../hook/useWindowDimensions';

function Thumbnail({ image, handleClick }) {
  const { width } = useWindowDimensions();

  // let w;
  // if (width >= 420 && width <= 1280) {
  //   w = (width - 8) / 4;
  // } else if (width < 420) {
  //   w = (width - 8) / 2;
  // } else {
  //   w = (1280 - 8) / 4;
  // }
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
