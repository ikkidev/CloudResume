/**
 * Class to show Marketplace products (plugins) in a tab under plugin-install.php
 */

class NFDPluginsMarketplace {
  constructor() {
    // Add event listener for when the DOM is loaded
    window.addEventListener('DOMContentLoaded', () => {
      this.setupContainer();

      // Fetch data from the Marketplace API
      fetch(
        nfdPremiumPluginsMarketplace.restApiRoot +
          '/newfold-marketplace/v1/marketplace',
        {
          credentials: 'same-origin',
          headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': nfdPremiumPluginsMarketplace.restApiNonce,
          },
        }
      )
        .then((response) => response.json())
        .then((data) => {
          if (!data.hasOwnProperty('products')) {
            this.isError();
          } else {
            this.isLoaded();
            this.showProducts(this.filterProducts(data.products.data));
          }
        });
    });
  }

  // Function that sets up the container for the plugin marketplace
  setupContainer() {
    const pluginsListTable = document.createElement('div');
    pluginsListTable.classList.add(
      'wp-list-table',
      'widefat',
      'plugin-install',
      'nfd-premium-plugins-marketplace'
    );
    pluginsListTable.innerHTML = `<h2 class="screen-reader-text">Plugins list</h2>
        <p>${nfdPremiumPluginsMarketplace.marketplaceDescription}</p>
        <div id="the-list">${this.isLoading()}</div>`;

    const filterArea = document.querySelector(
      '.plugin-install-tab-premium-marketplace .wp-filter'
    );
    filterArea.parentNode.insertBefore(
      pluginsListTable,
      filterArea.nextSibling
    );
  }

  // Filter products to only show plugins
  filterProducts(products) {
    const plugins = products.filter((product) => product.type == 'plugin');

    return plugins;
  }

  // Returns whether the environment supports CTB
  supportsCTB() {
    if (typeof nfdctb === 'undefined') {
      return false;
    }

    return nfdctb.supportsCTB;
  }

  // Show the filtered products on the page
  showProducts(products) {
    // Helper function to render the thumbnail for each product
    const renderThumbnail = (product) => {
      if (product.smallThumbnailUrl) {
        return `<img src="${product.smallThumbnailUrl}" alt="${product.name} Thumbnail" width="128px" height="128px">`;
      }

      const fallback =
        'https://hiive-space-cdn.nyc3.cdn.digitaloceanspaces.com/marketplace/premium-plugins/fallback.svg';
      return `<img src="${fallback}" alt="${product.name} Thumbnail" width="128px" height="128px">`;
    };

    // Helper function to render the action buttons for each product
    const renderActions = (product) => {
      let actionButtons = [];

      if (product.primaryUrl && product.price) {
        const primaryAction = `<a target="_blank" 
                href="${product.primaryUrl}" 
                class="button button-primary nfd-ctb-btn"
                ${
                   product?.clickToBuyId
                    ? 'data-action="load-nfd-ctb" data-ctb-id="' +
                      product?.clickToBuyId +
                      '"'
                    : ''
                }>Buy $${product.price}</a>`;

        actionButtons.push(primaryAction);
      }

      if (product.secondaryUrl && this.isValidUrl(product.secondaryUrl)) {
        const secondaryAction = `<a target="_blank" href="${product.secondaryUrl}">More Details</a>`;

        actionButtons.push(secondaryAction);
      }

      return actionButtons.join('');
    };

    // Helper function to render a template for each product
    const buildProduct = (product) => {
      return `<div class="plugin-card">
                <div class="nfd-plugin-card-content">
                    <div class="nfd-plugin-card-thumbnail">
                        ${renderThumbnail(product)}
                    </div>
                    <div class="nfd-plugin-card-title name column-name">
                        <h3>${product.name}</h3>
                    </div>
                    <div class="nfd-plugin-card-actions action-links">
                        ${renderActions(product)}
                    </div>
                    <div class="nfd-plugin-card-desc desc column-description">
                        <p>${product.description}</p>
                    </div>
                </div>
            </div>`;
    };

    const render = products.map((product) => buildProduct(product)).join('');

    const pluginsListTable = document.querySelector('.wp-list-table #the-list');
    return (pluginsListTable.innerHTML = render);
  }

  // Shows skeleton when the marketplace is loading API data
  isLoading() {
    // Helper function to generate skeleton template
    const skeleton = (styles, customClass) => {
      return `<div class="nfd-skeleton ${customClass || ''}" 
                    style="
                    width: ${styles.width || '100%'}; 
                    height: ${styles.height || 'auto'};
                    margin-top: ${styles.marginTop || '0px'};
                    margin-left: ${styles.marginLeft || '0px'};
                    margin-bottom: ${styles.marginBottom || '0px'};
                    margin-right: ${styles.marginRight || '0px'};
                    "></div>`;
    };

    // Helper function to build a product skeleton template
    const buildSkeleton = () => {
      return `<div class="plugin-card">
                <div class="nfd-plugin-card-content">
                    <div class="nfd-plugin-card-thumbnail">
                        ${skeleton({ width: '128px', height: '128px' })}
                    </div>
                    <div class="nfd-plugin-card-title">
                        ${skeleton({
                          width: '70%',
                          height: '25px',
                          marginBottom: '20px',
                        })}
                    </div>
                    <div class="nfd-plugin-card-actions">
                        ${skeleton({
                          width: '90px',
                          height: '32px',
                          marginRight: '0px',
                          marginLeft: 'auto',
                        })}
                    </div>
                    <div class="nfd-plugin-card-desc">
                        ${skeleton({ width: '100%', height: '12px' })}
                        ${skeleton({
                          width: '90%',
                          height: '12px',
                          marginTop: '8px',
                        })}
                        ${skeleton({
                          width: '100%',
                          height: '12px',
                          marginTop: '8px',
                        })}
                        ${skeleton({
                          width: '60%',
                          height: '12px',
                          marginTop: '8px',
                        })}
                    </div>
                </div>
            </div>`;
    };

    const items = 12;
    let pluginCardsSkeleton = [];

    for (let i = 0; i < items; i++) {
      pluginCardsSkeleton += buildSkeleton();
    }

    return pluginCardsSkeleton;
  }

  // Clears the marketplace container from skeleton HTML
  isLoaded() {
    const pluginsListTable = document.querySelector('.wp-list-table #the-list');
    pluginsListTable.innerHTML = '';
  }

  // Shows error message when API fail
  isError() {
    const pluginsListTable = document.querySelector('.wp-list-table #the-list');
    pluginsListTable.innerHTML = `<div class="nfd-premium-marketplace-loading-error">
            <div class="nfd-premium-marketplace-loading-error-content">
                <img src="https://hiive-space-cdn.nyc3.cdn.digitaloceanspaces.com/marketplace/premium-plugins/error-loading.webp" alt="Error laoding content">
                <h2>Oops, something went wrong.</h2>
                <p>Couldn't load marketplace content right now, please check back later.</p>
                <a class="button button-primary" id="nfd-premium-marketplace-reload">Try Again</a>
            </div>
        </div>`;

    document.addEventListener('mouseup', (e) => {
      if (e.target.id === 'nfd-premium-marketplace-reload') {
        location.reload();
      }
    });
  }

  // Check if a string is a valid URL
  isValidUrl(value) {
    try {
        new URL(value);
        return true;
    } catch (e) {
        return false;
    }
  }
}

const nfdPluginsMarketplace = new NFDPluginsMarketplace();
