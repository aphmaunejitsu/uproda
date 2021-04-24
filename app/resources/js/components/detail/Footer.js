import React, { useState } from 'react';
import PropTypes from 'prop-types';
import IconButton from '@material-ui/core/IconButton';
import FileCopyIcon from '@material-ui/icons/FileCopy';
import ShareIcon from '@material-ui/icons/Share';
import OpenInNewIcon from '@material-ui/icons/OpenInNew';
import DeleteForeverIcon from '@material-ui/icons/DeleteForever';
import TwitterIcon from '@material-ui/icons/Twitter';
import CopyToClipBoard from 'react-copy-to-clipboard';
import ToolTip from '@material-ui/core/Tooltip';

function Main({ image }) {
  if (!image) {
    return null;
  }

  const [openTip, setOpenTip] = useState(false);
  const handleClose = () => {
    setOpenTip(false);
  };

  const handleOpenTop = () => {
    setOpenTip(true);
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
        <IconButton>
          <DeleteForeverIcon />
        </IconButton>
      </footer>
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
