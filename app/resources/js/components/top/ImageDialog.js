import React from 'react';
import { LazyLoadComponent } from 'react-lazy-load-image-component';
import 'react-lazy-load-image-component/src/effects/blur.css';
import PropTypes from 'prop-types';
import { Close } from '@material-ui/icons';
import IconButton from '@material-ui/core/IconButton';
import ImageDetail from '../common/ImageDetail';

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
        <IconButton
          onClick={() => setIsOpen(false)}
          aria-label="close"
          color="inherit"
          className="close-image-dialog-button"
        >
          <Close />
        </IconButton>
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
