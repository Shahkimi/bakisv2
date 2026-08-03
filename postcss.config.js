import postcssCascadeLayers from '@csstools/postcss-cascade-layers';
import postcssOklabFunction from '@csstools/postcss-oklab-function';

// Compatibility pipeline for old browsers (Windows 7 kiosks, Chrome ~88-109).
// Tailwind v4 targets Chrome 111+ and ignores build.target/browserslist, so
// its output must be downleveled here:
//   - @layer          -> flattened (Chrome <99 ignores all layered CSS)
//   - oklch()/oklab() -> plain rgb() (Chrome <111)
//   - translate/rotate/scale properties -> transform: fallback (Chrome <104)

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
            fallbacks.push(fallback);
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
        postcssCascadeLayers(),
        postcssOklabFunction({
            preserve: false,
            subFeatures: { displayP3: false },
            // Safe only because preserve is false — output is a single
            // plain rgb() declaration per custom property.
            enableProgressiveCustomProperties: false,
        }),
        individualTransformFallback(),
    ],
};
