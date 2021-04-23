import React from 'react';
import PropTypes from 'prop-types';
import useWindowDemensions from '../hook/useWindowDimensions';

function View({ image }) {
  if (!image) {
    return null;
  }

  const { height } = useWindowDemensions();
  const h = height - 100;

  return (
    <>
      <div className="view">
        <div
          className="image"
          style={{ height: `${h}px` }}
        >
          <img
            src={image.image}
            alt={image.comment ? image.comment : image.basename}
          />
        </div>
      </div>
    </>
  );
}

View.propTypes = {
  image: PropTypes.shape({
    basename: PropTypes.string.isRequired,
    detail: PropTypes.string.isRequired,
    image: PropTypes.string.isRequired,
    width: PropTypes.number.isRequired,
    height: PropTypes.number.isRequired,
    comment: PropTypes.string.isRequired,
    original: PropTypes.string.isRequired,
    size: PropTypes.number.isRequired,
  }).isRequired,
};

export default View;
