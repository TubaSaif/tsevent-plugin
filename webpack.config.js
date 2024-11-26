const path = require('path');

module.exports = {
    mode: 'production',
    entry: {
        main: './assets/src/js/public.js',
        admin: './assets/src/js/admin.js',
    },
    output: {
        filename: '[name].bundle.js',
        path: path.resolve(__dirname, 'assets/dist'),
    },
    module: {
        rules: [
            {
                test: /\.css$/,
                use: ['style-loader', 'css-loader'],
            },
        ],
    },
};
