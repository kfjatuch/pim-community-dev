import { SearchOptions } from '../product';
import { RawProductInterface } from 'pimfront/js/product/domain/model/product';

class ProductGridFetcher {
  search (searchOptions: SearchOptions): Promise<RawProductInterface[]> {
    return new Promise((resolve, reject) => {
      resolve([]);
    });
  }
};

export default ProductGridFetcher;