import React from 'react';
import IconButton from '@material-ui/core/IconButton';
import PropTypes from 'prop-types';
import ShareIcon from '@material-ui/icons/Share';

const WebApiShare = ({ image }) => {
  const handleClick = (e) => {
    e.preventDefault();
    navigator.share({
      title: process.env.MIX_RODA_NAME,
      url: image.imageDetail,
    }).then(() => {
    });
  };
  return (
    <>
      <IconButton onClick={handleClick}>
        <ShareIcon />
      </IconButton>
    </>
  );
};

function ShareButton({ image }) {
  return (
    <>
      {navigator.share
        ? <WebApiShare image={image} />
        : null }
    </>
  );
}

ShareButton.propTypes = {
  image: PropTypes.shape({
    basename: PropTypes.string.isRequired,
    detail: PropTypes.string.isRequired,
    image: PropTypes.string.isRequired,
    imageDetail: PropTypes.string.isRequired,
    original: PropTypes.string.isRequired,
  }).isRequired,
};

WebApiShare.propTypes = {
  image: PropTypes.shape({
    imageDetail: PropTypes.string.isRequired,
  }).isRequired,
};

export default ShareButton;
