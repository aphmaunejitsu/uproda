import React from 'react';
import { useParams } from 'react-router-dom';
import { LazyLoadComponent } from 'react-lazy-load-image-component';
import 'react-lazy-load-image-component/src/effects/blur.css';
import PropTypes from 'prop-types';
import ImageDetail from './detail/Main';

function Detail() {
  const { hash } = useParams();
  return (
    <>
      <LazyLoadComponent id={image.basename}>
        <ImageDetail image={image} />
      </LazyLoadComponent>
    </>
  );
}

Detail.propTypes = {
  image: PropTypes.shape({
    basename: PropTypes.string.isRequired,
  }).isRequired,
};

export default Detail;
