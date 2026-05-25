const path = require('path')

module.exports = {
    config: path.resolve(__dirname, '../config'),
    dist: {
        dist: path.resolve(__dirname, '../Assets'),
        js: path.resolve(__dirname, '../Assets/js')
    },
    src: {
        fontAwesomeFree: path.resolve(__dirname, '../node_modules/@fortawesome/fontawesome-free/svgs'),
        fontAwesomePro: path.resolve(__dirname, '../node_modules/@fortawesome/fontawesome-pro/svgs'),
        fontAwesomeFreeFonts: path.resolve(__dirname, '../node_modules/@fortawesome/fontawesome-free/webfonts'),
        fontAwesomeProFonts: path.resolve(__dirname, '../node_modules/@fortawesome/fontawesome-pro/webfonts'),
        fonts: path.resolve(__dirname, '../Resources/fonts'),
        svg: path.resolve(__dirname, '../Resources/svg'),
        data: path.resolve(__dirname, '../Resources/data'),
        js: path.resolve(__dirname, '../Resources/js'),
        jsConfig: path.resolve(__dirname, '../Resources/js/config'),
        languages: path.resolve(__dirname, '../Resources/languages'),
        scss: path.resolve(__dirname, '../Resources/scss'),
        resources: path.resolve(__dirname, '../Resources')
    }
}
