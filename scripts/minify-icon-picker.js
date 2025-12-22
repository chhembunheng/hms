#!/usr/bin/env node

/**
 * Asset Minification Script
 * Minifies JavaScript and CSS files
 *
 * Usage:
 *   node scripts/minify-icon-picker.js <path>
 *   npm run minify:assets -- <path>
 *
 * Examples:
 *   node scripts/minify-icon-picker.js /path/to/component
 *   node scripts/minify-icon-picker.js public/assets/js/vendor/pickers/icon-picker
 */

import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Get path from CLI arguments
let componentPath = process.argv[2];

if (!componentPath) {
    // Default to icon-picker if no path provided
    componentPath = 'public/assets/js/vendor/pickers/icon-picker';
}

// Resolve to absolute path
const BASE_PATH = path.isAbsolute(componentPath)
    ? componentPath
    : path.join(__dirname, '..', componentPath);

/**
 * Simple CSS minifier
 * Removes comments, whitespace, and unnecessary characters
 */
function minifyCSS(css) {
    return css
        // Remove comments
        .replace(/\/\*[\s\S]*?\*\//g, '')
        // Remove newlines and multiple spaces
        .replace(/\n/g, ' ')
        .replace(/\s+/g, ' ')
        // Remove spaces around special characters
        .replace(/\s*([{}:;,])\s*/g, '$1')
        // Remove space before closing brace in selectors
        .replace(/\s*}\s*/g, '}')
        // Clean up multiple spaces in attribute selectors
        .replace(/\[\s+/g, '[')
        .replace(/\s+\]/g, ']')
        .trim();
}

/**
 * Simple JavaScript minifier
 * Removes comments, unnecessary whitespace, but preserves functionality
 */
function minifyJS(js) {
    return js
        // Remove single-line comments
        .replace(/\/\/.*?$/gm, '')
        // Remove multi-line comments
        .replace(/\/\*[\s\S]*?\*\//g, '')
        // Remove newlines (but keep semicolons for safety)
        .replace(/\n/g, ' ')
        // Remove multiple spaces
        .replace(/\s+/g, ' ')
        // Remove spaces around operators and special characters
        .replace(/\s*([{}()[\],;:=+\-*/<>!&|?])\s*/g, '$1')
        // Fix space after keywords (for, if, while, etc.)
        .replace(/\b(if|for|while|switch|function|return|var|let|const)\(/g, '$1 (')
        // Clean up
        .trim();
}

/**
 * Minify a file
 */
function minifyFile(inputPath, outputPath, minifyFn) {
    try {
        const content = fs.readFileSync(inputPath, 'utf8');
        const minified = minifyFn(content);
        fs.writeFileSync(outputPath, minified, 'utf8');

        const originalSize = Buffer.byteLength(content, 'utf8');
        const minifiedSize = Buffer.byteLength(minified, 'utf8');
        const reduction = ((1 - minifiedSize / originalSize) * 100).toFixed(2);

        console.log(`✓ Minified: ${path.basename(inputPath)}`);
        console.log(`  Original: ${(originalSize / 1024).toFixed(2)} KB`);
        console.log(`  Minified: ${(minifiedSize / 1024).toFixed(2)} KB`);
        console.log(`  Reduction: ${reduction}%\n`);

        return true;
    } catch (error) {
        console.error(`✗ Error minifying ${inputPath}:`, error.message);
        return false;
    }
}

/**
 * Main function
 */
function main() {

    // Check if path exists
    if (!fs.existsSync(BASE_PATH)) {
        console.error(`✗ Path not found: ${BASE_PATH}`);
        process.exit(1);
    }

    const files = [
        {
            input: path.join(BASE_PATH, 'js', path.basename(BASE_PATH) + '.js'),
            output: path.join(BASE_PATH, 'js', path.basename(BASE_PATH) + '.min.js'),
            minifier: minifyJS,
            type: 'JavaScript'
        },
        {
            input: path.join(BASE_PATH, 'css', path.basename(BASE_PATH) + '.css'),
            output: path.join(BASE_PATH, 'css', path.basename(BASE_PATH) + '.min.css'),
            minifier: minifyCSS,
            type: 'CSS'
        }
    ];

    let successCount = 0;

    files.forEach(({ input, output, minifier, type }) => {
        console.log(`📦 Processing ${type}...`);

        if (!fs.existsSync(input)) {
            console.warn(`⚠️  Input file not found: ${path.basename(input)}`);
            console.warn(`   Expected at: ${input}\n`);
            return;
        }

        if (minifyFile(input, output, minifier)) {
            successCount++;
        }
    });

    console.log(`\n✨ Complete! ${successCount}/${files.length} files minified successfully.`);

    if (successCount > 0) {
        process.exit(0);
    } else {
        process.exit(1);
    }
}

// Run the script
main();
