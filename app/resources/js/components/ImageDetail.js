import React from 'react';
import { useParams } from 'react-router-dom';

function ImageDetail() {
  const { hash } = useParams();
  return (
    <h1>
      Image: &nbsp;
      {hash}
    </h1>
  );
}

export default ImageDetail;
