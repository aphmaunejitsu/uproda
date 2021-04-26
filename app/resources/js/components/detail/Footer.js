import React, { useRef, useState } from 'react';
import PropTypes from 'prop-types';
import IconButton from '@material-ui/core/IconButton';
import FileCopyIcon from '@material-ui/icons/FileCopy';
import ShareIcon from '@material-ui/icons/Share';
import OpenInNewIcon from '@material-ui/icons/OpenInNew';
import DeleteForeverIcon from '@material-ui/icons/DeleteForever';
import TwitterIcon from '@material-ui/icons/Twitter';
import CopyToClipBoard from 'react-copy-to-clipboard';
import ToolTip from '@material-ui/core/Tooltip';
import Dialog from '@material-ui/core/Dialog';
import {
  DialogActions,
  DialogContent,
  DialogContentText,
  DialogTitle,
  TextField,
} from '@material-ui/core';
import Button from '@material-ui/core/Button';
import axios from 'axios';

function Main({ image }) {
  if (!image) {
    return null;
  }

  let delkey;
  const [openTip, setOpenTip] = useState(false);
  const [openDelete, setOpenDelete] = useState(false);

  const handleClose = () => {
    setOpenTip(false);
  };

  const handleOpenTop = () => {
    setOpenTip(true);
  };

  const openDeleteDialog = () => {
    setOpenDelete(true);
  };

  const closeDeleteDialog = () => {
    setOpenDelete(false);
  };

  const handleDelete = () => {
    axios.delete(
      '/api/v1/image',
      { data: { basename: image.basename, delkey: delkey.value } },
    )
      .then(response => {
        window.location.reload();
      });
    // alert('削除実行');
  };

  return (
    <>
      <footer>
        <ToolTip
          arrow
          open={openTip}
          onClose={handleClose}
          placement="top"
          title="Copied!"
        >
          <CopyToClipBoard text={image.image}>
            <IconButton onClick={handleOpenTop}>
              <FileCopyIcon />
            </IconButton>
          </CopyToClipBoard>
        </ToolTip>
        <IconButton>
          <TwitterIcon />
        </IconButton>
        <IconButton>
          <ShareIcon />
        </IconButton>
        <a
          href={`/image/${image.basename}`}
          target="_blank"
          rel="noopener noreferrer"
        >
          <IconButton>
            <OpenInNewIcon />
          </IconButton>
        </a>
        <IconButton onClick={openDeleteDialog}>
          <DeleteForeverIcon />
        </IconButton>
      </footer>
      <Dialog
        open={openDelete}
        onClose={closeDeleteDialog}
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
            inputRef={node => {
              delkey = node;
            }}
          />
        </DialogContent>
        <DialogActions>
          <Button
            onClick={closeDeleteDialog}
            color="default"
            variant="contained"
          >
            Cancel
          </Button>
          <Button
            onClick={handleDelete}
            color="secondary"
            variant="contained"
          >
            Delete
          </Button>
        </DialogActions>
      </Dialog>
    </>
  );
}

Main.propTypes = {
  image: PropTypes.shape({
    basename: PropTypes.string.isRequired,
    detail: PropTypes.string.isRequired,
    image: PropTypes.string.isRequired,
    original: PropTypes.string.isRequired,
  }).isRequired,
};

export default Main;
