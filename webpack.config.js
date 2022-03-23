const path = require('path');
const NodePolyfillPlugin = require("node-polyfill-webpack-plugin");

module.exports = {
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
            '~~': path.resolve('resources/sass'),
        },
        fallback: {
          "http": require.resolve("stream-http")
        }
    },
    plugins: [
      new NodePolyfillPlugin()
    ],
    stats: {
        children: true,
        warningsFilter: [
          /\-\-underline\-color/,
        ]
      },
  
};
