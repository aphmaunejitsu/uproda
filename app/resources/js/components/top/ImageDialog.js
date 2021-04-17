import React from 'react';
import { LazyLoadComponent } from 'react-lazy-load-image-component';
import 'react-lazy-load-image-component/src/effects/blur.css';
import PropTypes from 'prop-types';
import ImageDetail from '../common/ImageDetail';
// import useWindowDimensions from '../hook/useWindowDimensions';
import CloudUplaodIcon from '@material-ui/icons/Close';
import { Close } from '@material-ui/icons';

function ImageDialog({ isOpen, setIsOpen, image }) {
  if (!isOpen) {
    return null;
  }

  if (!image) {
    return null;
  }

  return (
    <div
      className="image-dialog"
      role="presentation"
    >
      <div className="header">
        <Close />
      </div>
      <LazyLoadComponent id={image.basename}>
        <ImageDetail image={image} />
      </LazyLoadComponent>
    </div>
  );
}

ImageDialog.propTypes = {
  isOpen: PropTypes.bool.isRequired,
  setIsOpen: PropTypes.func.isRequired,
  image: PropTypes.shape({
    basename: PropTypes.string.isRequired,
  }).isRequired,
};

export default ImageDialog;
