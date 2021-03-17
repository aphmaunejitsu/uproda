import React, { useEffect } from 'react';
import axios from 'axios';
import { Link } from 'react-router-dom';

function Top() {
  // const [error, setError] = React.useState(null);
  // const [isLoaded, setIsLoaded] = React.useState(false);
  const [items, setItems] = React.useState([]);
  const getImages = async () => {
    const response = await axios.get('/api/v1/image/');
    console.log(response.data);
    setItems(response.data.data);
  };

  useEffect(() => {
    getImages();
  }, []);

  return (
    <div className="images">
      {items.map(
        (image) => (
          <div key={image.id} className="thumbnail">
            <Link to={image.detail}>
              <img src={image.thumbnail} alt="" />
            </Link>
          </div>
        ),
      )}
    </div>
  );
}

export default Top;
