import React from 'react';
import { LazyLoadImage } from 'react-lazy-load-image-component';
import 'react-lazy-load-image-component/src/effects/blur.css';
import PropTypes from 'prop-types';
import useWindowDimensions from '../hook/useWindowDimensions';

function ImageDialog({ isOpen, setIsOpen, image }) {
  if (!isOpen) {
    return null;
  }

  if (!image) {
    return null;
  }

  const { width } = useWindowDimensions();
  let w;
  if (width >= 420 && width <= 1280) {
    w = (width - 8);
  } else if (width < 420) {
    w = (width - 8);
  } else {
    w = (1280 - 8);
  }

  return (
    <>
      <LazyLoadImage
        alt={image.comment}
        effect="blur"
        src={image.image}
        width={w}
        onClick={() => { setIsOpen(false);}}
      />
    </>
  );
}

ImageDialog.propTypes = {
  isOpen: PropTypes.bool.isRequired,
  setIsOpen: PropTypes.func.isRequired,
  image: PropTypes.shape({
    basename: PropTypes.string.isRequired,
    detail: PropTypes.string.isRequired,
    image: PropTypes.string.isRequired,
    comment: PropTypes.string.isRequired,
  }).isRequired,
};

export default ImageDialog;
