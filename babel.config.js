module.exports = {
  presets: [
    [
      '@babel/preset-env',
      {
        targets: {
          node: 'current',
          browsers: [
            'last 2 versions',
            '> 1%',
            'not dead',
            'not ie 11'
          ]
        },
        useBuiltIns: 'usage',
        corejs: 3,
        modules: false,
        loose: true
      }
    ]
  ],
  plugins: [
    '@babel/plugin-proposal-object-rest-spread',
    '@babel/plugin-syntax-dynamic-import',
    '@babel/plugin-proposal-class-properties',
    '@babel/plugin-proposal-optional-chaining',
    '@babel/plugin-proposal-nullish-coalescing-operator',
    [
      '@babel/plugin-transform-runtime',
      {
        regenerator: true,
        corejs: 3
      }
    ]
  ],
  env: {
    test: {
      presets: [
        [
          '@babel/preset-env',
          {
            targets: {
              node: 'current'
            }
          }
        ]
      ]
    },
    production: {
      plugins: [
        'transform-remove-console',
        'transform-remove-debugger'
      ]
    }
  },
  ignore: [
    'node_modules',
    'dist'
  ],
  sourceMaps: true,
  retainLines: true
};