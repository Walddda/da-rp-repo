const path = require('path');

module.exports = {
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
            '~~': path.resolve('resources/sass'),
        },
    },
    stats: {
        children: true,
        warningsFilter: [
          /\-\-underline\-color/,
        ]
      },
  
};
