import fs from 'node:fs/promises';
import path from 'node:path';

const basePath = process.env.PAGES_BASE || '/';
const source = path.resolve('public');
const destination = path.resolve('_site');

async function copyTree(from, to) {
  await fs.mkdir(to, { recursive: true });
  for (const entry of await fs.readdir(from, { withFileTypes: true })) {
    const fromPath = path.join(from, entry.name);
    const toPath = path.join(to, entry.name);
    if (entry.isDirectory()) await copyTree(fromPath, toPath);
    else await fs.copyFile(fromPath, toPath);
  }
}

await fs.rm(destination, { recursive: true, force: true });
await copyTree(source, destination);

const rootFiles = ['index.html', 'compare.html', 'styles.css', 'app.js', 'artworks.js'];
for (const filename of rootFiles) {
  const file = path.join(destination, filename);
  let content = await fs.readFile(file, 'utf8');
  if (filename.endsWith('.html')) {
    content = content.replaceAll('href="/', `href="${basePath}`).replaceAll('src="/', `src="${basePath}`);
  }
  if (filename === 'styles.css') content = content.replaceAll("url('/", `url('${basePath}`);
  if (filename === 'app.js') {
    const escapedBase = basePath.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    const routePattern = `new RegExp('^${escapedBase.replace(/\/$/, '')}/?')`;
    content = content.replace(
      "location.pathname.replace(/^\\//, '').split('/')[0] || 'home'",
      `location.pathname.replace(${routePattern}, '').replace(/^\\//, '').split('/')[0] || 'home'`,
    );
    content = content.replaceAll("'/api/", `'${basePath}api/`);
  }
  await fs.writeFile(file, content);
}

// GitHub Pages does not run the Express fallback, so provide directory entry points.
for (const page of ['about', 'artworks', 'contact']) {
  const directory = path.join(destination, page);
  await fs.mkdir(directory, { recursive: true });
  await fs.copyFile(path.join(destination, 'index.html'), path.join(directory, 'index.html'));
}
await fs.writeFile(path.join(destination, '.nojekyll'), '');
