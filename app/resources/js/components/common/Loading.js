import React from 'react';
import CircularProgress from '@material-ui/core/CircularProgress';
import { makeStyles } from '@material-ui/core/styles';

const useStyles = makeStyles({
  root: {
    padding: '1rem',
    width: '100%',
    display: 'flex',
    justifyContent: 'center'
  },
});

function Loading() {
  const styles = useStyles();

  return (
    <div className={styles.root}>
      <CircularProgress />
    </div>
  );
}

export default Loading;
