// webpack/react.config.js

const path = require('path');
const { merge } = require('webpack-merge');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const customConfig = {
	entry: {
		staging: path.resolve(process.cwd(), 'src/staging.js'), // Main React entry point
	},
	output: {
		path: path.resolve(process.cwd(), 'build/staging'),
		filename: 'staging.min.js', // Output JS
	},
	plugins: [
		new MiniCssExtractPlugin({
			filename: '[name].min.css', // Output CSS
		}),
	],
};

module.exports = merge(defaultConfig, customConfig);
