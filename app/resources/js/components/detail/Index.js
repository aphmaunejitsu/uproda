import React from 'react';
import { makeStyles } from '@material-ui/core/styles';
import { LazyLoadComponent } from 'react-lazy-load-image-component';
import 'react-lazy-load-image-component/src/effects/blur.css';
import PropTypes from 'prop-types';
import Main from './Main';

const useStyles = makeStyles({
  root: {
    height: 'calc(100vh - 68px)',
  },
});

function Index({ image }) {
  const classes = useStyles();
  if (!image) {
    return null;
  }

  return (
    <div
      className={classes.root}
      aria-hidden="true"
    >
      <LazyLoadComponent id={image.basename}>
        <Main image={image} />
      </LazyLoadComponent>
    </div>
  );
}

Index.propTypes = {
  image: PropTypes.shape({
    basename: PropTypes.string.isRequired,
    comment: PropTypes.string,
  }).isRequired,
};

export default Index;
