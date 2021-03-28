import React from 'react';
import axios from 'axios';
import InfiniteScroll from 'react-infinite-scroller';

import Thumbnail from './top/Thumbnail';
import Loading from './common/Loading';

function Top() {
  const [items, setItems] = React.useState([]);
  const [hasMore, setHasMore] = React.useState(true);

  const getImages = async (p) => {
    const response = await axios.get('/api/v1/image/', { params: { page: p } });
    const data = response.data.data;

    if (data.length < 1) {
      setHasMore(false);
      return;
    }

    setItems([...items, ...data]);
  };

  return (
    <>
      <div className="images">
        <InfiniteScroll
          pageStart={0}
          loadMore={getImages}
          hasMore={hasMore}
          loader={<Loading />}
        >
          {items.map((image) => (
            <Thumbnail image={image} key={image.basename} />
          ))}
        </InfiniteScroll>
      </div>
    </>
  );
}

export default Top;
