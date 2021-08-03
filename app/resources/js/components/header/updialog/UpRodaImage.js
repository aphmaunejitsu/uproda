import React from 'react';
import PropTypes from 'prop-types';
import CancelIcon from '@material-ui/icons/Cancel';
import IconButton from '@material-ui/core/IconButton';

function UpRodaImage({ image, chunkPos, handleCancelImage }) {
  return (
    <div className="roda-image">
      {
        image
          ? (
            <>
              <img src={image} alt="Upload" />
              {
                !chunkPos
                  ? (
                    <IconButton
                      onClick={() => handleCancelImage()}
                      aria-label="close"
                      color="inherit"
                      size="small"
                      className="cancel-image-button"
                    >
                      <CancelIcon />
                    </IconButton>
                  ) : null
              }
            </>
          )
          : <span>No Image</span>
      }
    </div>
  );
}

UpRodaImage.propTypes = {
  image: PropTypes.string.isRequired,
  chunkPos: PropTypes.number.isRequired,
  handleCancelImage: PropTypes.func.isRequired,
};

export default UpRodaImage;
