import React from 'react';
import PropTypes from 'prop-types';
import View from './View';
import Footer from './Footer';
import DMMAd from '../common/DMMAd';

function Main({ image }) {
  if (!image) {
    return null;
  }

  return (
    <>
      <div className="image-detail">
        <View image={image} />
        <DMMAd
          dmmid={process.env.MIX_RODA_DMM_ID1}
          bottom
        />
        <Footer image={image} />
      </div>
    </>
  );
}

Main.propTypes = {
  image: PropTypes.shape({
    image: PropTypes.string.isRequired,
  }).isRequired,
};

export default Main;
