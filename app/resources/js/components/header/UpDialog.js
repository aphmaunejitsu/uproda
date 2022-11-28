import React from 'react';
import PropTypes from 'prop-types';
import Header from './updialog/Header';
import Main from './updialog/Main';
import DMMAd from '../common/DMMAd';

function UpDialog({ isOpen, handleClose }) {
  if (!isOpen) {
    return null;
  }

  return (
    <div
      className="uproda-dialog"
      role="presentation"
    >
      <Header handleClose={handleClose} />
      <Main />
      <DMMAd dmmid={process.env.MIX_RODA_DMM_ID2} />
    </div>
  );
}

UpDialog.propTypes = {
  isOpen: PropTypes.bool.isRequired,
  handleClose: PropTypes.func.isRequired,
};

export default UpDialog;
