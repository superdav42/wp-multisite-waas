# GitHub Workflows

This directory contains GitHub Actions workflows for automating various tasks in the repository.

## Sync Wiki Workflow

The `sync-wiki.yml` workflow automatically syncs the content of the `.wiki` directory to the GitHub wiki whenever changes are pushed to the main branch.

### How it works

1. When changes are pushed to the `.wiki` directory on the main branch, the workflow is triggered
2. The workflow clones the GitHub wiki repository
3. It removes all existing files from the wiki repository (except the `.git` directory)
4. It copies all files from the `.wiki` directory to the wiki repository
5. It commits and pushes the changes to the wiki repository

### Benefits

- Documentation is automatically synced to the GitHub wiki
- No manual steps required to update the wiki
- Documentation changes can be reviewed alongside code changes
- Documentation is version-controlled with the code

### Requirements

- The GitHub wiki must be enabled for the repository
- The workflow uses the `GITHUB_TOKEN` secret, which is automatically provided by GitHub Actions
