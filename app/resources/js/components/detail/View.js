import React from 'react';
import PropTypes from 'prop-types';

function View({ image }) {
  if (!image) {
    return null;
  }

  const handleNotClose = (e) => {
    e.stopPropagation();
  };

  return (
    <>
      <div
        className="view"
      >
        <div
          className="image"
        >
          <img
            src={image.image}
            alt={image.comment ? image.comment : image.basename}
            onClick={handleNotClose}
            aria-hidden="true"
          />
        </div>
        <div className="contents">
          {image.comment}
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
    comment: PropTypes.string,
    original: PropTypes.string.isRequired,
    size: PropTypes.number.isRequired,
  }).isRequired,
};

export default View;
