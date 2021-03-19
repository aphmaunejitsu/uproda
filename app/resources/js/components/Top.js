import React, { useEffect } from 'react';
import axios from 'axios';
import Thumbnail from './top/Thumbnail';


function Top() {
  const [error, setError] = React.useState(null);
  const [isLoaded, setIsLoaded] = React.useState(false);
  const [items, setItems] = React.useState([]);
  const getImages = async () => {
    try {
      const response = await axios.get('/api/v1/image/');
      setItems(response.data.data);
      setIsLoaded(true);
    } catch (e) {
      setError(true);
    }
  };

  useEffect(() => {
    getImages();
  }, []);

  let el;
  if (isLoaded) {
    el = (
      <div className="images">
        {items.map((image) => (
          <Thumbnail
            key={image.id}
            image={image}
          />
        ))}
      </div>
    );
  } else if (error) {
    el = <div>error...</div>;
  } else {
    el = (
      <div className="Loading">Loading...</div>
    );
  }

  return el;
}

export default Top;
