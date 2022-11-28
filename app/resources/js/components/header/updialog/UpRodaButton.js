import React from 'react';
import PropTypes from 'prop-types';
import CloudUplaodIcon from '@material-ui/icons/CloudUpload';
import IconButton from '@material-ui/core/IconButton';
import LineProgressWithLabel from '../../common/LineProgressWithLabel';

function UpRodaButton({
  progress,
  progressBuffer,
  chunkPos,
  handleUpload,
}) {
  return (
    <div className="roda-upload-image">
      <LineProgressWithLabel
        progress={progress}
        progressBuffer={progressBuffer}
        chunkPos={chunkPos}
      />
      <div className="upload-button">
        {
          chunkPos
            ? (
              <IconButton
                variant="outlined"
                color="default"
                aria-label="up-image"
                disabled
              >
                <CloudUplaodIcon />
              </IconButton>
            ) : (
              <IconButton
                variant="outlined"
                color="default"
                aria-label="up-image"
                onClick={handleUpload}
              >
                <CloudUplaodIcon />
              </IconButton>
            )
        }
      </div>
    </div>
  );
}

UpRodaButton.propTypes = {
  chunkPos: PropTypes.number.isRequired,
  handleUpload: PropTypes.func.isRequired,
  progress: PropTypes.number.isRequired,
  progressBuffer: PropTypes.number.isRequired,
};

export default UpRodaButton;
