import React from 'react';
import ReactPagination from 'react-paginate';
import PropTypes from 'prop-types';

function Pagination({ data, handlePagination }) {
  console.log(data.meta);
  return (
    <nav aria-label="Page navigation">
      <ReactPagination
        pageCount={data.meta.last_page}
        marginPagesDisplayed={2}
        pageRangeDisplayed={1}
        containerClassName="pagination justify-content-center"
        previousClassName="page-item"
        previousLinkClassName="page-link"
        previousLabel="<"
        nextClassName="page-item"
        nextLinkClassName="page-link"
        nextLabel=">"
        breakLabel="..."
        breakClassName="break-me"
        pageClassName="page-item"
        pageLinkClassName="page-link"
        activeClassName="active"
        activeLinkClassName="page-link"
        onPageChange={handlePagination}
      />
    </nav>
  );
}

Pagination.propTypes = {
  data: PropTypes.shape({
    meta: PropTypes.shape({
      last_page: PropTypes.number.isRequired,
      current_page: PropTypes.number.isRequired,
    }),
  }).isRequired,
  handlePagination: PropTypes.func.isRequired,
};

export default Pagination;
