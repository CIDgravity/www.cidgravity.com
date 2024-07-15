### Requirements

To use this template, your computer needs:

- Node.js (>= 16.x.x) is used to run the build processes. https://nodejs.org/en/download/
- Test: run `node -v` in the terminal

## Usage

1. enable pnpm with corepack

```bash
corepack enable
corepack prepare pnpm@latest --activate
```

> _corepack is installed with Node.js from **v16.13.x**, if your version is below, install it with: `npm install -g corepack`, or upgrade Node.js_ 

2. Install depedencies

```bash
pnpm i
```

3. To start development server

```bash
pnpm dev
```