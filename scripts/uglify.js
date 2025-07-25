const glob = require("glob");
const { execSync } = require("child_process");

const files = glob.sync("assets/js/**/*.js").filter(f => !f.endsWith(".min.js"));

files.forEach((file) => {
  const outFile = file.replace(/\.js$/, ".min.js");
  console.log(`Uglifying: ${file} → ${outFile}`);
  execSync(`npx uglifyjs "${file}" -c -m -o "${outFile}"`);
});
