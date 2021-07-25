import React from 'react';
import PropTypes from 'prop-types';
import { Close } from '@material-ui/icons';
import CancelIcon from '@material-ui/icons/Cancel';
import IconButton from '@material-ui/core/IconButton';

function UpDialog({ isOpen, handleClose }) {
  if (!isOpen) {
    return null;
  }

  const inputFile = React.useRef(null);
  const [file, setFile] = React.useState(false);

  const handleFileOnChange = (e) => {
    if (e.target.files.length > 0) {
      const f = e.target.files[0];
      setFile(URL.createObjectURL(f));
      console.log(f);
    }
  };

  const handleCancelImage = () => {
    setFile(false);
    inputFile.current.value = null;
  };

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
            <input
              type="file"
              id="roda-upload"
              accept={process.env.MIX_RODA_ACCEPT_FILES}
              onChange={(event) => handleFileOnChange(event)}
              ref={inputFile}
            />
            <p>
              画像を選択
              <br />
              [max filesize:
              {process.env.MIX_RODA_UPLOAD_MAXSIZE}
              MB]
            </p>
          </label>
        </div>
        <div className="roda-image">
          {
            file
              ? (
                <>
                  <img src={file} alt="Upload" />
                  <IconButton
                    onClick={() => handleCancelImage()}
                    aria-label="close"
                    color="inherit"
                    className="cancel-image-button"
                  >
                    <CancelIcon />
                  </IconButton>
                </>
              )
              : <span>No Image</span>
          }
        </div>
      </div>
    </div>
  );
}

UpDialog.propTypes = {
  isOpen: PropTypes.bool.isRequired,
  handleClose: PropTypes.func.isRequired,
};

export default UpDialog;
