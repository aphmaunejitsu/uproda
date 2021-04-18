import React from 'react';
import { LazyLoadImage } from 'react-lazy-load-image-component';
import 'react-lazy-load-image-component/src/effects/blur.css';
import PropTypes from 'prop-types';

function ImageDetail({ image }) {
  if (!image) {
    return null;
  }

  let formatBytes = '0 Bytes';
  if (image.size > 0) {
    const k = 1024;
    const bytes = Number(image.size);
    const units = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'ZB', 'YB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    const num = parseFloat((bytes / (k ** i)).toFixed(2));
    formatBytes = `${num}  ${units[i]}`;
  }

  return (
    <>
      <div className="image-detail">
        <div className="view">
          <LazyLoadImage
            alt={image.comment}
            effect="blur"
            src={image.image}
            className="image"
          />
          <div className="content">
            <div className="title">
              {image.comment ? image.comment : image.original}
            </div>
            <div className="size">
              {formatBytes}
            </div>
          </div>
          <div className="comments">
            coment
          </div>
        </div>
        <div className="footer">
          footer
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
    original: PropTypes.string.isRequired,
    size: PropTypes.number.isRequired,
  }).isRequired,
};

export default ImageDetail;
