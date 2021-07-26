import React from 'react';
import PropTypes from 'prop-types';
import { Close } from '@material-ui/icons';
import IconButton from '@material-ui/core/IconButton';

function Header({ handleClose }) {
  return (
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
  );
}

Header.propTypes = {
  handleClose: PropTypes.func.isRequired,
};

export default Header;
