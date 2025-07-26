const { execSync } = require('child_process');
const pkg = require('../package.json');

const archiveFile = `${pkg.name || 'archive'}`;

try {
  execSync(`composer archive --format=zip --file=${archiveFile}`, {
    stdio: 'inherit',
  });
  console.log(`✅ Created archive: ${archiveFile}`);
} catch (error) {
  console.error('❌ Failed to create archive:', error.message);
  process.exit(1);
}
