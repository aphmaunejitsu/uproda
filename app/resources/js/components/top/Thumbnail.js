import React, { useEffect } from 'react';
// import { LazyLoadImage } from 'react-lazy-load-image-component';
import { Link } from 'react-router-dom';
import PropTypes from 'prop-types';
import noimage from '../../../images/noimage.gif';

function Thumbnail({ image }) {
  const [isLoaded, setIsLoaded] = React.useState(false);

  useEffect(() => {
    const img = new Image();
    img.src = image.thumbnail;
    img.onload = () => {
      setIsLoaded(true);
    };
  }, []);

  if (isLoaded) {
    return (
      <>
        <Link to={image.detail}>
          <img src={image.thumbnail} alt={image.comment} />
        </Link>
      </>
    );
  }

  return (
    <>
      <Link to={image.detail}>
        <img src={noimage} alt="noimage" />
      </Link>
    </>
  );
}

Thumbnail.propTypes = {
  image: PropTypes.shape({
    id: PropTypes.number.isRequired,
    basename: PropTypes.string.isRequired,
    detail: PropTypes.string.isRequired,
    image: PropTypes.string.isRequired,
    thumbnail: PropTypes.string.isRequired,
    comment: PropTypes.string.isRequired,
  }).isRequired,
};

export default Thumbnail;
