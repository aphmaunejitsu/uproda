import React from 'react';
import IconButton from '@material-ui/core/IconButton';
import CloudUplaodIcon from '@material-ui/icons/CloudUpload';
import UpDialog from './UpDialog';

function UpButton() {
  const [open, setOpen] = React.useState(false);
  const handleClickOpen = () => {
    setOpen(true);
  };

  const handleClose = () => {
    setOpen(false);
  };

  return (
    <>
      <div className="upload-button">
        <IconButton
          variant="outlined"
          color="default"
          aria-label="up-image"
          onClick={handleClickOpen}
        >
          <CloudUplaodIcon />
        </IconButton>
      </div>
      <UpDialog isOpen={open} handleClose={handleClose} />
    </>
  );
}

export default UpButton;
