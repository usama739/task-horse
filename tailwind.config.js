module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
     './resources/**/*.ts', 
     './public/**/*.html',
    './storage/framework/views/*.php',
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './node_modules/flowbite/**/*.js'
  ],
  theme: {
    extend: {
      backgroundOpacity: {
         '10': '0.1',
         '20': '0.2',
         '95': '0.95',
      }
    },
  },
  plugins: [
    require('flowbite/plugin'),
     require('@tailwindcss/forms'),
    // Add this if not present:
  ],
}
