### Requirements

To use this template, your computer needs:

- Node.js (>= 16.x.x) is used to run the build processes. https://nodejs.org/en/download/
- Test: run `node -v` in the terminal

## Usage

1. Install depedencies

```bash
npm install
```

2. To start development server (running on port 3000 with live reload)

```bash
npm run dev
```

3. To build for production

```bash
npm run build
```

## Deployment

The website is already built, and the compiled filed are in the `dist` folder.
For production deployment, use the files in the `dist` folder. You can, for example, copy the contents to `/var/www/html` or any other directory served by your web server.
