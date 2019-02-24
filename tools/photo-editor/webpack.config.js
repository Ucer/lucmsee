const HtmlWebpackPlugin = require('html-webpack-plugin');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const path = require('path');


module.exports = (env = {}) => ({
    entry: './src/index.js',
    output: {
        path: path.resolve(__dirname, '../../public/tools/photo-editor/dist'),
        publicPath: env.production ? 'http://lucmsee.test/tools/photo-editor/dist/' : ''
    },
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader',
            },
            {
                test: /\.css$/,
                use: [
                    'vue-style-loader',
                    {
                        loader: 'css-loader',
                        options: {
                            importLoaders: 1,
                        },
                    },
                    'postcss-loader',
                ],
            },
            {
                test: /\.js$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
            },
        ],
    },
    plugins: [
        new VueLoaderPlugin(),
        new HtmlWebpackPlugin({
            filename: env.production ? '../../../../resources/views/tools/photo-editor.blade.php' : 'index.html',
            template: './src/index.html',
        }),
    ],
});
