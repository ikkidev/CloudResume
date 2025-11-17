const paths = require('./paths')
const glob = require('glob')
const path = require('path')

const ESLintPlugin = require('eslint-webpack-plugin')
const CopyPlugin = require('copy-webpack-plugin')
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const RemovePlugin = require('remove-files-webpack-plugin')
const SpriteLoaderPlugin = require('svg-sprite-loader/plugin')
const StylelintPlugin = require('stylelint-webpack-plugin')
const PurgecssPlugin = require('purgecss-webpack-plugin')
const TerserPlugin = require('terser-webpack-plugin')

/**
 * Generating stylesheets
 * --------------------------------------------------------------------------------
 * All .scss files that need to generate a .css file have their own entry inside
 * the entry object inside the Webpack configuration. Chunk names should always be
 * in snake case and start with 'styles'.
 *
 * Examples output based on chunk names:
 *
 * styles_style => style.css
 * styles_style_context => style.context.css
 */

module.exports = {
    entry: {
        front: paths.src.js + '/front.js',
        admin: paths.src.js + '/admin.js',
        // style entries
        styles_admin: paths.src.scss + '/admin.scss',
        styles_front: paths.src.scss + '/front.scss',
        // assets
        assets_icons: paths.src.jsConfig + '/sprite.js'
    },
    output: {
        clean: true,
        filename: '[name].min.js',
        path: paths.dist.js
    },
    module: {
        rules: [
            {
                test: /\.scss$/i,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    {
                        loader: 'postcss-loader',
                        options: {
                            postcssOptions: {
                                plugins: [['postcss-preset-env']]
                            }
                        }
                    },
                    {
                        loader: 'sass-loader',
                        options: {
                            sassOptions: {
                                includePaths: ['./node_modules/normalize.css', './node_modules/sass-mq'],
                                quietDeps: true
                            }
                        }
                    }
                ]
            },
            {
                test: /\.js$/,
                use: ['babel-loader'],
                exclude: /node_modules/
            },
            {
                test: /\.svg$/,
                include: [paths.src.fontAwesomeFree, paths.src.fontAwesomePro, paths.src.svg],
                use: [
                    {
                        loader: 'svg-sprite-loader',
                        options: {
                            extract: true,
                            spriteFilename: path => {
                                const pathArr = path.split('/')
                                const isFontAwesomeFree = path.indexOf('fontawesome-free') !== -1
                                const isFontAwesomePro = path.indexOf('fontawesome-pro') !== -1
                                let folder = pathArr[pathArr.length - 2]
                                if (isFontAwesomeFree) {
                                    folder = `fontawesome-free-${folder}`
                                }
                                if (isFontAwesomePro) {
                                    folder = `fontawesome-pro-${folder}`
                                }
                                return `../icons/sprite-${folder}.svg` // relative to output.path
                            }
                        }
                    },
                    'svgo-loader'
                ]
            },
            {
                test: /\.(jpe?g|png|gif|svg)$/i,
                exclude: [paths.src.fontAwesomeFree, paths.src.fontAwesomePro, paths.src.svg],
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            outputPath: '../img',
                            name: '[name].[ext]'
                        }
                    }
                ]
            }
        ]
    },
    plugins: [
        new ESLintPlugin({
            files: ['.', 'src', 'config'],
            formatter: 'table',
            exclude: ['node_modules', 'Resources/js/utils/postscribe/*']
        }),
        new CopyPlugin({
            patterns: [
                {
                    from: paths.src.svg,
                    to: '../svg/'
                }
            ]
        }),
        new MiniCssExtractPlugin({
            filename ({ chunk }) {
                const parts = chunk.name.split('_')

                parts.shift()

                const name = parts.length > 1 ? parts.join('.') : parts[0]
                // returned path is relative to paths.dist.js
                return `../css/${name}.css`
            }
        }),
        new RemovePlugin({
            before: {
                test: [
                    {
                        folder: 'Assets',
                        method: path => path,
                        recursive: true
                    }
                ]
            },
            after: {
                test: [
                    {
                        folder: 'Assets',
                        method: path => {
                            const file = path.split('/').pop()
                            return file.startsWith('styles_') || file.startsWith('lang_') || file.startsWith('assets_')
                        },
                        recursive: true
                    }
                ]
            }
        }),
        new SpriteLoaderPlugin({
            plainSprite: true
        }),
        new StylelintPlugin({
            extensions: ['scss']
        }),
        new PurgecssPlugin({
            paths: [].concat.apply([], [
                './../WordPress/**/*.php',
                './../Templates/**/*.php',
                './../Resources/**/*.js'
            ].map(x => glob.sync(path.resolve(__dirname, x))))
        })
    ],
    optimization: {
        minimize: true,
        minimizer: [new CssMinimizerPlugin(), new TerserPlugin()]
    },
    resolve: {
        alias: {
            '@fa-free-svg': paths.src.fontAwesomeFree,
            '@fa-pro-svg': paths.src.fontAwesomePro,
            '@vo-fonts': paths.src.fonts,
            '@vo-js': paths.src.js,
            '@vo-languages': paths.src.languages,
            '@vo-scss': paths.src.scss
        }
    }
}
