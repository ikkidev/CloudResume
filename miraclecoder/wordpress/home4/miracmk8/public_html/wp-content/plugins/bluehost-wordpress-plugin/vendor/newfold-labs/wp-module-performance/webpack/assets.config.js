// webpack/assets.config.js

const path = require("path");
const glob = require("glob");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");

// Absolute paths for source and output directories (based on project root)
const assetsPath = path.resolve(process.cwd(), "assets");
const buildPath = path.resolve(process.cwd(), "build/assets");

/**
 * Terser's function to decide which comments to preserve.
 *
 * @see https://github.com/Automattic/jetpack/blob/fdf3b72390c7fcb64508d985149de2af01b935b3/projects/js-packages/webpack-config/src/webpack/terser.js
 * @see https://github.com/terser/terser/blob/v5.9.0/lib/output.js#L171-L177
 * @param {object} comment - Comment object.
 * @param {string} comment.type - Comment type.
 * @param {string} comment.value - Comment text.
 * @returns {boolean} Whether to keep it.
 */
function isSomeComments(comment) {
  return (
    (comment.type === "comment2" || comment.type === "comment1") &&
    /@preserve|@lic|@cc_on|^\**!/i.test(comment.value)
  );
}

/**
 * Function to match a WP i18n "translators" comment.
 *
 * @see https://github.com/Automattic/jetpack/blob/fdf3b72390c7fcb64508d985149de2af01b935b3/projects/js-packages/webpack-config/src/webpack/terser.js
 * @see https://github.com/php-gettext/Gettext/blob/4.x/src/Utils/ParsedComment.php#L53-L73
 * @see https://github.com/wp-cli/i18n-command/blob/v2.2.9/src/JsCodeExtractor.php#L15
 * @param {object} comment - Comment object.
 * @param {string} comment.type - Comment type.
 * @param {string} comment.value - Comment text.
 * @returns {boolean} Whether to keep it.
 */
function isTranslatorsComment(comment) {
  return (
    (comment.type === "comment2" || comment.type === "comment1") &&
    /^[#*/ \t\r\n]*[tT]ranslators/.test(comment.value)
  );
}

module.exports = {
  // Set mode to production to enable optimizations like minification
  mode: "production",

  // Dynamically create multiple entry points from JS files in the assets folder
  entry: glob.sync(path.join(assetsPath, "**/*.js")).reduce((entries, file) => {
    // Strip path and extension to create the output file name
    const name = path.relative(assetsPath, file).replace(/(\.min)?\.js$/, ""); // Prevent double .min if already minified

    entries[name] = file; // e.g., 'image/image-bulk-optimizer': './assets/image/image-bulk-optimizer.js'
    return entries;
  }, {}),

  // Output compiled files to the build folder
  output: {
    path: buildPath,
    filename: "[name].min.js" // e.g., image/image-bulk-optimizer.min.js
  },

  module: {
    rules: [
      {
        // Handle all .css imports using PostCSS and MiniCssExtractPlugin
        test: /\.css$/i,
        use: [
          MiniCssExtractPlugin.loader, // Extract CSS to separate files
          "css-loader" // Resolve @import and url() in CSS
        ]
      }
    ]
  },

  optimization: {
    // Enable minification
    minimize: true,
    minimizer: [
      // Minify JS
      new TerserPlugin({
        terserOptions: {
          mangle: {
            reserved: ["__", "_n", "_nx", "_x"]
          },
          compress: {
            drop_console: true // Remove console.* calls
          },
          format: {
            // The `new Function` bit here is a hack to work around the way terser-webpack-plugin serializes
            // the terserOptions. The "comments" function must not refer to anything from the local or global scope,
            // so we "paste" our external functions inside.
            comments: new Function(
              "node",
              "comment",
              `${isTranslatorsComment}; return isTranslatorsComment( comment )`
            )
          }
        },
        // Same.
        extractComments: new Function(
          "node",
          "comment",
          `${isSomeComments}; return isSomeComments( comment )`
        )
      }),

      // Minify CSS
      new CssMinimizerPlugin()
    ]
  },

  plugins: [
    // Output CSS files with the same `[name].min.css` pattern
    new MiniCssExtractPlugin({
      filename: "[name].min.css" // e.g., image/image-bulk-optimizer.min.css
    })
  ],

  resolve: {
    // Allow imports without specifying these extensions
    extensions: [".js", ".css"]
  }
};
