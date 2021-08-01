import React from 'react';
import PropTypes from 'prop-types';
import CancelIcon from '@material-ui/icons/Cancel';
import IconButton from '@material-ui/core/IconButton';

function UpRodaImage({ image, handleCancelImage }) {
  return (
    <div className="roda-image">
      {
        image
          ? (
            <>
              <img src={image} alt="Upload" />
              <IconButton
                onClick={() => handleCancelImage()}
                aria-label="close"
                color="inherit"
                size="small"
                className="cancel-image-button"
              >
                <CancelIcon />
              </IconButton>
            </>
          )
          : <span>No Image</span>
      }
    </div>
  );
}

UpRodaImage.propTypes = {
  image: PropTypes.shape().isRequired,
  handleCancelImage: PropTypes.func.isRequired,
};

export default UpRodaImage;
