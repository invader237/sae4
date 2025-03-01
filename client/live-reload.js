const browserSync = require("browser-sync").create();

browserSync.init({
    proxy: "localhost", 
    files: ["./**/*.html", "./**/*.css", "./**/*.js"], 
    port: 3000,
    reloadDelay: 100,
});
