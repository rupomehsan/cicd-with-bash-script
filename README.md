# cicd - Continuous Integration/Continuous Deployment with Bash Script

# Project Deployment & File Tracking Documentation

This project uses custom deployment scripts and file tracking for efficient and safe updates to your server. Below are step-by-step instructions for setup, usage, and troubleshooting.

---
Before deploying to your server, set up SSH key authentication for secure and passwordless access:

1. **Generate an SSH key (if you don't have one):**
   ```bash
   ssh-keygen -t rsa -b 4096 -C "your_email@example.com"
   ```
2. **Copy your public key to the server:**
   ```bash
   ssh-copy-id root@159.12.123.123
   ```

---

## 1. Folder Structure

- `.deploy_tools/` — Contains all deployment scripts, ignore files, and tracking utilities.
  - `.zip_ignore` — Patterns for files/folders to exclude from deployment zip.
  - `deploy_to_vps.sh` — Main deployment script (uploads the whole project as a zip).
  - `deploy_to_vps_via_file_tracking.sh` — Deploys only modified files using file tracking.
  - `deploy_setup_commands.sh` — Runs on the server after deployment (for cache clear, optimization, etc.).
  - `.modify_tracking/` — Stores file tracking data (e.g., `modified.json`).

---

## 2. How to Add/Update Files for Deployment

- Place new or updated files in your project as usual.
- If using file tracking, ensure your tracker (e.g., `tracker.js`) is running to update `.modify_tracking/modified.json`.
- If deploying the whole project, no extra steps are needed.

---

## 3. How to Ignore Files/Folders from Deployment

- Edit `.deploy_tools/.zip_ignore` and add patterns (one per line) for files/folders to exclude.
  - Example:
    ```
    node_modules/*
    node_modules/**/*
    .env
    .git/*
    *.log
    ```
- These patterns are relative to the project root.

---

## 4. How to Deploy (Full Project)

1. **Make the script executable (first time only):**
   ```bash
   chmod +x .deploy_tools/deploy_to_vps.sh
   ```
2. **Run the deployment script from the project root:**
   ```bash
   ./.deploy_tools/deploy_to_vps.sh
   ```
3. The script will:
   - Create a zip archive, excluding files in `.zip_ignore`.
   - Upload the zip to your VPS.
   - SSH into your VPS, unzip, run setup commands, and clean up.

---

## 5. How to Deploy Only Modified Files (File Tracking)

1. **Ensure `jq` is installed:**
   - Ubuntu/Debian: `sudo apt-get install jq`
   - CentOS/RHEL: `sudo yum install jq`
   - macOS: `brew install jq`
2. **Make the script executable (first time only):**
   ```bash
   chmod +x .deploy_tools/deploy_to_vps_via_file_tracking.sh
   ```
3. **Run the script from the project root:**
   ```bash
   ./.deploy_tools/deploy_to_vps_via_file_tracking.sh
   ```
4. The script will:
   - Read `.modify_tracking/modified.json` for changed files.
   - Upload only those files via `rsync`.
   - Run setup commands on the server.

---

## 6. How to Add/Update Server Setup Commands

- Edit `.deploy_tools/deploy_setup_commands.sh` to add any post-deployment steps (e.g., Laravel cache clear, migrations, npm build, etc.).
- Example for Laravel:
  ```bash
  #!/bin/bash
  php artisan o:clear
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```
- Make sure the script is executable:
  ```bash
  chmod +x .deploy_tools/deploy_setup_commands.sh
  ```

---

## 7. Troubleshooting

- **File/folder is not ignored:**
  - Check your `.zip_ignore` patterns and make sure you run the script from the project root.
- **`deploy_setup_commands.sh` not found:**
  - Ensure it exists in `.deploy_tools/` and is not excluded by `.zip_ignore`.
- **`jq: command not found`:**
  - Install `jq` as shown above.
- **Permission denied:**
  - Make sure all scripts are executable (`chmod +x ...`).

---

## 8. Best Practices

- Keep all deployment and tracking tools in `.deploy_tools/` for organization.
- Regularly update `.zip_ignore` as your project grows.
- Test deployment on a staging server before production.

---

## 9. NPM Deployment Commands

You can use npm scripts to run your deployment easily:

- To initialize or update the file tracker (run this at the start of the project, or whenever file tracking is not active):

  ```bash
  npm run deploy:track
  ```

- To deploy the whole project:
  ```bash
  npm run deploy:project
  ```
- To deploy only modified files (via file tracking):
  ```bash
  npm run deploy:files
  ```
