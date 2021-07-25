import React from 'react';
import PropTypes from 'prop-types';
import { Close } from '@material-ui/icons';
import IconButton from '@material-ui/core/IconButton';

function UpDialog({ isOpen, handleClose }) {
  if (!isOpen) {
    return null;
  }

  return (
    <div
      className="image-dialog"
      role="presentation"
    >
      <header>
        <IconButton
          onClick={() => handleClose(false)}
          aria-label="close"
          color="inherit"
          className="close-image-dialog-button"
        >
          <Close />
        </IconButton>
      </header>
      <div className="upload-image">
        <div className="upload-file">
          <label htmlFor="roda-upload">
            <input type="file" id="roda-upload" />
            ファイルを添付する
          </label>
        </div>
        <div className="roda-image" />
      </div>
    </div>
  );
}

UpDialog.propTypes = {
  isOpen: PropTypes.bool.isRequired,
  handleClose: PropTypes.func.isRequired,
};

export default UpDialog;
