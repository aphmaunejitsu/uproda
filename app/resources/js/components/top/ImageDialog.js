import React from 'react';
import 'react-lazy-load-image-component/src/effects/blur.css';
import PropTypes from 'prop-types';
import ImageDetail from '../common/ImageDetail';
// import useWindowDimensions from '../hook/useWindowDimensions';

function ImageDialog({ isOpen, setIsOpen, image }) {
  if (!isOpen) {
    return null;
  }

  if (!image) {
    return null;
  }

  //   const { width } = useWindowDimensions();
  // let w;
  // if (width >= 420 && width <= 1280) {
  //   w = (width - 8);
  // } else if (width < 420) {
  //   w = (width - 8);
  // } else {
  //   w = (1280 - 8);
  // }

  return (
    <div
      className="image-dialog"
      onClick={() => { setIsOpen(false); }}
      role="presentation"
    >
      <ImageDetail image={image} />
    </div>
  );
}

ImageDialog.propTypes = {
  isOpen: PropTypes.bool.isRequired,
  setIsOpen: PropTypes.func.isRequired,
  image: PropTypes.shape({}).isRequired,
};

export default ImageDialog;
