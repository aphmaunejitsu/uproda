import React from 'react';
import { Link } from 'react-router-dom';
import { makeStyles } from '@material-ui/core/styles';
import Logo from '../../../images/favicon.jpg';

const useStyles = makeStyles((theme) => ({
  root: {
    display: 'flex',
    'align-items': 'center',
  },
  img: {
    height: 36,
    width: 36,
  },
  span: {
    'margin-left': '0.5rem',
    color: theme.palette.info,
  },
}));

function SiteLogo() {
  const classes = useStyles();
  return (
    <div className={classes.root}>
      <Link
        to="/"
        style={{ textDecoration: 'none' }}
      >
        <img
          className={classes.img}
          src={Logo}
          alt={process.env.MIX_RODA_IMG_DESCRIPTION}
        />
        <span className={classes.span}>{process.env.MIX_RODA_SUBTITLE}</span>
      </Link>
    </div>
  );
}

export default SiteLogo;
