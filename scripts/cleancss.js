const glob = require("glob");
const { execSync } = require("child_process");

const files = glob.sync("assets/css/**/*.css").filter(f => !f.endsWith(".min.css"));

files.forEach((file) => {
  const outFile = file.replace(/\.css$/, ".min.css");
  console.log(`Minifying: ${file} → ${outFile}`);
  execSync(`npx cleancss -o "${outFile}" "${file}"`);
});
