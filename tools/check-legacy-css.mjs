// Guards the old-browser CSS pipeline in postcss.config.js (Chrome ~109 kiosks).
// Runs after `vite build`; fails the build if the emitted bundle regains a
// construct those browsers cannot parse, or regains the `:not(#\#)` specificity
// hack that lets Tailwind's preflight override each page's own <style> block.

import { readdirSync, readFileSync } from 'node:fs';
import { join } from 'node:path';

const ASSETS_DIR = 'public/build/assets';

const FORBIDDEN = [
    { pattern: '@layer', why: '@layer flattening stopped working (Chrome <99 ignores all layered CSS)' },
    { pattern: 'oklch(', why: 'oklch() downleveling stopped working (Chrome <111)' },
    { pattern: 'oklab(', why: 'oklab() downleveling stopped working (Chrome <111)' },
    { pattern: ':not(#\\#)', why: 'a specificity-hacking layer polyfill crept back in; it makes Tailwind preflight override per-page <style> blocks' },
];

const REQUIRED = [
    { pattern: '@supports not (translate: none)', why: 'the individual-transform fallback block is missing (Chrome <104)' },
];

// A gradient color-interpolation hint (`linear-gradient(to right in oklab, …)`)
// makes Chrome <111 drop the whole background-image. It is only allowed inside
// color-mix() — which Tailwind @supports-guards itself — or inside the
// modern-only @supports block the pipeline emits.
// Matched loosely because the minifier drops whitespace inside the condition.
const GRADIENT_UPGRADE_AT_RULE = /@supports\s*\(\s*background-image:\s*linear-gradient\(in oklab\s*,\s*red\s*,\s*red\)\s*\)/;
const INTERPOLATION_HINT =
    /\bin (?:oklab|oklch|lab|lch|srgb-linear|srgb|hsl|hwb|xyz-d50|xyz-d65|xyz)\b/;

function stripGuardedInterpolation(css) {
    let out = css.replace(/color-mix\(in [a-z0-9-]+/gi, 'color-mix(');
    for (;;) {
        const match = out.match(GRADIENT_UPGRADE_AT_RULE);
        if (!match) return out;
        const start = match.index;
        const open = out.indexOf('{', start);
        if (open === -1) return out;
        let depth = 0;
        let i = open;
        for (; i < out.length; i++) {
            if (out[i] === '{') depth++;
            else if (out[i] === '}' && --depth === 0) {
                i++;
                break;
            }
        }
        out = out.slice(0, start) + out.slice(i);
    }
}

let files;
try {
    files = readdirSync(ASSETS_DIR).filter((f) => f.endsWith('.css'));
} catch {
    console.error(`check-legacy-css: cannot read ${ASSETS_DIR} — did vite build run?`);
    process.exit(1);
}

if (files.length === 0) {
    console.error(`check-legacy-css: no CSS bundles found in ${ASSETS_DIR}`);
    process.exit(1);
}

const failures = [];

for (const file of files) {
    const css = readFileSync(join(ASSETS_DIR, file), 'utf8');

    for (const { pattern, why } of FORBIDDEN) {
        const at = css.indexOf(pattern);
        if (at === -1) continue;
        const snippet = css.slice(Math.max(0, at - 60), at + 120).replace(/\s+/g, ' ');
        failures.push(`${file}: found "${pattern}" — ${why}\n    …${snippet}…`);
    }

    for (const { pattern, why } of REQUIRED) {
        if (!css.includes(pattern)) {
            failures.push(`${file}: missing "${pattern}" — ${why}`);
        }
    }

    const residual = stripGuardedInterpolation(css);
    const hint = residual.match(INTERPOLATION_HINT);
    if (hint) {
        const snippet = residual.slice(Math.max(0, hint.index - 60), hint.index + 120).replace(/\s+/g, ' ');
        failures.push(
            `${file}: unguarded gradient interpolation "${hint[0]}" — Chrome <111 drops the whole background-image\n    …${snippet}…`,
        );
    }
}

if (failures.length > 0) {
    console.error('check-legacy-css: old-browser CSS pipeline is broken.\n');
    failures.forEach((f) => console.error(`  ${f}\n`));
    console.error('See postcss.config.js.');
    process.exit(1);
}

console.log(`check-legacy-css: OK (${files.length} bundle${files.length === 1 ? '' : 's'} checked)`);
