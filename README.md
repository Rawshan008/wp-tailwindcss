## Installation Guide

Clone the repository and run flowing command.
```
yarn
```
Then add this code in your wp-config.php files

```
define('IS_VITE_DEVELOPMENT', true);
```
Then run this command for development mood.

```
yarn dev
```

For Production then run this command.

```
yarn build
```

### Must be false IS_VITE_DEVELOPMENT form wp-config.php when you run production.