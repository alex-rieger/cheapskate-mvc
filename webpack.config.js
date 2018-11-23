let Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/cheapskate-mvc/public/build')
    .addEntry('scripts', './assets/js/main.js')
    .addStyleEntry('styles', './assets/css/main.css')

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    //.enableSassLoader()
    //.enableTypeScriptLoader()
;

module.exports = Encore.getWebpackConfig();