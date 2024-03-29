import React, { useState } from 'react';
import Dialog from '@material-ui/core/Dialog';
import PropTypes from 'prop-types';
import MuiAlert from '@material-ui/lab/Alert';
import {
  DialogActions,
  DialogContent,
  DialogContentText,
  DialogTitle,
  Snackbar,
  TextField,
} from '@material-ui/core';
import Button from '@material-ui/core/Button';
import axios from 'axios';

function Alert(props) {
  return (
    <MuiAlert
      elevation={6}
      variant="filled"
      // eslint-disable-next-line react/jsx-props-no-spreading
      {...props}
    />
  );
}

function ImageDeleteDialog({ image, isOpen, handleDialogClose }) {
  let delkey;
  const [isOpenSnack, setOpenSnack] = useState(false);
  const [isDeleting, setIsDeleting] = useState(false);

  const handleDelete = (e) => {
    e.stopPropagation();
    setIsDeleting(true);

    axios.delete(
      '/api/v1/image',
      { data: { basename: image.basename, delkey: delkey.value } },
    )
      .then(() => {
        window.location = '/';
      })
      .catch(() => {
        handleDialogClose(false);
        setOpenSnack(true);
        setIsDeleting(false);
        setOpenSnack(true);
      });
  };

  const closeSnackbar = (e) => {
    e.stopPropagation();
    setOpenSnack(false);
  };

  return (
    <>
      <Dialog
        open={isOpen}
        onClose={handleDialogClose}
        aria-labelledby="delete-image-title"
      >
        <DialogTitle id="delete-image-title">Delete Image</DialogTitle>
        <DialogContent>
          <DialogContentText>
            この画像を削除しますか？削除した場合は元に戻せません
          </DialogContentText>
          <TextField
            autoFocus
            margin="dense"
            id="delkey"
            label="削除キー"
            type="text"
            fullWidth
            required
            inputRef={(node) => {
              delkey = node;
            }}
          />
        </DialogContent>
        <DialogActions>
          <Button
            onClick={handleDialogClose}
            color="default"
            variant="contained"
            disabled={isDeleting && true}
          >
            Cancel
          </Button>
          <Button
            onClick={handleDelete}
            color="secondary"
            variant="contained"
            disabled={isDeleting && true}
          >
            Delete
          </Button>
        </DialogActions>
      </Dialog>
      <Snackbar
        anchorOrigin={{ vertical: 'center', horizontal: 'center' }}
        open={isOpenSnack}
        onClose={closeSnackbar}
        autoHideDuration={2000}
      >
        <Alert onClose={closeSnackbar} severity="warning">
          削除できませんでした
        </Alert>
      </Snackbar>
    </>
  );
}

ImageDeleteDialog.propTypes = {
  image: PropTypes.shape({
    basename: PropTypes.string.isRequired,
    detail: PropTypes.string.isRequired,
    image: PropTypes.string.isRequired,
    imageDetail: PropTypes.string.isRequired,
    original: PropTypes.string.isRequired,
  }).isRequired,
  isOpen: PropTypes.bool.isRequired,
  handleDialogClose: PropTypes.func.isRequired,
};

export default ImageDeleteDialog;
