const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');
const pkg = require('../package.json');

const isWindows = process.platform === 'win32';
const wpCliPath = isWindows
  ? 'vendor\\wp-cli\\wp-cli\\bin\\wp.bat'
  : 'vendor/wp-cli/wp-cli/bin/wp';

const potFile = path.join(__dirname, '..', 'lang', `${pkg.name}.pot`);
const gitPotFile = potFile.split(path.sep).join('/'); // cross-platform for git

try {
  console.log('Generating POT file...');
  execSync(`${wpCliPath} i18n make-pot ./ ${potFile} --slug=${pkg.name} --exclude=node_modules,tests,docs,assets/js/lib`, { stdio: 'inherit' });

  if (!fs.existsSync(potFile)) {
    console.error('POT file was not created!');
    process.exit(1);
  }

  // Read POT file and ignore POT-Creation-Date
  const potContents = fs.readFileSync(potFile, 'utf8')
    .split('\n')
    .filter(line => !line.startsWith('POT-Creation-Date:'))
    .join('\n');

  // Write a temporary file for diff comparison
  const tmpFile = potFile + '.tmp';
  fs.writeFileSync(tmpFile, potContents.replace(/\r\n/g, '\n'), 'utf8');

  // Check for meaningful changes ignoring POT-Creation-Date
  const diff = execSync(`git diff --no-index --name-only ${tmpFile} ${gitPotFile}`).toString().trim();
  fs.unlinkSync(tmpFile); // clean up

  if (diff) {
    console.log('Meaningful changes detected. Staging POT file...');
    execSync(`git add ${gitPotFile}`);

    // Check if only POT file changed
    const staged = execSync('git diff --cached --name-only').toString().trim().split('\n');
    if (staged.length === 1 && staged[0] === gitPotFile) {
      console.log('Only POT file changed. Commit aborted. Please include other changes.');
      process.exit(1); // prevents commit
    }
  } else {
    console.log('No meaningful changes in POT file.');
  }

} catch (err) {
  console.error('Error generating POT:', err.message);
  process.exit(1);
}
