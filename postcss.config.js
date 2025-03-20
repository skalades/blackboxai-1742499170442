module.exports = {
  plugins: {
    'tailwindcss': {},
    'autoprefixer': {
      browsers: ['> 1%', 'last 2 versions', 'not dead'],
      grid: true
    },
    'postcss-import': {},
    'postcss-flexbugs-fixes': {},
    'postcss-preset-env': {
      autoprefixer: {
        flexbox: 'no-2009'
      },
      stage: 3,
      features: {
        'custom-properties': false,
        'nesting-rules': true
      }
    },
    'cssnano': process.env.NODE_ENV === 'production' ? {
      preset: ['default', {
        discardComments: {
          removeAll: true
        },
        normalizeWhitespace: false
      }]
    } : false,
    'postcss-pxtorem': {
      rootValue: 16,
      unitPrecision: 5,
      propList: ['*'],
      selectorBlackList: [],
      replace: true,
      mediaQuery: false,
      minPixelValue: 0
    },
    '@fullhuman/postcss-purgecss': process.env.NODE_ENV === 'production' ? {
      content: [
        './includes/**/*.php',
        './admin/**/*.php',
        './*.php',
        './assets/**/*.js'
      ],
      defaultExtractor: content => content.match(/[\w-/:]+(?<!:)/g) || [],
      safelist: {
        standard: [
          /^[a-z]*-?grid/,
          /^[a-z]*-?gap/,
          /^[a-z]*-?cols/,
          /^[a-z]*-?rows/,
          /^[a-z]*-?auto/,
          /^[a-z]*-?span/,
          /^[a-z]*-?start/,
          /^[a-z]*-?end/,
          'html',
          'body',
          'img',
          'a',
          /^data-/,
          /^aria-/,
          'active',
          'disabled',
          'required',
          'invalid'
        ],
        deep: [
          /^modal/,
          /^toast/,
          /^dropdown/,
          /^tooltip/,
          /^popover/,
          /^alert/,
          /^badge/,
          /^btn/,
          /^form/,
          /^nav/,
          /^table/,
          /^card/
        ],
        greedy: [
          /^fa-/,
          /^text-/,
          /^bg-/,
          /^border-/,
          /^rounded-/,
          /^shadow-/,
          /^p-/,
          /^m-/,
          /^w-/,
          /^h-/,
          /^flex-/,
          /^grid-/,
          /^gap-/,
          /^space-/,
          /^transition-/,
          /^transform-/,
          /^opacity-/,
          /^rotate-/,
          /^scale-/,
          /^translate-/,
          /^skew-/,
          /^cursor-/,
          /^select-/,
          /^resize-/,
          /^list-/,
          /^overflow-/,
          /^whitespace-/,
          /^break-/,
          /^tracking-/,
          /^leading-/,
          /^align-/,
          /^justify-/,
          /^place-/,
          /^order-/,
          /^object-/,
          /^z-/,
          /^divide-/,
          /^ring-/,
          /^filter-/,
          /^backdrop-/
        ]
      }
    } : false
  }
};