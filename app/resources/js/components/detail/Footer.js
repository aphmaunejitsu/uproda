import React, { useState } from 'react';
import PropTypes from 'prop-types';
import IconButton from '@material-ui/core/IconButton';
import FileCopyIcon from '@material-ui/icons/FileCopy';
import OpenInNewIcon from '@material-ui/icons/OpenInNew';
import DeleteForeverIcon from '@material-ui/icons/DeleteForever';
import CloudDownloadIcon from '@material-ui/icons/CloudDownload';
import { Link } from 'react-router-dom';
import CopyToClipBoard from 'react-copy-to-clipboard';
import ToolTip from '@material-ui/core/Tooltip';
import ImageDeleteDialog from './ImageDeleteDialog';
import ShareButton from './ShareButton';
import ShareTwitterButton from './ShareTwitterButton';

function Main({ image }) {
  if (!image) {
    return null;
  }

  const [openTip, setOpenTip] = useState(false);
  const [openDelete, setOpenDelete] = useState(false);

  const handleClose = (e) => {
    e.stopPropagation();
    setOpenTip(false);
  };

  const handleOpenTop = (e) => {
    e.stopPropagation();
    setOpenTip(true);
  };

  const openDeleteDialog = (e) => {
    e.stopPropagation();
    setOpenDelete(true);
  };

  const closeDeleteDialog = (e) => {
    // e.stopPropagation();
    setOpenDelete(false);
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
        <ShareTwitterButton image={image} />
        <ShareButton image={image} />
        <a href={`${image.image}`} download>
          <IconButton>
            <CloudDownloadIcon />
          </IconButton>
        </a>
        <Link
          to={`${image.detail}`}
          rel="noopener noreferrer"
        >
          <IconButton>
            <OpenInNewIcon />
          </IconButton>
        </Link>
        <IconButton onClick={openDeleteDialog}>
          <DeleteForeverIcon />
        </IconButton>
      </footer>
      <ImageDeleteDialog
        image={image}
        isOpen={openDelete}
        handleDialogClose={closeDeleteDialog}
      />
    </>
  );
}

Main.propTypes = {
  image: PropTypes.shape({
    basename: PropTypes.string.isRequired,
    detail: PropTypes.string.isRequired,
    image: PropTypes.string.isRequired,
    imageDetail: PropTypes.string.isRequired,
    original: PropTypes.string.isRequired,
  }).isRequired,
};

export default Main;
