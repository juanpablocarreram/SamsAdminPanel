Database Setup Guide
This document provides a step-by-step guide to initializing and configuring the database for this project. Please follow the instructions in the exact order listed below.

Prerequisites
MySQL 8.0 or higher installed and running.

Administrative (root) access to your local MySQL server.

A terminal, MySQL Workbench, or any preferred database client.

Installation Steps
Step 1: Initialize User and Privileges (setup.sql)
Before creating the tables, you need to run an initial environment setup script. This script configures the necessary database user, permissions, and initial security settings.

⚠️ Important Note: The setup.sql file contains sensitive configuration layout and is hidden from the public repository for security reasons as well as the .env variables used in database.php file.

To obtain this files, please contact the project creator / repository owner on GitHub to request a secure copy.

Once you have the file, log into your MySQL server as an administrator and execute it:

Bash
mysql -u root -p < path/to/setup.sql
Step 2: Build the Database Schema (schema.sql)
Once your environment and user privileges are ready, you need to build the structural database architecture. This file creates the database, tables, keys, and constraints. (Don't forget to create it with the Database opened)

Run the schema script located in the SQL/ directory:

Bash
mysql -u your_project_user -p < SQL/schema.sql
Step 3: Populate with Sample Data (sample.sql)
To test the application locally, you should populate the newly created tables with initial mock data and system configuration constants.

Run the sample data script located in the SQL/ directory:

Bash
mysql -u your_project_user -p < SQL/sample.sql
Verification
To ensure everything was configured correctly, log into your MySQL CLI and run the following commands to check the created structures:

SQL
SHOW DATABASES;
USE your_database_name;
SHOW TABLES;