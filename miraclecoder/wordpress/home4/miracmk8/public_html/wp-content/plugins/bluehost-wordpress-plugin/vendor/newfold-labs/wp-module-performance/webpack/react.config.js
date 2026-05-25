// webpack/react.config.js

const path = require("path");
const { merge } = require("webpack-merge");
const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const TerserPlugin = require("terser-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");

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

const customConfig = {
  // Set mode to production to enable optimizations like minification
  mode: "production",
  entry: {
    performance: path.resolve(process.cwd(), "src/performance.js") // Main React entry point
  },
  output: {
    path: path.resolve(process.cwd(), "build/performance"),
    filename: "performance.min.js" // Output JS
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: "performance.min.css" // Output CSS
    })
  ],
  optimization: {
    concatenateModules: false,
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
  }
};

// Merge configurations with custom config taking precedence for optimization
module.exports = merge(
  {
    ...defaultConfig,
    optimization: {
      ...defaultConfig.optimization,
      ...customConfig.optimization
    }
  },
  customConfig
);
