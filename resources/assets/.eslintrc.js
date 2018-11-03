
module.exports = {
  "root": true,
  "env": {
    "browser": true,
    "commonjs": true,
    "es6": true,
    "jquery": true,
    "node": true
  },
  extends: [
    "eslint:recommended",
    "plugin:vue/strongly-recommended"
  ],
  rules: {
    'no-unused-vars': 'off',
    'no-console': 'off',
    'vue/no-unused-vars': 'off',
    'vue/require-prop-types': 'off'
  }
}