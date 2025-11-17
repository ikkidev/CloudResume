/**
 * Initialize the page.
 *
 * This function add all polyfills and then runs the callback.
 *
 * @param {Function} callback Function to run when the page is initialized.
 */
export default callback => {
    document.addEventListener('DOMContentLoaded', () => {
        callback()
    })
}
