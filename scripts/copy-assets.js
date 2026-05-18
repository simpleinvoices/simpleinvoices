const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
const vendorDir = path.join(root, 'templates', 'default', 'vendor');

function copy(src, dest) {
    fs.mkdirSync(path.dirname(dest), { recursive: true });
    fs.copyFileSync(src, dest);
    console.log('  COPY ' + path.relative(root, dest));
}

function copyDir(srcDir, destDir, filter) {
    fs.mkdirSync(destDir, { recursive: true });
    for (const entry of fs.readdirSync(srcDir, { recursive: true })) {
        const srcPath = path.join(srcDir, entry);
        if (fs.statSync(srcPath).isFile()) {
            if (filter && !filter(srcPath)) continue;
            const destPath = path.join(destDir, entry);
            fs.mkdirSync(path.dirname(destPath), { recursive: true });
            fs.copyFileSync(srcPath, destPath);
        }
    }
    console.log('  COPY ' + path.relative(root, srcDir) + '/ -> ' + path.relative(root, destDir));
}

console.log('Cleaning vendor directory...');
fs.rmSync(vendorDir, { recursive: true, force: true });

console.log('\nCopying assets:');

// @tabler/core v1.4.0
console.log('\n[@tabler/core]');
copy(
    path.join(root, 'node_modules', '@tabler', 'core', 'dist', 'css', 'tabler.min.css'),
    path.join(vendorDir, 'tabler-core', 'tabler.min.css')
);
copy(
    path.join(root, 'node_modules', '@tabler', 'core', 'dist', 'js', 'tabler.min.js'),
    path.join(vendorDir, 'tabler-core', 'tabler.min.js')
);

// @tabler/icons-webfont v3.44.0 - only copy fonts referenced by the CSS
console.log('\n[@tabler/icons-webfont]');
copy(
    path.join(root, 'node_modules', '@tabler', 'icons-webfont', 'dist', 'tabler-icons.min.css'),
    path.join(vendorDir, 'tabler-icons', 'tabler-icons.min.css')
);
// CSS references: tabler-icons.woff2, tabler-icons.woff, tabler-icons.ttf
for (const fontFile of ['tabler-icons.woff2', 'tabler-icons.woff', 'tabler-icons.ttf']) {
    copy(
        path.join(root, 'node_modules', '@tabler', 'icons-webfont', 'dist', 'fonts', fontFile),
        path.join(vendorDir, 'tabler-icons', 'fonts', fontFile)
    );
}

// tom-select v2.6.1
console.log('\n[tom-select]');
copy(
    path.join(root, 'node_modules', 'tom-select', 'dist', 'css', 'tom-select.bootstrap5.min.css'),
    path.join(vendorDir, 'tom-select', 'tom-select.bootstrap5.min.css')
);
copy(
    path.join(root, 'node_modules', 'tom-select', 'dist', 'js', 'tom-select.complete.min.js'),
    path.join(vendorDir, 'tom-select', 'tom-select.complete.min.js')
);

// hugerte v1.0.10 (flat package, no dist/ dir)
console.log('\n[hugerte]');
copy(
    path.join(root, 'node_modules', 'hugerte', 'hugerte.min.js'),
    path.join(vendorDir, 'hugerte', 'hugerte.min.js')
);
for (const subdir of ['skins', 'icons', 'models', 'plugins', 'themes']) {
    const src = path.join(root, 'node_modules', 'hugerte', subdir);
    if (fs.existsSync(src)) {
        copyDir(src, path.join(vendorDir, 'hugerte', subdir));
    }
}

// litepicker v2.0.12
console.log('\n[litepicker]');
copy(
    path.join(root, 'node_modules', 'litepicker', 'dist', 'litepicker.js'),
    path.join(vendorDir, 'litepicker', 'litepicker.js')
);

// apexcharts v5.12.0
console.log('\n[apexcharts]');
copy(
    path.join(root, 'node_modules', 'apexcharts', 'dist', 'apexcharts.min.js'),
    path.join(vendorDir, 'apexcharts', 'apexcharts.min.js')
);

// @fontsource/inter v5.2.8 - combine needed weights, copy only latin woff2/woff
console.log('\n[@fontsource/inter]');
const weights = ['300', '400', '500', '600', '700'];
let combinedCss = '/* Inter font (self-hosted) - combined weights: ' + weights.join(', ') + ' */\n\n';
for (const w of weights) {
    const cssFile = path.join(root, 'node_modules', '@fontsource', 'inter', 'latin-' + w + '.css');
    if (fs.existsSync(cssFile)) {
        combinedCss += fs.readFileSync(cssFile, 'utf8').trim() + '\n\n';
    }
}
fs.mkdirSync(path.join(vendorDir, 'inter'), { recursive: true });
fs.writeFileSync(path.join(vendorDir, 'inter', 'inter.css'), combinedCss);
console.log('  WRITE templates/default/vendor/inter/inter.css');
// Only copy latin woff2/woff files for the needed weights
const interFilter = (f) => {
    const name = path.basename(f);
    if (!name.startsWith('inter-latin-')) return false;
    const weight = name.replace('inter-latin-', '').replace('-normal.woff2', '').replace('-normal.woff', '');
    return weights.includes(weight);
};
copyDir(
    path.join(root, 'node_modules', '@fontsource', 'inter', 'files'),
    path.join(vendorDir, 'inter', 'files'),
    interFilter
);

// docsify v4.13.1 - documentation SPA
console.log('\n[docsify]');
copy(
    path.join(root, 'node_modules', 'docsify', 'lib', 'docsify.min.js'),
    path.join(vendorDir, 'docsify', 'docsify.min.js')
);
copy(
    path.join(root, 'node_modules', 'docsify', 'lib', 'themes', 'vue.css'),
    path.join(vendorDir, 'docsify', 'themes', 'vue.css')
);
copy(
    path.join(root, 'node_modules', 'docsify', 'lib', 'plugins', 'search.min.js'),
    path.join(vendorDir, 'docsify', 'plugins', 'search.min.js')
);

console.log('\nDone. Vendor assets written to templates/default/vendor/');
