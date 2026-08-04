import postcssOklabFunction from '@csstools/postcss-oklab-function';

// Compatibility pipeline for old browsers (Windows 7 kiosks, Chrome ~88-109).
// Tailwind v4 targets Chrome 111+ and ignores build.target/browserslist, so
// its output must be downleveled here:
//   - @layer          -> unwrapped in source order (Chrome <99 ignores all
//                        layered CSS)
//   - oklch()/oklab() -> plain rgb() (Chrome <111)
//   - gradient `in oklab` interpolation -> stripped, restored behind @supports
//                        (Chrome <111)
//   - translate/rotate/scale properties -> transform: fallback (Chrome <104)

// Re-creates the @media/@supports ancestry of `source` around `node`, so a
// generated fallback stays scoped to the same conditions as the rule it mirrors
// (without this, a `lg:` or `hover:` variant leaks to every viewport/state).
const withAncestry = (source, node, AtRule) => {
    let wrapped = node;
    for (let parent = source.parent; parent && parent.type === 'atrule'; parent = parent.parent) {
        const clone = new AtRule({ name: parent.name, params: parent.params });
        clone.append(wrapped);
        wrapped = clone;
    }
    return wrapped;
};

// Flatten @layer for Chrome <99, which ignores layered CSS entirely.
// Unwrap in place rather than emulating layer priority with specificity hacks
// (`:not(#\#)` chains): Tailwind emits theme -> base -> components -> utilities
// in source order, and its utilities are class selectors while preflight is
// element/universal, so plain unwrapping already yields the intended cascade.
// It also keeps each page's own <style> block winning over preflight — those
// blocks are unlayered and load after app.css, which is how real @layer behaves.
const flattenCascadeLayers = () => ({
    postcssPlugin: 'flatten-cascade-layers',
    OnceExit(root) {
        let found;
        do {
            found = false;
            root.walkAtRules('layer', (atRule) => {
                found = true;
                // A bare `@layer a, b, c;` order declaration has no body.
                if (atRule.nodes) atRule.replaceWith(atRule.nodes);
                else atRule.remove();
            });
        } while (found);
    },
});
flattenCascadeLayers.postcss = true;

// Chrome <111 cannot parse a color-interpolation method inside a gradient
// (`linear-gradient(to right in oklab, …)`), and Tailwind v4 puts one into
// --tw-gradient-position for every gradient utility. There the substituted
// value is invalid at computed-value time, so background-image resolves to
// `none` and every gradient button/icon/banner renders blank. Strip the hint so
// old browsers interpolate in sRGB, and restore Tailwind's original value
// behind @supports so modern browsers are unaffected.
const INTERPOLATION_HINT =
    /\s+in\s+(?:oklab|oklch|lab|lch|srgb-linear|srgb|hsl|hwb|xyz-d50|xyz-d65|xyz)\b(?:\s+(?:shorter|longer|increasing|decreasing)\s+hue)?/gi;

const gradientInterpolationFallback = () => ({
    postcssPlugin: 'gradient-interpolation-fallback',
    OnceExit(root, { AtRule, Rule }) {
        const upgrades = [];
        root.walkRules((rule) => {
            const modern = [];
            rule.walkDecls((decl) => {
                if (!/gradient/i.test(decl.prop) && !/-gradient\(/i.test(decl.value)) return;
                // color-mix(in oklab, …) carries the same `in <space>` syntax but
                // is already @supports-guarded by Tailwind — never rewrite it.
                if (decl.value.includes('color-mix(')) return;
                const stripped = decl.value.replace(INTERPOLATION_HINT, '');
                if (stripped === decl.value) return;
                modern.push({ prop: decl.prop, value: decl.value });
                decl.value = stripped;
            });
            if (modern.length === 0) return;

            const upgrade = new Rule({ selector: rule.selector });
            modern.forEach((d) => upgrade.append(d));
            upgrades.push(withAncestry(rule, upgrade, AtRule));
        });

        if (upgrades.length > 0) {
            const supports = new AtRule({
                name: 'supports',
                params: '(background-image: linear-gradient(in oklab, red, red))',
            });
            upgrades.forEach((node) => supports.append(node));
            root.append(supports);
        }
    },
});
gradientInterpolationFallback.postcss = true;

// Emits a `@supports not (translate: none)` block containing a transform:
// equivalent for every rule that uses the individual transform properties.
// Old browsers drop the unknown translate/rotate/scale declarations and apply
// the fallback; modern browsers fail the @supports test and skip it.
const individualTransformFallback = () => ({
    postcssPlugin: 'individual-transform-fallback',
    OnceExit(root, { AtRule, Rule }) {
        const pctToNumber = (v) =>
            /^-?\d*\.?\d+%$/.test(v.trim()) ? String(parseFloat(v) / 100) : v.trim();

        const fallbacks = [];
        root.walkRules((rule) => {
            const decls = {};
            rule.walkDecls((d) => {
                decls[d.prop] = d.value;
            });
            if (!('translate' in decls) && !('rotate' in decls) && !('scale' in decls)) return;

            const parts = [];
            if ('translate' in decls) {
                if (decls.translate.includes('var(')) {
                    const tx = decls['--tw-translate-x'] ?? 'var(--tw-translate-x, 0)';
                    const ty = decls['--tw-translate-y'] ?? 'var(--tw-translate-y, 0)';
                    parts.push(`translate(${tx.trim()}, ${ty.trim()})`);
                } else {
                    parts.push(`translate(${decls.translate.trim().split(/\s+/).join(', ')})`);
                }
            }
            if ('rotate' in decls) {
                parts.push(`rotate(${decls.rotate === 'none' ? '0deg' : decls.rotate.trim()})`);
            }
            if ('scale' in decls) {
                if (decls.scale.includes('var(')) {
                    const sx = decls['--tw-scale-x'] ? pctToNumber(decls['--tw-scale-x']) : 'var(--tw-scale-x, 1)';
                    const sy = decls['--tw-scale-y'] ? pctToNumber(decls['--tw-scale-y']) : 'var(--tw-scale-y, 1)';
                    parts.push(`scale(${sx}, ${sy})`);
                } else {
                    parts.push(`scale(${decls.scale.trim().split(/\s+/).map(pctToNumber).join(', ')})`);
                }
            }

            const fallback = new Rule({ selector: rule.selector });
            fallback.append({ prop: 'transform', value: parts.join(' ') });
            fallbacks.push(withAncestry(rule, fallback, AtRule));
        });

        if (fallbacks.length > 0) {
            const supports = new AtRule({ name: 'supports', params: 'not (translate: none)' });
            fallbacks.forEach((r) => supports.append(r));
            root.append(supports);
        }
    },
});
individualTransformFallback.postcss = true;

export default {
    plugins: [
        flattenCascadeLayers(),
        postcssOklabFunction({
            preserve: false,
            subFeatures: { displayP3: false },
            // Safe only because preserve is false — output is a single
            // plain rgb() declaration per custom property.
            enableProgressiveCustomProperties: false,
        }),
        gradientInterpolationFallback(),
        individualTransformFallback(),
    ],
};
