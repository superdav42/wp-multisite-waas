const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

// Utility: Copy files
function copyFile(src, dest) {
  fs.mkdirSync(path.dirname(dest), { recursive: true });
  fs.copyFileSync(src, dest);
}

// Utility: Delete folder
function deleteFolder(targetPath) {
  if (fs.existsSync(targetPath)) {
    fs.rmSync(targetPath, { recursive: true, force: true });
  }
}

// Utility: Delete all *.min.js or *.min.css
function cleanMinified(dir, ext) {
  const walk = (dirPath) => {
    fs.readdirSync(dirPath).forEach(file => {
      const fullPath = path.join(dirPath, file);
      if (fs.statSync(fullPath).isDirectory()) {
        walk(fullPath);
      } else if (file.endsWith(`.min.${ext}`)) {
        fs.unlinkSync(fullPath);
      }
    });
  };
  walk(dir);
}

// Utility: Post archive process
function postArchive(packageName) {
  const zipName = `${packageName}.zip`;
  const extractDir = packageName;

  deleteFolder(extractDir);

  // Extract ZIP
  if (process.platform === 'win32') {
    execSync(`powershell -Command "Expand-Archive -Path '${zipName}' -DestinationPath '${extractDir}' -Force"`, {
      stdio: 'inherit',
    });
  } else {
    execSync(`unzip ${zipName} -d ${extractDir}`, { stdio: 'inherit' });
  }

  fs.unlinkSync(zipName);

  // Re-create ZIP
  if (process.platform === 'win32') {
    execSync(`powershell -Command "Compress-Archive -Path '${extractDir}\\*' -DestinationPath '${zipName}' -Force"`, {
      stdio: 'inherit',
    });
  } else {
    execSync(`zip -r -9 ${zipName} ${extractDir}`, { stdio: 'inherit' });
  }

  deleteFolder(extractDir);
}

// Exports
module.exports = {
  copyFile,
  deleteFolder,
  cleanMinified,
  postArchive
};
