import React from 'react';
import axios from 'axios';
import InfiniteScroll from 'react-infinite-scroller';

import Thumbnail from './top/Thumbnail';
import Loading from './common/Loading';

function Top() {
  const [items, setItems] = React.useState([]);
  const [hasMore, setHasMore] = React.useState(true);
  const [isError, setIsError] = React.useState(false);

  const getImages = async (p) => {
    await axios.get('/api/v1/image/', { params: { page: p } })
      .then((response) => {
        const { data } = response;
        const images = data.data;

        if (images.length < 1) {
          setHasMore(false);
          return;
        }

        setItems([...items, ...images]);
      })
      .catch(() => {
        setIsError(true);
      });
  };

  const clickThumbnail = () => {
    console.log('click thumbnail');
  };

  if (isError) {
    return (
      <>
        <div className="images error">could not load images</div>
      </>

    );
  }

  return (
    <>
      <div className="images">
        <InfiniteScroll
          pageStart={0}
          loadMore={getImages}
          hasMore={hasMore}
          loader={<Loading key={0} />}
        >
          {items.map((image) => (
            <Thumbnail image={image} handleThumbnail={clickThumbnail} key={image.basename} />
          ))}
        </InfiniteScroll>
      </div>
    </>
  );
}

export default Top;
