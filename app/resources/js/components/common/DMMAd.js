import React, { useEffect, useRef } from 'react';
import PropTypes from 'prop-types';
import { makeStyles } from '@material-ui/core/styles';

const useStyles = makeStyles({
  root: {
    display: 'flex',
    'justify-content': 'center',
    padding: '.5rem',
  },
  widget: {
    background: 'transparent',
  },
  bottom: {
    'margin-top': 'auto',
  },
});

function DMMAd({ dmmid, bottom }) {
  const classes = useStyles();
  const refDMMAd = useRef(null);

  if (!dmmid) {
    return null;
  }

  if (!process.env.MIX_RODA_USE_DMM_AFFI) {
    return null;
  }

  if (!process.env.MIX_RODA_DMM_URL) {
    return null;
  }

  useEffect(() => {
    const script = document.createElement('script');
    script.src = process.env.MIX_RODA_DMM_URL;
    script.className = 'dmm-widget-scripts';
    script.setAttribute('data-id', dmmid);
    script.async = true;
    script.type = 'text/javascript';
    refDMMAd.current.appendChild(script);
  }, [dmmid]);

  return (
    <div
      className={`${classes.root} ${bottom && classes.bottom}`}
      ref={refDMMAd}
    >
      <ins
        className={`dmm-widget-placement ${classes.widget}`}
        data-id={dmmid}
      />
    </div>
  );
}

DMMAd.propTypes = {
  dmmid: PropTypes.string,
  bottom: PropTypes.bool,
};

DMMAd.defaultProps = {
  dmmid: null,
  bottom: false,
};

export default DMMAd;
