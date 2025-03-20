const mix = require('laravel-mix');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your application. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.setPublicPath('public')
   .js('resources/js/app.js', 'public/js')
   .vue()
   .postCss('resources/css/app.css', 'public/css', [
     require('tailwindcss'),
     require('autoprefixer'),
   ])
   .sass('resources/scss/main.scss', 'public/css')
   .copyDirectory('resources/images', 'public/images')
   .copyDirectory('resources/fonts', 'public/fonts');

// Version files in production
if (mix.inProduction()) {
    mix.version();
}

// Configure Webpack
mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
            '@components': path.resolve('resources/js/components'),
            '@pages': path.resolve('resources/js/pages'),
            '@store': path.resolve('resources/js/store'),
            '@utils': path.resolve('resources/js/utils')
        }
    },
    output: {
        chunkFilename: 'js/chunks/[name].[chunkhash].js'
    }
});

// Enable source maps in development
if (!mix.inProduction()) {
    mix.sourceMaps();
}

// Configure BrowserSync
mix.browserSync({
    proxy: 'localhost:8000',
    port: 3000,
    open: false,
    files: [
        'public/**/*.{js,css}',
        'resources/views/**/*.php',
        'app/**/*.php',
        'routes/**/*.php'
    ]
});

// Disable processing URLs in CSS
mix.options({
    processCssUrls: false
});

// Configure notifications
mix.disableNotifications();

// Add custom Webpack rules
mix.webpackConfig({
    module: {
        rules: [
            {
                test: /\.tsx?$/,
                loader: "ts-loader",
                exclude: /node_modules/
            }
        ]
    }
});

// Configure optimization
mix.webpackConfig({
    optimization: {
        splitChunks: {
            chunks: 'all',
            name: false,
            cacheGroups: {
                vendor: {
                    test: /[\\/]node_modules[\\/]/,
                    name(module) {
                        const packageName = module.context.match(
                            /[\\/]node_modules[\\/](.*?)([\\/]|$)/
                        )[1];
                        return `vendor.${packageName.replace('@', '')}`;
                    }
                }
            }
        }
    }
});

// Configure image optimization
mix.webpackConfig({
    module: {
        rules: [
            {
                test: /\.(png|jpe?g|gif|svg)$/,
                use: [
                    {
                        loader: 'image-webpack-loader',
                        options: {
                            mozjpeg: {
                                progressive: true,
                                quality: 65
                            },
                            optipng: {
                                enabled: true
                            },
                            pngquant: {
                                quality: [0.65, 0.90],
                                speed: 4
                            },
                            gifsicle: {
                                interlaced: false
                            },
                            webp: {
                                quality: 75
                            }
                        }
                    }
                ]
            }
        ]
    }
});

// Configure environment-specific settings
if (process.env.NODE_ENV === 'development') {
    mix.webpackConfig({
        devtool: 'inline-source-map'
    });
}

// Configure PWA assets
mix.copyDirectory('resources/pwa', 'public');

// Configure service worker
mix.js('resources/js/service-worker.js', 'public');

// Configure manifest
mix.copy('resources/manifest.json', 'public');

// Configure browserconfig
mix.copy('resources/browserconfig.xml', 'public');

// Configure robots.txt
mix.copy('resources/robots.txt', 'public');

// Configure humans.txt
mix.copy('resources/humans.txt', 'public');

// Configure security.txt
mix.copyDirectory('resources/.well-known', 'public/.well-known');