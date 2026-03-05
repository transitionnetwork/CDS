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

    // Extract attributes from root <svg> to carry through to <symbol>
    const vbMatch   = src.match(/viewBox="([^"]+)"/);
    const viewBox   = vbMatch ? ` viewBox="${vbMatch[1]}"` : '';

    const fillMatch = src.match(/<svg[^>]*\sfill="([^"]+)"/);
    const fill      = fillMatch ? ` fill="${fillMatch[1]}"` : '';

    // Carry fill-rule / clip-rule from root style="" into symbol style=""
    const styleMatch    = src.match(/<svg[^>]*\sstyle="([^"]+)"/);
    const rootStyle     = styleMatch ? styleMatch[1] : '';
    const svgStyleProps = ['fill-rule', 'clip-rule', 'stroke-linejoin', 'stroke-miterlimit'];
    const keptStyle     = svgStyleProps
      .map(p => { const m = rootStyle.match(new RegExp(`${p}:[^;]+`)); return m ? m[0] : null; })
      .filter(Boolean).join(';');
    const style         = keptStyle ? ` style="${keptStyle}"` : '';

    // Strip XML declaration, DOCTYPE, comments, and outer <svg ...> wrapper tags
    src = src
      .replace(/<\?xml[^>]*\?>\s*/g, '')
      .replace(/<!DOCTYPE[^>]*>\s*/gi, '')
      .replace(/<!--[\s\S]*?-->\s*/g, '')
      .replace(/<svg[^>]*>/, '')
      .replace(/<\/svg>\s*$/, '')
      .trim();

    return `  <symbol id="${id}"${viewBox}${fill}${style}>\n    ${src}\n  </symbol>`;
  });

const sprite = `<svg xmlns="http://www.w3.org/2000/svg" style="display:none">\n${symbols.join('\n')}\n</svg>\n`;

writeFileSync(outFile, sprite, 'utf8');
console.log(`Built sprite: ${outFile} (${symbols.length} symbols)`);
