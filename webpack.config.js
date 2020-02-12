const path = require('path');
const webpack = require('webpack');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const LiveReloadPlugin = require('webpack-livereload-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const StyleLintPlugin = require('stylelint-webpack-plugin');
const CompressionPlugin = require('compression-webpack-plugin');

const isProd = process.env.NODE_ENV === 'production' ? true : false;

var config = {
    mode: "development",
    watch: false,
    stats: "minimal",
    entry: {
        app: [
            path.resolve('app/client/js/app.js'),
            path.resolve('app/client/scss/app.scss')
        ],
    },
    output: {
        filename: '[name].js',
        path: path.resolve('public'),
        pathinfo: false,
    },
    module: {
        rules: [
            {
                enforce: 'pre',
                include: path.resolve('src'),
                test: /\.(js|vue)$/,
                loader: 'eslint-loader',
                exclude: /node_modules/
            },
            {
                test: /\.js$/,
                exclude: /mapbox-gl/,
                use: [
                    {
                        loader: 'babel-loader',
                        options: {
                            presets: [
                            '@babel/preset-env',
                            ],
                            plugins: [],
                        },
                    },
                    'eslint-loader',
                ]
            },
            {
                test: /\.vue$/,
                use: 'vue-loader',
            },
            {
                test: /\.scss$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader',
                        options: {
                            url: false
                        }
                    },
                    'postcss-loader',
                    {
                      loader: 'sass-loader'
                    }
                ]
            },
            {
            test: /\.css$/,
            use: [
                MiniCssExtractPlugin.loader,
                {
                    loader: 'css-loader',
                    options: {
                        url: false
                    }
                }
            ]
            }
        ],
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.js',
            node_modules: 'node_modules',
        }
    },
    plugins: [
        new VueLoaderPlugin(),
        new LiveReloadPlugin(),
        new StyleLintPlugin({
            'files': 'app/client/scss/**/*.scss',
            'lintDirtyModulesOnly': true
        }),
        new MiniCssExtractPlugin({
            filename: '[name].css'
        }),
        new webpack.ProvidePlugin({
            mapboxgl: 'mapbox-gl',
        })
    ],
};

module.exports = (env, argv) => {

    if (argv.mode === 'development') {
        config.devtool = 'inline-source-map';
    }

    if (argv.mode === 'production') {
        // Compress js and css using gzip
        config.plugins.push(
            new CompressionPlugin()
        );

        config.optimization = {
            splitChunks: {
            cacheGroups: {
                commons: {
                test: /[\\/]node_modules[\\/]/,
                name: 'vendor',
                chunks: 'initial',
                },
            },
            },
        };
    }

    return config;
};
