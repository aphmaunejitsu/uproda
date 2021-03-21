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

  if (isLoaded) {
    return (
      <div className="images">
        {items.map((image) => (
          <Thumbnail image={image} key={image.id} />
        ))}
      </div>
    );
  }

  if (error) {
    return <div>error...</div>;
  }

  return <div className="Loading">Loading...</div>;
}

export default Top;
