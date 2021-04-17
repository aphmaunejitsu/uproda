import React, { useEffect, useState } from 'react';
import { LazyLoadImage } from 'react-lazy-load-image-component';
import 'react-lazy-load-image-component/src/effects/blur.css';
import PropTypes from 'prop-types';

function ImageDetail({ image }) {
  const [isLoadedImage, setIsLoadedImage] = useState(false);

  if (!image) {
    return null;
  }

  useEffect(() => {
    const img = new Image();
    img.src = image.image;

    img.onload = () => {
      setIsLoadedImage(true);
    };
  }, []);

  if (!isLoadedImage) {
    return null;
  }

  return (
    <>
      <div className="image-detail">
        <div className="view">
          <LazyLoadImage
            alt={image.comment}
            effect="blur"
            src={image.image}
          />
        </div>
        <div className="contents">
          aaaaaaa
        </div>
      </div>
    </>
  );
}

ImageDetail.propTypes = {
  image: PropTypes.shape({
    basename: PropTypes.string.isRequired,
    detail: PropTypes.string.isRequired,
    image: PropTypes.string.isRequired,
    width: PropTypes.number.isRequired,
    height: PropTypes.number.isRequired,
    comment: PropTypes.string.isRequired,
  }).isRequired,
};

export default ImageDetail;
