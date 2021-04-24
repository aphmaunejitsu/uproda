import React from 'react';
import PropTypes from 'prop-types';
import IconButton from '@material-ui/core/IconButton';
import FileCopyIcon from '@material-ui/icons/FileCopy';
import ShareIcon from '@material-ui/icons/Share';
import TrendingFlatIcon from '@material-ui/icons/TrendingFlat';
import DeleteForeverIcon from '@material-ui/icons/DeleteForever';
import TwitterIcon from '@material-ui/icons/Twitter';

function Main({ image }) {
  if (!image) {
    return null;
  }

  return (
    <>
      <footer>
        <IconButton>
          <FileCopyIcon />
        </IconButton>
        <IconButton>
          <TwitterIcon />
        </IconButton>
        <IconButton>
          <ShareIcon />
        </IconButton>
        <IconButton>
          <TrendingFlatIcon />
        </IconButton>
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
