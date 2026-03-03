#!/usr/bin/env node
/**
 * Build SVG sprite symbol file from individual SVG files.
 * Output: dist/svg/sprite.symbol.svg
 */
import { readFileSync, readdirSync, writeFileSync, mkdirSync } from 'fs';
import { join, basename } from 'path';

const srcDir  = new URL('../assets/svgs/sprites', import.meta.url).pathname;
const outDir  = new URL('../dist/svg', import.meta.url).pathname;
const outFile = join(outDir, 'sprite.symbol.svg');

mkdirSync(outDir, { recursive: true });

const symbols = readdirSync(srcDir)
  .filter(f => f.endsWith('.svg'))
  .map(file => {
    const id  = basename(file, '.svg');
    let   src = readFileSync(join(srcDir, file), 'utf8');

    // Extract viewBox
    const vbMatch = src.match(/viewBox="([^"]+)"/);
    const viewBox = vbMatch ? ` viewBox="${vbMatch[1]}"` : '';

    // Strip XML declaration, DOCTYPE, comments, and outer <svg ...> wrapper tags
    src = src
      .replace(/<\?xml[^>]*\?>\s*/g, '')
      .replace(/<!DOCTYPE[^>]*>\s*/gi, '')
      .replace(/<!--[\s\S]*?-->\s*/g, '')
      .replace(/<svg[^>]*>/, '')
      .replace(/<\/svg>\s*$/, '')
      .trim();

    return `  <symbol id="${id}"${viewBox}>\n    ${src}\n  </symbol>`;
  });

const sprite = `<svg xmlns="http://www.w3.org/2000/svg" style="display:none">\n${symbols.join('\n')}\n</svg>\n`;

writeFileSync(outFile, sprite, 'utf8');
console.log(`Built sprite: ${outFile} (${symbols.length} symbols)`);
