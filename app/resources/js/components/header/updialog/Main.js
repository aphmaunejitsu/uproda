import React from 'react';
import CancelIcon from '@material-ui/icons/Cancel';
import IconButton from '@material-ui/core/IconButton';
import Snackbar from '@material-ui/core/Snackbar';
import { makeStyles } from '@material-ui/core/styles';
import TextField from '@material-ui/core/TextField';
import Button from '@material-ui/core/Button';

const useStyles = makeStyles((theme) => ({
  root: {
    display: 'flex',
    flexWrap: 'wrap',
  },
  textField: {
    marginLeft: theme.spacing(1),
    marginRight: theme.spacing(1),
    width: '25ch',
  },
}));

function Main() {
  const classes = useStyles();
  const inputFile = React.useRef(null);
  const [file, setFile] = React.useState(false);
  const [snackOpen, setSnackOpen] = React.useState(false);
  const [snackMessage, setSnackMessage] = React.useState(null);

  const handleFileOnChange = (e) => {
    if (e.target.files.length > 0) {
      const f = e.target.files[0];
      if (f.size > process.env.MIX_RODA_UPLOAD_MAXSIZE * 1024 * 1024) {
        setSnackMessage(`フィルサイズは${process.env.MIX_RODA_UPLOAD_MAXSIZE}MBまでです`);
        setSnackOpen(true);
      } else {
        setFile(URL.createObjectURL(f));
      }
    }
  };

  const handleCancelImage = () => {
    setFile(false);
    inputFile.current.value = null;
  };

  const handleCloseSnack = () => {
    setSnackOpen(false);
  };

  return (
    <div className="upload-image">
      <form>
        <div className="upload-file">
          <label htmlFor="roda-upload">
            <input
              type="file"
              id="roda-upload"
              accept={process.env.MIX_RODA_ACCEPT_FILES}
              onChange={(event) => handleFileOnChange(event)}
              ref={inputFile}
            />
            <span>
              画像を選択
              <br />
              max filesize:
              {process.env.MIX_RODA_UPLOAD_MAXSIZE}
              MB
            </span>
          </label>
        </div>
        <div className={classes.root}>
          <TextField
            name="delkey"
            label="Delete key"
            style={{ marginTop: '0.5rem' }}
            fullWidth
            margin="normal"
            InputLabelProps={{
              shrink: true,
            }}
            inputProps={{
              maxLength: 20,
              autoComplete: 'off',
            }}
          />
          <TextField
            name="comment"
            label="Comment"
            style={{ marginTop: '0.5rem' }}
            fullWidth
            margin="normal"
            InputLabelProps={{
              shrink: true,
            }}
            inputProps={{
              maxLength: 20,
              autoComplete: 'off',
            }}
          />
        </div>
        <div className="roda-upload-image">
          {
            file
              ? <Button variant="contained">Upload</Button>
              : <Button variant="contained" disabled>Upload</Button>
          }
        </div>
        <div className="roda-image">
          {
            file
              ? (
                <>
                  <img src={file} alt="Upload" />
                  <IconButton
                    onClick={() => handleCancelImage()}
                    aria-label="close"
                    color="inherit"
                    size="small"
                    className="cancel-image-button"
                  >
                    <CancelIcon />
                  </IconButton>
                </>
              )
              : <span>No Image</span>
          }
        </div>
      </form>
      <Snackbar
        anchorOrigin={{ vertical: 'top', horizontal: 'right' }}
        open={snackOpen}
        autoHideDuration={5000}
        onClose={handleCloseSnack}
        message={snackMessage}
        action={[
          <IconButton
            key="close"
            aria-label="close"
            color="inherit"
            onClick={handleCloseSnack}
          >
            <CancelIcon />
          </IconButton>,
        ]}
      />
    </div>
  );
}

export default Main;
