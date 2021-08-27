import React, { useEffect } from 'react';
import PropTypes from 'prop-types';
import { makeStyles } from '@material-ui/core/styles';

const useStyles = makeStyles((theme) => ({
  uploadFileLabel: {
    width: '100%',
    padding: '0.5em',
    borderColor: theme.palette.primary.main,
    borderStyle: 'dotted',
    borderWidth: '1px',
    display: 'flex',
    justifyContent: 'center',
    cursor: 'pointer',
  },
  dragging: {
    backgroundColor: theme.palette.primary.light,
    borderStyle: 'solid',
    color: theme.palette.primary.contrastText,
    cursor: 'copy',
  },
  intputFile: {
    display: 'none',
  },
}));

function RodaUploadInput({
  handleSetFile,
  handleSetImage,
  handleSetMimeType,
  handleSetFileSize,
  handleSetSnackMessage,
  handleSetSnackOpen,
}) {
  const styles = useStyles();
  const inputFile = React.useRef(null);
  const maxMB = process.env.MIX_RODA_UPLOAD_MAXSIZE / 1024;
  const maxByte = process.env.MIX_RODA_UPLOAD_MAXSIZE * 1024;

  const [dragging, setDragging] = React.useState(false);
  const [dragCounter, setDragCounter] = React.useState(0);
  const dropDiv = React.createRef();

  const handleDrag = (e) => {
    e.preventDefault();
    e.stopPropagation();
    setDragging(true);
  };

  const handleDragIn = (e) => {
    e.preventDefault();
    e.stopPropagation();
    setDragCounter(dragCounter + 1);
    if (e.dataTransfer.items && e.dataTransfer.items.length > 0) {
      setDragging(true);
    }
  };

  const handleDragOut = (e) => {
    e.preventDefault();
    e.stopPropagation();
    setDragCounter(dragCounter - 1);
    if (dragCounter === 0) {
      setDragging(false);
    }
  };

  const dropFiles = (files) => {
    console.log(files);
    if (files.length > 0) {
      const f = files[0];
      if (f.size > maxByte) {
        handleSetSnackMessage(`フィルサイズは${maxMB.toFixed(1)}MBまでです`);
        handleSetSnackOpen(true);
      } else {
        handleSetImage(f);
        handleSetMimeType(f.type);
        handleSetFileSize(f.size);
        handleSetFile(URL.createObjectURL(f));
      }

      if (files.length > 1) {
        handleSetSnackMessage('1ファイルのみアップロードできます');
        handleSetSnackOpen(true);
      }
    }
  };

  const handleDrop = (e) => {
    e.preventDefault();
    e.stopPropagation();
    setDragging(false);
    if (e.dataTransfer.items && e.dataTransfer.items.length > 0) {
      dropFiles(e.dataTransfer.files);
      e.dataTransfer.clearData();
      setDragCounter(0);
    }
  };

  useEffect(() => {
    dropDiv.current.addEventListener('dragenter', handleDragIn);
    dropDiv.current.addEventListener('dragleave', handleDragOut);
    dropDiv.current.addEventListener('dragover', handleDrag);
    dropDiv.current.addEventListener('drop', handleDrop);
  }, [setDragging]);

  const handleFileOnChange = (e) => {
    if (e.target.files.length > 0) {
      const f = e.target.files[0];
      if (f.size > maxByte) {
        handleSetSnackMessage(`フィルサイズは${maxMB.toFixed(1)}MBまでです`);
        handleSetSnackOpen(true);
      } else {
        handleSetImage(f);
        handleSetMimeType(f.type);
        handleSetFileSize(f.size);
        handleSetFile(URL.createObjectURL(f));
      }
    }
  };

  return (
    <div className="upload-file">
      <label
        ref={dropDiv}
        htmlFor="roda-upload"
        className={`${styles.uploadFileLabel} ${dragging && styles.dragging}`}
      >
        <input
          type="file"
          id="roda-upload"
          accept={process.env.MIX_RODA_ACCEPT_FILES}
          onChange={(event) => handleFileOnChange(event)}
          ref={inputFile}
          className={styles.intputFile}
        />
        <span>
          画像を選択
          <br />
          max filesize:
          {maxMB.toFixed(1)}
          MB
        </span>
      </label>
    </div>
  );
}

RodaUploadInput.propTypes = {
  handleSetFile: PropTypes.func.isRequired,
  handleSetImage: PropTypes.func.isRequired,
  handleSetMimeType: PropTypes.func.isRequired,
  handleSetFileSize: PropTypes.func.isRequired,
  handleSetSnackMessage: PropTypes.func.isRequired,
  handleSetSnackOpen: PropTypes.func.isRequired,
};

export default RodaUploadInput;
