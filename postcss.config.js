import postcssOklabFunction from '@csstools/postcss-oklab-function';

// Converts Tailwind v4's oklch()/oklab() colors to plain rgb() so the app
// renders on Chrome 109 (last Chrome for Windows 7). Tailwind v4's own
// browser floor is Chrome 111 and ignores build.target/browserslist.
export default {
    plugins: [
        postcssOklabFunction({
            preserve: false,
            subFeatures: { displayP3: false },
            // Safe only because preserve is false — output is a single
            // plain rgb() declaration per custom property.
            enableProgressiveCustomProperties: false,
        }),
    ],
};
