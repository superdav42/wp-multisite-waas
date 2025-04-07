module.exports = {
  extends: [
    'stylelint-config-standard-scss',
  ],
  rules: {
    // WordPress-specific CSS rules
    'selector-class-pattern': null, // Allow WordPress class naming convention
    'no-descending-specificity': null,
    'at-rule-no-unknown': [
      true,
      {
        ignoreAtRules: [
          'extend',
          'at-root',
          'debug',
          'warn',
          'error',
          'if',
          'else',
          'for',
          'each',
          'while',
          'mixin',
          'include',
          'content',
          'return',
          'function',
          'tailwind',
          'apply',
          'responsive',
          'variants',
          'screen',
        ],
      },
    ],
    // Other customizations
    'string-quotes': 'single',
    'declaration-block-trailing-semicolon': 'always',
  },
  ignoreFiles: [
    'dist/**/*.css',
    'node_modules/**/*',
    'vendor/**/*',
  ],
}; 