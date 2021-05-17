import React, { useCallback, useMemo } from 'react';
import PropTypes from 'prop-types';
import { Close } from '@material-ui/icons';
import IconButton from '@material-ui/core/IconButton';
import { useDropzone } from 'react-dropzone';

const baseStyle = {
  flex: 1,
  display: 'flex',
  flexDirection: 'column',
  alignItems: 'center',
  padding: '20px',
  borderWidth: 2,
  borderRadius: 2,
  borderColor: '#eeeeee',
  borderStyle: 'dashed',
  backgroundColor: '#fafafa',
  color: '#bdbdbd',
  outline: 'none',
  transition: 'border .24s ease-in-out',
  height: '64px',
};

const activeStyle = {
  borderColor: '#2196f3',
};

const acceptStyle = {
  borderColor: '#00e676',
};

const rejectStyle = {
  borderColor: '#ff1744',
};

function UpDialog({ isOpen, handleClose }) {
  if (!isOpen) {
    return null;
  }

  const onDrop = useCallback((acceptedFiles) => {
    // Do something with the files
    console.log(acceptedFiles);
  }, []);

  const {
    getRootProps,
    getInputProps,
    isDragActive,
    isDragAccept,
    isDragReject,
  } = useDropzone({
    onDrop,
    accept: 'image/bmp, image/jpeg, image/gif, image/png, image/webp',
  });

  const style = useMemo(() => ({
    ...baseStyle,
    ...(isDragActive ? activeStyle : {}),
    ...(isDragAccept ? acceptStyle : {}),
    ...(isDragReject ? rejectStyle : {}),
  }), [
    isDragActive,
    isDragReject,
    isDragAccept,
  ]);

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
        <div {...getRootProps({ style })} className="roda-box">
          <input {...getInputProps()} />
          {
            isDragActive
              ? <span>Drop the files here ...</span>
              : <span>Drag n drop some files here, or click to select files</span>
          }
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
