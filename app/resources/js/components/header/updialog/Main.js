import React, { useEffect } from 'react';
import CancelIcon from '@material-ui/icons/Cancel';
import IconButton from '@material-ui/core/IconButton';
import Snackbar from '@material-ui/core/Snackbar';
import { makeStyles } from '@material-ui/core/styles';
import TextField from '@material-ui/core/TextField';
import axios from 'axios';
import UUID from 'uuidjs';
import { ReCaptcha } from 'react-recaptcha-v3';
import UpRodaImage from './UpRodaImage';
import UpRodaButton from './UpRodaButton';
import RodaUploadInput from './RodaUploadInput';

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
  const inputRecaptcha = React.useRef(null);

  const waitTime = process.env.MIX_RODA_WAIT_TIME;
  const sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

  const [file, setFile] = React.useState('');
  const [delkey, setDelkey] = React.useState('');
  const [comment, setComment] = React.useState('');
  const [image, setImage] = React.useState(null);
  const [fileSize, setFileSize] = React.useState(0);
  const [mimetype, setMimetype] = React.useState(null);

  const [chunkPos, setChunkPos] = React.useState(0);
  const [chunkCount, setChunkCount] = React.useState(0);
  const [uuid, setUuid] = React.useState(null);
  const [uploaded, setUploaded] = React.useState(null);
  const [progress, setProgress] = React.useState(0);
  const [progressBuffer, setProgressBuffer] = React.useState(0);

  const [isDragging, setIsDragging] = React.useState(false);

  const [snackOpen, setSnackOpen] = React.useState(false);
  const [snackMessage, setSnackMessage] = React.useState(null);
  const [recaptcha, setRecapcha] = React.useState(null);

  const chunkSize = process.env.MIX_RODA_UPLOAD_CHUNK;
  const maxByte = process.env.MIX_RODA_UPLOAD_MAXSIZE * 1024;
  const maxMB = process.env.MIX_RODA_UPLOAD_MAXSIZE / 1024;

  // Drag n Drop
  const [dragCounter, setDragCounter] = React.useState(0);
  const dropDiv = React.createRef();

  const verifyCallback = (recapchaToken) => {
    setRecapcha(recapchaToken);
  };

  const updateToken = () => {
    inputRecaptcha.current.execute();
  };

  const handleDragStart = (e) => {
    e.preventDefault();
    e.stopPropagation();
  };

  const handleDrag = (e) => {
    e.preventDefault();
    e.stopPropagation();
    setIsDragging(true);
  };

  const handleDragIn = (e) => {
    e.preventDefault();
    e.stopPropagation();
    setDragCounter(dragCounter + 1);
    if (e.dataTransfer.items && e.dataTransfer.items.length > 0) {
      setIsDragging(true);
    }
  };

  const handleDragOut = (e) => {
    e.preventDefault();
    e.stopPropagation();
    setDragCounter(dragCounter - 1);
    if (dragCounter === 0) {
      setIsDragging(false);
    }
  };

  const dropFiles = (files) => {
    console.log(files);
    if (files.length > 0) {
      const f = files[0];
      if (f.type.match('^image/')) {
        if (f.size > maxByte) {
          setSnackMessage(`フィルサイズは${maxMB.toFixed(1)}MBまでです`);
          setSnackOpen(true);
        } else {
          setImage(f);
          setMimetype(f.type);
          setFileSize(f.size);
          setFile(URL.createObjectURL(f));
        }

        if (files.length > 1) {
          setSnackMessage('1ファイルのみアップロードできます');
          setSnackOpen(true);
        }
      } else {
        setSnackMessage('画像のみアップロードできます');
        setSnackOpen(true);
      }
    }
  };

  const handleDrop = (e) => {
    e.preventDefault();
    e.stopPropagation();
    setIsDragging(false);
    if (e.dataTransfer.items && e.dataTransfer.items.length > 0) {
      dropFiles(e.dataTransfer.files);
      e.dataTransfer.clearData();
      setDragCounter(0);
    }
  };

  useEffect(() => {
    dropDiv.current.addEventListener('dragstart', handleDragStart);
    dropDiv.current.addEventListener('dragenter', handleDragIn);
    dropDiv.current.addEventListener('dragleave', handleDragOut);
    dropDiv.current.addEventListener('dragover', handleDrag);
    dropDiv.current.addEventListener('drop', handleDrop);
  }, [setIsDragging]);

  const handleCancelImage = () => {
    setFile('');
    setImage(null);
    setDelkey('');
    setComment('');
    setFileSize(0);
    setMimetype('');
    setChunkCount(0);
    setChunkPos(0);
  };

  const handleCloseSnack = () => {
    setSnackOpen(false);
  };

  const sendImage = async () => {
    if (image && chunkPos) {
      const pos = chunkPos - 1;
      const start = pos * chunkSize;
      const upsize = (pos + 1) * chunkSize;
      const endRange = (upsize <= fileSize) ? upsize - 1 : fileSize - 1;
      const chunk = image.slice(start, upsize, mimetype);

      const formData = new FormData();

      if (delkey) {
        formData.append('delkey', delkey);
      }

      if (comment) {
        formData.append('comment', comment);
      }


      formData.append('hash', uuid);
      formData.append('file', chunk, image.name);
      formData.append('token', recaptcha);

      const headers = {
        'Content-Type': 'multipart/form-data',
        'Content-Range': `bytes ${start}-${endRange}/${image.size}`,
        Accept: 'application/json',
      };

      await axios.post(
        '/api/v1/image',
        formData,
        {
          headers,
        },
      )
        .then((response) => {
          if (chunkPos < chunkCount) {
            sleep(waitTime);
            setProgress(Math.ceil((chunkPos / chunkCount) * 100));
            setChunkPos(chunkPos + 1);
          } else {
            setUploaded(response.data);
            setProgressBuffer(100);
            setProgress(100);
          }
        })
        .catch(() => {
          setSnackMessage('アップロードに失敗したお');
          setSnackOpen(true);
          handleCancelImage();
        });
    }
  };

  useEffect(() => {
    if (image) {
      setProgressBuffer(Math.ceil((chunkPos / chunkCount) * 100));
      sendImage();
    }
  }, [chunkPos]);

  useEffect(() => {
    console.log(progress);
    console.log(uploaded);
    if (progress >= 100) {
      sleep(1000);
      if (uploaded) {
        window.location = uploaded.data.detail;
      }
    }
  }, [progress, uploaded]);

  const handleUpload = async () => {
    if (image) {
      updateToken();
      setChunkCount(Math.ceil(image.size / chunkSize));
      setUuid(UUID.generate());
      setMimetype(image.type);
      setChunkPos(1);
    } else {
      setSnackMessage('画像が選択されていません');
      setSnackOpen(true);
    }
  };

  return (
    <div className="upload-image" ref={dropDiv}>
      <form autoComplete="off">
        <RodaUploadInput
          handleSetFile={setFile}
          handleSetImage={setImage}
          handleSetFileSize={setFileSize}
          handleSetMimeType={setMimetype}
          handleSetSnackOpen={setSnackOpen}
          handleSetSnackMessage={setSnackMessage}
          handleDropFiles={dropFiles}
          isDialogDragging={isDragging}
        />
        <div className={classes.root}>
          <TextField
            name="delkey"
            label="Delete key"
            color="secondary"
            style={{ marginTop: '0.5rem' }}
            fullWidth
            margin="normal"
            InputLabelProps={{
              shrink: true,
            }}
            inputProps={{
              maxLength: 20,
            }}
            onChange={(e) => {
              setDelkey(e.target.value);
            }}
          />
          <TextField
            name="comment"
            label="Comment"
            color="secondary"
            style={{ marginTop: '0.5rem' }}
            fullWidth
            margin="normal"
            InputLabelProps={{
              shrink: true,
            }}
            inputProps={{
              maxLength: 200,
            }}
            onChange={(e) => {
              setComment(e.target.value);
            }}
          />
        </div>
        <UpRodaButton
          chunkPos={chunkPos}
          handleUpload={handleUpload}
          progress={progress}
          progressBuffer={progressBuffer}
        />
        <UpRodaImage
          image={file}
          chunkPos={chunkPos}
          handleCancelImage={handleCancelImage}
        />
      </form>
      <Snackbar
        anchorOrigin={{ vertical: 'top', horizontal: 'right' }}
        open={snackOpen}
        autoHideDuration={2000}
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
      <ReCaptcha
        ref={inputRecaptcha}
        sitekey={process.env.MIX_RODA_GOOGLE_RECAPTCHA_SITEKEY}
        action="uploda"
        verifyCallback={verifyCallback}
      />
    </div>
  );
}

export default Main;
