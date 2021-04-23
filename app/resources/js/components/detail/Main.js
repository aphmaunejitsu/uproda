import React from 'react';
import { LazyLoadComponent } from 'react-lazy-load-image-component';
import 'react-lazy-load-image-component/src/effects/blur.css';
import PropTypes from 'prop-types';
import ImageView from './View';

function Main({ image }) {
  if (!image) {
    return null;
  }

  return (
    <>
      <div className="image-detail">
        <LazyLoadComponent effect="blur">
          <ImageView image={image} />
        </LazyLoadComponent>
        <div className="footer">
          footer
        </div>
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
