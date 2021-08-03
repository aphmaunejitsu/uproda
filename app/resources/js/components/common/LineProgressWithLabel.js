import React from 'react';
import PropTypes from 'prop-types';
import { LinearProgress } from '@material-ui/core';
import { makeStyles } from '@material-ui/core/styles';
import Box from '@material-ui/core/Box';
import Typography from '@material-ui/core/Typography';

const useStyles = makeStyles({
  progress: {
    width: '100%',
    display: 'flex',
    'align-items': 'center',
  },
});

function LineProgressWithLabel({
  progress,
  progressBuffer,
  chunkPos,
}) {
  const classes = useStyles();
  return (
    <div className={classes.progress}>
      <Box width="100%" mr={1}>
        { chunkPos
          ? (
            <LinearProgress
              variant="buffer"
              value={progress}
              valueBuffer={progressBuffer}
            />
          ) : null }
      </Box>
      <Box minWidth={35}>
        <Typography variant="body2" color="textSecondary">
          {`${progress}%`}
        </Typography>
      </Box>
    </div>
  );
}

LineProgressWithLabel.propTypes = {
  chunkPos: PropTypes.number.isRequired,
  progress: PropTypes.number.isRequired,
  progressBuffer: PropTypes.number.isRequired,
};

export default LineProgressWithLabel;
