import React, { useEffect } from 'react';
import { useParams } from 'react-router-dom';
import { LazyLoadComponent } from 'react-lazy-load-image-component';
import 'react-lazy-load-image-component/src/effects/blur.css';
import { Helmet } from 'react-helmet';
import axios from 'axios';
import Index from './detail/Index';
import NotFound from './NotFound';
import Loading from './common/Loading';

function Detail() {
  const { hash } = useParams();
  const [image, setImage] = React.useState(null);
  const [isLoaded, setIsLoaded] = React.useState(false);
  const [isError, setIsError] = React.useState(false);

  useEffect(() => {
    const getImage = async () => {
      await axios.get(`/api/v1/image/${hash}`)
        .then((response) => {
          const { data } = response.data;
          setImage(data);
          setIsLoaded(true);
        })
        .catch(() => {
          setIsError(true);
          setIsLoaded(true);
        });
    };
    getImage();
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
      <Helmet
        title={`${process.env.MIX_RODA_NAME} | ${image.basename}`}
        meta={[
          {
            name: 'description',
            content: `${process.env.MIX_RODA_DESCRIPTION}`,
          },
          { property: 'og:image', content: image.thumbnail },
          {
            property: 'og:titie',
            content: `${process.env.MIX_RODA_NAME} | ${image.basename}`,
          },
          {
            property: 'og:type',
            content: 'article',
          },
          {
            property: 'og:site_name',
            conetnt: `${process.env.MIX_RODA_NAME}`,
          },
          {
            property: 'og:description',
            content: image.comment,
          },
          {
            property: 'og:url',
            content: image.imageDetail,
          },
          {
            name: 'twitter:card',
            content: 'summary',
          },
          {
            name: 'twitter:site',
            content: `${process.env.MIX_RODA_TWITTER_ACCOUNT}`,
          },
          {
            name: 'twitter:title',
            content: `${process.env.MIX_APP_URL}`,
          },
          {
            name: 'twitter:content',
            content: `${process.env.MIX_RODA_DESCRIPTION}`,
          },
        ]}
      />
      <LazyLoadComponent id={image.basename}>
        <Index image={image} />
      </LazyLoadComponent>
    </>
  );
}

export default Detail;
