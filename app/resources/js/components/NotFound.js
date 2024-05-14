import React from 'react';
import { Link } from 'react-router-dom';
import { makeStyles } from '@material-ui/core/styles';
import NotFoundImage from '../../images/404.jpg';
import DMMAd from './common/DMMAd';
import { Helmet } from 'react-helmet';

const useStyles = makeStyles({
  root: {
    display: 'flex',
    flexFlow: 'column',
    alignItems: 'center',
  },
  image: {
    width: '100%',
    maxWidth: '648px',
  },
});

function NotFound() {
  const styles = useStyles();
  return (
    <>
      <Helmet
        title={`${process.env.MIX_RODA_NAME} | 404 Not Found`}
      />
      <div className={styles.root}>
        <h1>Not Found</h1>
        <Link to="/">
          <img
            src={NotFoundImage}
            alt={process.env.MIX_RODA_404_IMG_DESCRIPTION}
            className={styles.image}
          />
        </Link>
        <DMMAd
          dmmid={process.env.MIX_RODA_DMM_ID2}
          bottom
        />
      </div>
    </>
  );
}

export default NotFound;
