const merge = require('webpack-merge');
const config = require('./webpack.config.js');

const HtmlWebpackPlugin = require('html-webpack-plugin');
const HtmlWebpackInlineSourcePlugin = require('html-webpack-inline-source-plugin');
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");
const UglifyJsPlugin = require("uglifyjs-webpack-plugin");
const ASSET_PATH = process.env.ASSET_PATH || '';

module.exports = merge(config, {
    mode: 'production',
    output: {
		      publicPath: ASSET_PATH
    },
     optimization: {
        minimizer: [
            new UglifyJsPlugin({
                cache: true,
                parallel: true,
            }),
            new OptimizeCSSAssetsPlugin({}),
        ]
    },
    plugins: [
        new HtmlWebpackPlugin({
            minify: {
                removeComments: true,
                collapseWhitespace: true,
            },
            template: 'index.html',
        }),
        new HtmlWebpackInlineSourcePlugin(),
    ]
});
