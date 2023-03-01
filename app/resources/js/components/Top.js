import React, { useEffect } from 'react';
import axios from 'axios';
import InfiniteScroll from 'react-infinite-scroller';
import Thumbnail from './top/Thumbnail';
import Loading from './common/Loading';
import ImageDialog from './top/ImageDialog';
import DMMAd from './common/DMMAd';

function Top() {
  const [items, setItems] = React.useState([]);
  const [hasMore, setHasMore] = React.useState(true);
  const [isError, setIsError] = React.useState(false);
  const [isOpenImage, setIsOpenImage] = React.useState(false);
  const [showImage, setShowImage] = React.useState({
    basename: '',
    detail: '',
    image: '',
    comment: '',
  });

  const getImages = async (p) => {
    await axios.get('/api/v1/image/', { params: { page: p } })
      .then((response) => {
        const { data } = response;
        const { meta } = data;

        if (data.data.length < 1 || !meta.to) {
          setHasMore(false);
          return;
        }

        setItems([...items, ...data.data]);
      })
      .catch(() => {
        setIsError(true);
      });
  };

  useEffect(() => {
    document.body.classList.toggle('is-fixed', isOpenImage);
  }, [isOpenImage]);

  const onClickThumbnail = (image) => {
    setIsOpenImage(true);
    setShowImage(image);
  };

  const showImages = (
    <>
      {items.map((image) => (
        <Thumbnail
          image={image}
          key={image.basename}
          handleClick={onClickThumbnail}
        />
      ))}
    </>
  );

  if (isError) {
    return (
      <>
        <div className="images error">could not load images</div>
      </>
    );
  }

  return (
    <>
      <DMMAd dmmid={process.env.MIX_RODA_DMM_ID1} />
      <InfiniteScroll
        pageStart={0}
        loadMore={getImages}
        hasMore={hasMore}
        loader={<Loading key={0} />}
        className='images'
      >
        {showImages}
      </InfiniteScroll>
      <ImageDialog
        isOpen={isOpenImage}
        image={showImage}
        setIsOpen={setIsOpenImage}
      />
    </>
  );
}

export default Top;
