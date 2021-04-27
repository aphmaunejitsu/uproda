import React from 'react';
import PropTypes from 'prop-types';
import { TwitterShareButton } from 'react-share';
import TwitterIcon from '@material-ui/icons/Twitter';

const ShareTwitterButton = ({ image }) => (
  <>
    <TwitterShareButton
      url={image.imageDetail}
      hashtags={['nejitsu']}
      style={{ padding: 12 }}
    >
      <TwitterIcon
        style={{ fontSize: 24 }}
      />
    </TwitterShareButton>
  </>
);

ShareTwitterButton.propTypes = {
  image: PropTypes.shape({
    imageDetail: PropTypes.string.isRequired,
  }).isRequired,
};

export default ShareTwitterButton;
