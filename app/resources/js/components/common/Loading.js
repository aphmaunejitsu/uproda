import React from 'react';
import CircularProgress from '@material-ui/core/CircularProgress';
import { makeStyles } from '@material-ui/core/styles';

const useStyles = makeStyles({
  root: {
    padding: '1rem',
    width: '100%',
  },
  circular: {
    position: 'fixed',
    top: '50%',
    left: 'calc(50% - 32px)',
  },
});

function Loading() {
  const styles = useStyles();

  return (
    <div className={styles.root}>
      <CircularProgress className={styles.circular} />
    </div>
  );
}

export default Loading;
