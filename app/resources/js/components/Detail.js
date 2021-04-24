import React, { useEffect } from 'react';
import { useParams } from 'react-router-dom';
import { LazyLoadComponent } from 'react-lazy-load-image-component';
import 'react-lazy-load-image-component/src/effects/blur.css';
import axios from 'axios';
import ImageDetail from './detail/Main';
import NotFound from './NotFound';
import Loading from './common/Loading';

function Detail() {
  const { hash } = useParams();
  const [image, setImage] = React.useState(null);
  const [isLoaded, setIsLoaded] = React.useState(false);
  const [isError, setIsError] = React.useState(false);

  useEffect(() => {
    let unmounted = false;
    const getImage = async () => {
      await axios.get(`/api/v1/image/${hash}`)
        .then((response) => {
          const { data } = response.data;
          if (!unmounted) {
            setImage(data);
            setIsLoaded(true);
          }
        })
        .catch(() => {
          if (!unmounted) {
            setIsError(true);
            setIsLoaded(true);
          }
        });
    };
    getImage();
    const cleanup = () => {
      unmounted = true;
    };
    return cleanup;
  }, []);

  if (!isLoaded) {
    return (
      <>
        <Loading />
      </>
    );
  }

  if (isError) {
    return (
      <>
        <NotFound />
      </>
    );
  }

  return (
    <>
      <LazyLoadComponent id={image.basename}>
        <ImageDetail image={image} />
      </LazyLoadComponent>
    </>
  );
}

export default Detail;
